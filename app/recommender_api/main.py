import pandas as pd
import numpy as np
import torch
import torch.nn as nn
import pytorch_lightning as pl
from torch.utils.data import Dataset, DataLoader
from sklearn.model_selection import train_test_split
from sklearn.metrics import f1_score, accuracy_score
from pytorch_lightning.loggers import CSVLogger
from sklearn.metrics import f1_score, accuracy_score, roc_auc_score, roc_curve
import os
import matplotlib.pyplot as plt

base_dir = os.path.dirname(__file__)
file_path = os.path.join(base_dir, "tmp", "Train_Dataset_Final.csv")
df = pd.read_csv(file_path)


# Codificare user_id / product_id
df["user_id"] = df["user_id"].astype("category")
df["product_id"] = df["product_id"].astype("category")
df["user_idx"] = df["user_id"].cat.codes
df["product_idx"] = df["product_id"].cat.codes

# === Selectare coloane ===
feature_cols = [
    col for col in df.columns
    if col not in ["user_id", "product_id", "user_idx", "product_idx", "target"]
]
df[feature_cols] = df[feature_cols].apply(pd.to_numeric, errors="coerce").fillna(0).astype(np.float32)

# === Dataset ===
class TrainDataset(Dataset):
    def __init__(self, df):
        self.users = torch.tensor(df["user_idx"].values, dtype=torch.long)
        self.items = torch.tensor(df["product_idx"].values, dtype=torch.long)
        self.features = torch.tensor(df[feature_cols].values, dtype=torch.float32)
        self.labels = torch.tensor(df["target"].values, dtype=torch.float32)

    def __len__(self): return len(self.labels)
    def __getitem__(self, idx): return self.users[idx], self.items[idx], self.features[idx], self.labels[idx]

class RecommenderDataModule(pl.LightningDataModule):
    def __init__(self, df, batch_size=256):
        super().__init__()
        self.df = df
        self.batch_size = batch_size

    def setup(self, stage=None):
        train_val_df, self.test_df = train_test_split(self.df, test_size=0.1, random_state=42)
        train_df, val_df = train_test_split(train_val_df, test_size=0.1111, random_state=42)
        self.train_dataset = TrainDataset(train_df)
        self.val_dataset = TrainDataset(val_df)
        self.test_dataset = TrainDataset(self.test_df)

    def train_dataloader(self): return DataLoader(self.train_dataset, batch_size=self.batch_size, shuffle=True,num_workers=4, persistent_workers=True)
    def val_dataloader(self): return DataLoader(self.val_dataset, batch_size=self.batch_size,num_workers=4, persistent_workers=True)
    def test_dataloader(self): return DataLoader(self.test_dataset, batch_size=self.batch_size,num_workers=4, persistent_workers=True)

class NCF(pl.LightningModule):
    def __init__(self, num_users, num_items, num_features, embedding_dim=16):
        super().__init__()
        self.save_hyperparameters()
        self.user_embedding = nn.Embedding(num_users, embedding_dim)
        self.item_embedding = nn.Embedding(num_items, embedding_dim)
        self.fc1 = nn.Linear(embedding_dim * 2 + num_features, 64)
        self.fc2 = nn.Linear(64, 32)
        self.output = nn.Linear(32, 1)
        self.loss_fn = nn.BCELoss()
        self.validation_step_outputs = []
        self.test_step_outputs = []

        user_features_df = df.groupby("user_idx")[feature_cols].mean().reset_index(drop=True)
        self.register_buffer("user_feature_matrix", torch.tensor(user_features_df.values, dtype=torch.float32))

    def get_similar_users_embedding(self, cold_user_features, top_k=5):
        norm_feats = self.user_feature_matrix / (self.user_feature_matrix.norm(dim=1, keepdim=True) + 1e-8)
        cold_norm = cold_user_features / (cold_user_features.norm(dim=1, keepdim=True) + 1e-8)
        similarities = torch.matmul(norm_feats, cold_norm.T).squeeze()
        top_k_indices = torch.topk(similarities, k=top_k).indices
        return self.user_embedding(top_k_indices).mean(dim=0, keepdim=True)

    def get_item_mean_embedding(self): return self.item_embedding.weight.data.mean(dim=0, keepdim=True)

    def forward(self, user_input, item_input, features):
        user_emb = self.user_embedding(user_input.clone())
        item_emb = self.item_embedding(item_input.clone())

        cold_user_mask = user_input >= self.hparams.num_users
        if cold_user_mask.any():
            cold_user_feats = features[cold_user_mask]
            for idx in range(cold_user_feats.shape[0]):
                user_emb[cold_user_mask][idx] = self.get_similar_users_embedding(cold_user_feats[idx].unsqueeze(0))

        cold_item_mask = item_input >= self.hparams.num_items
        if cold_item_mask.any():
            item_emb[cold_item_mask] = self.get_item_mean_embedding()

        x = torch.cat([user_emb, item_emb, features], dim=-1)
        x = torch.relu(self.fc1(x))
        x = torch.relu(self.fc2(x))
        return torch.sigmoid(self.output(x)).squeeze()

    def training_step(self, batch, batch_idx): #Este folosită pentru antrenarea efectivă a modelului. Se execută la fiecare batch în timpul epocii de antrenare (trainer.fit()).
        user, item, features, label = batch
        preds = self(user, item, features)
        loss = self.loss_fn(preds, label)
        self.log("train_loss", loss, on_step=False, on_epoch=True, prog_bar=True, logger=True)
        return loss

    def validation_step(self, batch, batch_idx): #Este folosită doar pentru evaluare. Se execută în timpul validării, după fiecare epocă (sau la final).
        user, item, features, label = batch
        preds = self(user, item, features).detach().cpu()
        preds_bin = (preds > 0.5).int()
        self.validation_step_outputs.append((preds_bin, label.cpu()))

    def on_validation_epoch_end(self):
        preds, labels = zip(*self.validation_step_outputs)
        preds = torch.cat(preds)
        labels = torch.cat(labels)
        self.log("val_f1", f1_score(labels, preds), prog_bar=True, on_step=False, on_epoch=True, logger=True)
        self.log("val_acc", accuracy_score(labels, preds), prog_bar=True, on_step=False, on_epoch=True, logger=True)

        self.validation_step_outputs.clear()

    def test_step(self, batch, batch_idx):
        user, item, features, label = batch
        preds = self(user, item, features).detach().cpu()
        preds_bin = (preds > 0.5).int()
        self.test_step_outputs.append((preds_bin, label.cpu()))

    def on_test_epoch_end(self):
        preds, labels = zip(*self.test_step_outputs)
        preds = torch.cat(preds)
        labels = torch.cat(labels)
        self.log("test_f1", f1_score(labels, preds), prog_bar=True, on_step=False, on_epoch=True, logger=True)
        self.log("test_acc", accuracy_score(labels, preds), prog_bar=True, on_step=False, on_epoch=True, logger=True)

         # === ROC AUC ===
        try:
            fpr, tpr, _ = roc_curve(labels, preds)
            auc_score = roc_auc_score(labels, preds)

            # Plot
            plt.figure()
            plt.plot(fpr, tpr, label=f"AUC = {auc_score:.2f}")
            plt.plot([0, 1], [0, 1], linestyle="--", color="gray")
            plt.xlabel("False Positive Rate")
            plt.ylabel("True Positive Rate")
            plt.title("AUC-ROC Curve")
            plt.legend()
            plt.grid(True)

            os.makedirs("public/recommender", exist_ok=True)
            plt.savefig("public/recommender/roc_curve.png")
            plt.close()
        except Exception as e:
            print(f"Eroare la generarea curbei ROC: {e}")

        self.test_step_outputs.clear()

    def configure_optimizers(self): return torch.optim.Adam(self.parameters(), lr=1e-3)

# === Main ===
if __name__ == "__main__":
    num_users = df["user_idx"].nunique()
    num_items = df["product_idx"].nunique()
    num_features = len(feature_cols)

    model = NCF(num_users, num_items, num_features)
    data = RecommenderDataModule(df)

    logger = CSVLogger("logs", name="ncf_model", version=None)

    trainer = pl.Trainer(
        max_epochs=10,
        accelerator="gpu" if torch.cuda.is_available() else "cpu",
        devices=1,
        logger=logger,
        enable_checkpointing=False,
        num_sanity_val_steps=0)

    trainer.fit(model, datamodule=data)
    trainer.test(model, datamodule=data)
    trainer.logger.finalize("success")

    os.makedirs("models", exist_ok=True)
    checkpoint_path = "models/ncf_model.ckpt"
    trainer.save_checkpoint(checkpoint_path)
    print(f"Modelul a fost salvat în: {checkpoint_path}")

    print("Antrenament finalizat.")

    # === Chart cu evoluția pe epoci ===
try:
    log_dir = os.path.join("logs", "ncf_model")
    version = sorted(os.listdir(log_dir))[-1]  # cea mai recentă versiune
    metrics_path = os.path.join(log_dir, version, "metrics.csv")
    metrics_df = pd.read_csv(metrics_path)

    # Filtrare coloane utile
    epochs = metrics_df["epoch"]
    train_loss = metrics_df[metrics_df["train_loss"].notna()]
    val_acc = metrics_df[metrics_df["val_acc"].notna()]
    val_f1 = metrics_df[metrics_df["val_f1"].notna()]

    plt.figure(figsize=(10, 6))
    plt.plot(train_loss["epoch"], train_loss["train_loss"], label="Train Loss", marker="o")
    plt.plot(val_acc["epoch"], val_acc["val_acc"], label="Validation Accuracy", marker="x")
    plt.plot(val_f1["epoch"], val_f1["val_f1"], label="Validation F1", marker="s")
    plt.xlabel("Epoch")
    plt.ylabel("Metric Value")
    plt.title("Evoluția metricei în timp")
    plt.legend()
    plt.grid(True)

    os.makedirs("public/recommender", exist_ok=True)
    plt.savefig("public/recommender/epoch_metrics.png")
    plt.close()
except Exception as e:
    print(f"Eroare la generarea graficului cu metricele: {e}")


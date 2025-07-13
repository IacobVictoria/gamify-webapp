import pandas as pd
import numpy as np
import torch
from sklearn.metrics.pairwise import cosine_similarity
from main import NCF 
from pytorch_lightning import seed_everything

seed_everything(42)

df = pd.read_csv("tmp/Train_Dataset_Final.csv")

# Codificare ID-uri
df["user_id"] = df["user_id"].astype("category")
df["product_id"] = df["product_id"].astype("category")
df["user_idx"] = df["user_id"].cat.codes
df["product_idx"] = df["product_id"].cat.codes

user_id_to_idx = dict(zip(df["user_id"], df["user_idx"]))
product_id_to_idx = dict(zip(df["product_id"], df["product_idx"]))
product_idx_to_id = dict(zip(df["product_idx"], df["product_id"]))

# Feature-uri numerice
feature_cols = [
    col for col in df.columns if col not in ["user_id", "product_id", "user_idx", "product_idx", "target"]
]
df[feature_cols] = df[feature_cols].apply(pd.to_numeric, errors="coerce").fillna(0).astype(np.float32)

# User de test
user_id = 236
user_idx = user_id_to_idx[user_id]

# Produse pozitive (target == 1)
positive_df = df[(df["user_idx"] == user_idx) & (df["target"] == 1)]
positive_products = positive_df["product_idx"].unique()

# Produse nevăzute de user
all_products = df[["product_idx", "product_id"] + feature_cols].drop_duplicates("product_idx")
unseen_df = all_products[~all_products["product_idx"].isin(positive_products)].copy()

# Model antrenat
model = NCF.load_from_checkpoint("models/ncf_model.ckpt")
model.eval()

# Testează primele 10 produse nevăzute, model vs cosine similarity
num_tests = 10
sampled_products = unseen_df.sample(num_tests, random_state=42)
incoerente_count = 0

for idx, test_product in sampled_products.iterrows():
    test_product_vector = test_product[feature_cols].astype(np.float32).values.reshape(1, -1)

    test_product_id = test_product["product_id"]
    test_product_idx = product_id_to_idx[test_product_id]

    # Calcul similaritatea medie cu produsele pozitive
    similarities = []
    for _, row in positive_df.iterrows():
        product_vector = row[feature_cols].values.reshape(1, -1)
        sim = cosine_similarity(test_product_vector, product_vector)[0][0]
        similarities.append(sim)
    average_similarity = np.mean(similarities)

    # Tensorii pentru predicție
    user_tensor = torch.tensor([user_idx], dtype=torch.long)
    item_tensor = torch.tensor([test_product_idx], dtype=torch.long)
    features_tensor = torch.tensor(test_product_vector, dtype=torch.float32)

    # Prezicere scor
    with torch.no_grad():
        prediction = model(user_tensor, item_tensor, features_tensor).item()

    print(f"\nProdus testat: {test_product_id}")
    print(f"Similaritate medie cu produsele cu target 1: {average_similarity:.4f}")
    print(f"Scor prezis de model: {prediction:.4f}")

    if average_similarity > 0.7:
        print("Produs similar cu cele cumpărate.")
        similar_flag = True
    else:
        print("Produs diferit de ce a preferat utilizatorul.")
        similar_flag = False

    if prediction > 0.5:
        print("AI zice că userul AR cumpăra produsul.")
        prediction_flag = True
    else:
        print("AI zice că userul NU ar cumpăra produsul.")
        prediction_flag = False
        
    if similar_flag != prediction_flag:
        incoerente_count += 1

print(f"\n Din 10 teste, {incoerente_count} au fost incoerente între similaritate și predicție.")

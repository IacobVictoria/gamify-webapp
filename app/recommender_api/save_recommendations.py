import torch
import pandas as pd
import os

def generate_recommendations(model, df, output_csv_path=None):
    model.eval()

    if output_csv_path is None:
        base_path = os.path.dirname(__file__)
        output_csv_path = os.path.join(base_path, "tmp", "recommendations.csv")

    # Encoderi inversi pentru user_id și product_id
    user_encoder = dict(enumerate(df["user_id"].astype("category").cat.categories))
    product_encoder = dict(enumerate(df["product_id"].astype("category").cat.categories))

    user_idxs = df["user_idx"].unique()
    product_idxs = df["product_idx"].unique()

    all_combinations = [(u, p) for u in user_idxs for p in product_idxs]
    batch_size = 2048
    results = []

    with torch.no_grad():
        for i in range(0, len(all_combinations), batch_size):
            batch = all_combinations[i:i + batch_size]
            user_batch = torch.tensor([x[0] for x in batch], dtype=torch.long)
            product_batch = torch.tensor([x[1] for x in batch], dtype=torch.long)

            features_batch = model.user_feature_matrix[user_batch]

            scores = model(user_batch, product_batch, features_batch).cpu().numpy()

            for (u_idx, p_idx), score in zip(batch, scores):
                results.append({
                    "user_id": user_encoder[u_idx],
                    "product_id": product_encoder[p_idx],
                    "score": float(score)
                })

    recommendations_df = pd.DataFrame(results)
    recommendations_df.to_csv(output_csv_path, index=False)

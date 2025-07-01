import torch
import pandas as pd
import numpy as np
from sqlalchemy import create_engine, text
from main import NCF 
import uuid
from datetime import datetime
from concurrent.futures import ThreadPoolExecutor, as_completed

DB_URL = "mysql+pymysql://root:@localhost/gamify_recommender"
MODEL_PATH = "models/ncf_model.ckpt"
THRESHOLD = 0.5
TOP_K = 10
MAX_WORKERS = 4  

engine = create_engine(DB_URL)

query = """
SELECT user_id, product_id, target, has_bought, has_wishlisted, has_reviewed, user_rating,
       gender, score_in_app, price, stock, calories, protein, carbs, fats, fiber, sugar,
       category_Detox, category_EnergyDrinks, category_HealthySnacks, category_OrganicFoods,
       category_Supplements, category_Vitamins, category_WeightLoss,
       allergen_eggs, allergen_lactose, allergen_none, allergen_nuts, allergen_gluten,
       allergen_soy, allergen_fish,
       cluster_0, cluster_1, cluster_2, cluster_3, health_score, total_units_sold,
       wishlist_count, average_rating, review_count, avg_likes_per_review
FROM recommendation_data
"""

df = pd.read_sql(query, engine)

# Codificare
df["user_id"] = df["user_id"].astype("category")
df["product_id"] = df["product_id"].astype("category")
df["user_idx"] = df["user_id"].cat.codes
df["product_idx"] = df["product_id"].cat.codes

user_id_to_idx = dict(zip(df["user_id"], df["user_idx"]))
product_idx_to_id = dict(zip(df["product_idx"], df["product_id"]))

feature_cols = [col for col in df.columns if col not in ["user_id", "product_id", "user_idx", "product_idx", "target"]]
df[feature_cols] = df[feature_cols].astype(np.float32)

num_users = df["user_idx"].nunique()
num_items = df["product_idx"].nunique()
num_features = len(feature_cols)

model = NCF.load_from_checkpoint(MODEL_PATH, num_users=num_users, num_items=num_items, num_features=num_features)
model.eval()

def predict_for_user(user_idx):
    user_id = df.loc[df["user_idx"] == user_idx, "user_id"].iloc[0]

    seen_products = df[(df["user_idx"] == user_idx) & (df["target"] == 1)]["product_idx"].unique()
    unseen_products = [pid for pid in range(num_items) if pid not in seen_products]

    unseen_df = df[df["product_idx"].isin(unseen_products)].drop_duplicates("product_idx")
    if len(unseen_df) == 0:
        return []

    user_tensor = torch.tensor([user_idx] * len(unseen_df), dtype=torch.long)
    item_tensor = torch.tensor(unseen_df["product_idx"].values, dtype=torch.long)
    features_tensor = torch.tensor(unseen_df[feature_cols].values, dtype=torch.float32)

    with torch.no_grad():
        scores = model(user_tensor, item_tensor, features_tensor).cpu().numpy()

    filtered = [(pid, score) for pid, score in zip(unseen_df["product_idx"].values, scores) if score >= THRESHOLD]
    top_k = sorted(filtered, key=lambda x: x[1], reverse=True)[:TOP_K]

    recs = []
    for prod_idx, score in top_k:
        recs.append({
            "id": str(uuid.uuid4()),
            "user_id": user_id,
            "product_id": product_idx_to_id[prod_idx],
            "score": score,
            "created_at": datetime.now(),
            "updated_at": datetime.now(),
        })
    return recs


recommendations = []

users = df["user_idx"].unique()

with ThreadPoolExecutor(max_workers=MAX_WORKERS) as executor:
    futures = {executor.submit(predict_for_user, user_idx): user_idx for user_idx in users}

    for future in as_completed(futures):
        recs = future.result()
        if recs:
            recommendations.extend(recs)

with engine.begin() as conn:
    conn.execute(text("TRUNCATE TABLE recommended_products"))

    if recommendations:
        conn.execute(
            text("""
                INSERT INTO recommended_products (id, user_id, product_id, score, created_at, updated_at)
                VALUES (:id, :user_id, :product_id, :score, :created_at, :updated_at)
            """),
            recommendations
        )

print("Recomandările au fost generate și salvate cu succes.")

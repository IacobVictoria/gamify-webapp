import pandas as pd
import numpy as np
import os
from sqlalchemy import create_engine

np.random.seed(42)

engine = create_engine("mysql+pymysql://root:@localhost/gamify_recommender")

# === Încarcă date din MySQL ===
users_df = pd.read_sql("SELECT id AS user_id, gender, score FROM users", engine)
products_df = pd.read_sql("SELECT * FROM products", engine)
orders_df = pd.read_sql("SELECT id AS order_id, user_id FROM client_orders", engine)
order_products_df = pd.read_sql("SELECT order_id, product_id, quantity FROM order_products", engine)
wishlists_df = pd.read_sql("SELECT user_id, product_id FROM wishlists", engine)
reviews_df = pd.read_sql("SELECT user_id, product_id, rating, likes FROM reviews", engine)

# === Încarcă pozitivii ===
base_path = os.path.dirname(__file__)
positive_df = pd.read_csv(os.path.join(base_path, "tmp", "Train_Dataset_Positive.csv"))

# === Încarcă negativele existente (dacă sunt) ===
existing_neg_path = os.path.join(base_path, "tmp", "Train_Dataset_Negative.csv")
existing_negatives = set()
if os.path.exists(existing_neg_path):
    existing_neg_df = pd.read_csv(existing_neg_path)
    existing_negatives = set(zip(existing_neg_df["user_id"], existing_neg_df["product_id"]))
else:
    existing_neg_df = pd.DataFrame()

# === Conversii consistente ===
for df in [users_df, products_df, orders_df, order_products_df, wishlists_df, reviews_df, positive_df]:
    for col in df.columns:
        if "user_id" in col or "product_id" in col or "order_id" in col:
            df[col] = df[col].astype(str)

# === Lookup și metadata ===
positive_pairs = set(zip(positive_df["user_id"], positive_df["product_id"]))
all_users = users_df["user_id"].astype(str).unique()
all_products = products_df["id"].astype(str).unique()
wishlist_set = set(zip(wishlists_df["user_id"], wishlists_df["product_id"]))
review_map = reviews_df.set_index(["user_id", "product_id"])["rating"].to_dict()
user_info = users_df.set_index("user_id")[["gender", "score"]].rename(columns={"score": "score_in_app"})

# === Generează doar noi negative ===
negatives = []
needed = len(positive_df)
generated = 0
tried = 0
max_tries = needed * 20

while generated < needed and tried < max_tries:
    tried += 1
    user_id = np.random.choice(all_users)
    product_id = np.random.choice(all_products)
    key = (user_id, product_id)
    if key in positive_pairs or key in wishlist_set or key in review_map or key in existing_negatives:
        continue

    negatives.append({
        "user_id": user_id,
        "product_id": product_id,
        "target": 0,
        "has_bought": 0,
        "has_wishlisted": 0,
        "has_reviewed": 0,
        "user_rating": 0,
    })
    generated += 1

neg_df = pd.DataFrame(negatives)

# === Features utilizator ===
user_info["gender"] = user_info["gender"].map({"female": 0, "male": 1})
user_info.index = user_info.index.astype(str)
neg_df = neg_df.merge(user_info, how="left", left_on="user_id", right_index=True)

# === Features produs ===
product_features = products_df.rename(columns={"id": "product_id"})
neg_df = neg_df.merge(product_features, how="left", on="product_id")

# === Agregări extra ===
units_df = order_products_df.groupby("product_id")["quantity"].sum().reset_index(name="total_units_sold")
wishlist_agg_df = wishlists_df.groupby("product_id")["user_id"].nunique().reset_index(name="wishlist_count")
review_agg_df = reviews_df.groupby("product_id").agg(
    average_rating=("rating", "mean"),
    review_count=("rating", "count"),
    avg_likes_per_review=("likes", "mean")
).reset_index()

neg_df = neg_df.merge(units_df, on="product_id", how="left")
neg_df = neg_df.merge(wishlist_agg_df, on="product_id", how="left")
neg_df = neg_df.merge(review_agg_df, on="product_id", how="left")

fill_cols = ["total_units_sold", "wishlist_count", "average_rating", "review_count", "avg_likes_per_review"]
neg_df[fill_cols] = neg_df[fill_cols].fillna(0)

# === Combină cu cele existente și limitează la numărul de pozitivi ===
combined_neg_df = pd.concat([existing_neg_df, neg_df], ignore_index=True)
final_neg_df = combined_neg_df.drop_duplicates(subset=["user_id", "product_id"])
final_neg_df = final_neg_df.sample(frac=1, random_state=42).reset_index(drop=True)
final_neg_df = final_neg_df.iloc[:len(positive_df)]

# === Salvare ===
output_path = os.path.join(base_path, "tmp", "Train_Dataset_Negative.csv")
final_neg_df.to_csv(output_path, index=False)

print(f"Negative dataset final salvat cu {len(final_neg_df)} exemple.")

import pandas as pd
import numpy as np

np.random.seed(42)

from sqlalchemy import create_engine
import pandas as pd
import numpy as np
import os

engine = create_engine("mysql+pymysql://root:@localhost/gamify_recommender")

users_df = pd.read_sql("SELECT id AS user_id, gender, score FROM users", engine)
products_df = pd.read_sql("SELECT * FROM products", engine)
orders_df = pd.read_sql("SELECT id AS order_id, user_id FROM client_orders", engine)
order_products_df = pd.read_sql("SELECT order_id, product_id, quantity FROM order_products", engine)
wishlists_df = pd.read_sql("SELECT user_id, product_id FROM wishlists", engine)
reviews_df = pd.read_sql("SELECT user_id, product_id, rating, likes FROM reviews", engine)


# === Conversii pentru consistență ===
products_df["id"] = products_df["id"].astype(str)
orders_df["order_id"] = orders_df["order_id"].astype(str)
orders_df["user_id"] = orders_df["user_id"].astype(str)
order_products_df["order_id"] = order_products_df["order_id"].astype(str)
order_products_df["product_id"] = order_products_df["product_id"].astype(str)
wishlists_df["user_id"] = wishlists_df["user_id"].astype(str)
wishlists_df["product_id"] = wishlists_df["product_id"].astype(str)
reviews_df["user_id"] = reviews_df["user_id"].astype(str)
reviews_df["product_id"] = reviews_df["product_id"].astype(str)

# === Adaugă user_id în order_products ===
order_products_df = order_products_df.merge(orders_df[["order_id", "user_id"]], on="order_id")

# === Seturi pentru lookup ===
bought_set = set(zip(order_products_df["user_id"], order_products_df["product_id"]))
wishlist_set = set(zip(wishlists_df["user_id"], wishlists_df["product_id"]))
review_map = reviews_df.set_index(["user_id", "product_id"])["rating"].to_dict()

# === Feature: has_bought / has_wishlisted / has_reviewed / rating ===
positive_records = []
seen = set()

for row in order_products_df.itertuples():
    key = (row.user_id, row.product_id)
    if key in seen:
        continue
    seen.add(key)

    positive_records.append({
        "user_id": row.user_id,
        "product_id": row.product_id,
        "target": 1,
        "has_bought": 1,
        "has_wishlisted": int(key in wishlist_set),
        "has_reviewed": int(key in review_map),
        "user_rating": review_map.get(key, 0)
    })

# === Adaugă produse din wishlist care NU sunt cumpărate ===
wishlist_only = wishlist_set - bought_set
for user_id, product_id in wishlist_only:
    positive_records.append({
        "user_id": user_id,
        "product_id": product_id,
        "target": 1,
        "has_bought": 0,
        "has_wishlisted": 1,
        "has_reviewed": int((user_id, product_id) in review_map),
        "user_rating": review_map.get((user_id, product_id), 0)
    })

positive_df = pd.DataFrame(positive_records)

# === Adaugă user features ===
users_df = users_df.rename(columns={"id": "user_id", "score": "score_in_app"})
users_df["gender"] = users_df["gender"].map({"female": 0, "male": 1})
positive_df["user_id"] = positive_df["user_id"].astype(str)
users_df["user_id"] = users_df["user_id"].astype(str)

positive_df = positive_df.merge(users_df[["user_id", "gender", "score_in_app"]], on="user_id", how="left")

# === Adaugă agregări din reviews și wishlist ===
# Top produse: cele mai cumpărate, cele mai wishlistate, cele mai bine evaluate
units_df = order_products_df.groupby("product_id")["quantity"].sum().reset_index(name="total_units_sold")
wishlist_agg_df = wishlists_df.groupby("product_id")["user_id"].nunique().reset_index(name="wishlist_count")
review_agg_df = reviews_df.groupby("product_id").agg(
    average_rating=("rating", "mean"),
    review_count=("rating", "count"),
    avg_likes_per_review=("likes", "mean")
).reset_index()

# === Combina cu features din produse ===
products_df = products_df.merge(units_df, how="left", left_on="id", right_on="product_id")
products_df = products_df.merge(wishlist_agg_df, how="left", left_on="id", right_on="product_id")
products_df = products_df.merge(review_agg_df, how="left", left_on="id", right_on="product_id")
products_df.drop(columns=["product_id"], inplace=True)

products_df[["total_units_sold", "wishlist_count", "average_rating", "review_count", "avg_likes_per_review"]] = (
    products_df[["total_units_sold", "wishlist_count", "average_rating", "review_count", "avg_likes_per_review"]]
    .fillna(0)
)

positive_df = positive_df.merge(products_df, how="left", left_on="product_id", right_on="id").drop(columns=["id"])

positive_df = positive_df.sample(frac=1, random_state=42).reset_index(drop=True)

import os

output_path = os.path.join(os.path.dirname(__file__), "tmp", "Train_Dataset_Positive.csv")
positive_df.to_csv(output_path, index=False)
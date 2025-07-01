import pandas as pd
from sqlalchemy import create_engine
from datetime import datetime, timedelta
from sklearn.preprocessing import MinMaxScaler

engine = create_engine("mysql+pymysql://root:@localhost/gamify_recommender")

three_months_ago = datetime.now() - timedelta(days=90)
three_months_ago_str = three_months_ago.strftime('%Y-%m-%d %H:%M:%S')

# --- 1. Citește datele brute filtrate pe ultimele 3 luni ---

orders_df = pd.read_sql(f"""
    SELECT id AS order_id, user_id, created_at, updated_at FROM client_orders
    WHERE created_at >= '{three_months_ago_str}' OR updated_at >= '{three_months_ago_str}'
""", engine)

order_products_df = pd.read_sql(f"""
    SELECT op.order_id, op.product_id, op.quantity FROM order_products op
    JOIN client_orders co ON op.order_id = co.id
    WHERE co.created_at >= '{three_months_ago_str}' OR co.updated_at >= '{three_months_ago_str}'
""", engine)

wishlists_df = pd.read_sql(f"""
    SELECT user_id, product_id, created_at, updated_at FROM wishlists
    WHERE created_at >= '{three_months_ago_str}' OR updated_at >= '{three_months_ago_str}'
""", engine)

reviews_df = pd.read_sql(f"""
    SELECT user_id, product_id, rating, likes, created_at, updated_at FROM reviews
    WHERE created_at >= '{three_months_ago_str}' OR updated_at >= '{three_months_ago_str}'
""", engine)

# --- 2. Construiește interacțiunile user-product ---

bought_df = order_products_df.merge(orders_df[['order_id', 'user_id']], on='order_id')
bought_df = bought_df[['user_id', 'product_id']].drop_duplicates()
bought_df['has_bought'] = 1

wishlists_df = wishlists_df[['user_id', 'product_id']].drop_duplicates()
wishlists_df['has_wishlisted'] = 1

reviews_df = reviews_df[['user_id', 'product_id', 'rating']].drop_duplicates()
reviews_df['has_reviewed'] = 1

# Calculează media ratingurilor user-product
user_rating_df = reviews_df.groupby(['user_id', 'product_id'])['rating'].mean().reset_index()
review_map = user_rating_df.set_index(['user_id', 'product_id'])['rating'].to_dict()

# Construiește tabelul de interacțiuni
interactions = pd.concat([
    bought_df[['user_id', 'product_id', 'has_bought']],
    wishlists_df[['user_id', 'product_id', 'has_wishlisted']],
    reviews_df[['user_id', 'product_id', 'has_reviewed']]
], axis=0)

interactions_agg = interactions.groupby(['user_id', 'product_id']).agg({
    'has_bought': 'max',
    'has_wishlisted': 'max',
    'has_reviewed': 'max'
}).fillna(0).reset_index()

interactions_agg['target'] = 1

# Adaugă user_rating
interactions_agg['user_rating'] = interactions_agg.apply(
    lambda row: review_map.get((row['user_id'], row['product_id']), 0),
    axis=1
)

scaler = MinMaxScaler()
interactions_agg['user_rating'] = scaler.fit_transform(interactions_agg[['user_rating']])

# --- 3. Agregările produselor ---

units_df = order_products_df.groupby("product_id")["quantity"].sum().reset_index(name="total_units_sold")
wishlist_count_df = wishlists_df.groupby("product_id")["user_id"].nunique().reset_index(name="wishlist_count")

reviews_df["rating"] = pd.to_numeric(reviews_df.get("rating", pd.Series(dtype=float)), errors="coerce")
reviews_df["likes"] = pd.to_numeric(reviews_df.get("likes", pd.Series(dtype=float)), errors="coerce")

review_agg_df = reviews_df.groupby("product_id").agg(
    average_rating=("rating", "mean"),
    review_count=("rating", "count"),
    avg_likes_per_review=("likes", "mean")
).reset_index()

product_agg_df = units_df.merge(wishlist_count_df, on="product_id", how="left") \
                         .merge(review_agg_df, on="product_id", how="left") \
                         .fillna({
                             "wishlist_count": 0,
                             "average_rating": 0,
                             "review_count": 0,
                             "avg_likes_per_review": 0
                         })

cols_to_scale = ["total_units_sold", "wishlist_count", "average_rating", "review_count", "avg_likes_per_review"]

# Înlocuire valori NaN cu 0 pentru aceste coloane
product_agg_df[cols_to_scale] = product_agg_df[cols_to_scale].fillna(0)

# Aplicare scalare MinMax
scaler = MinMaxScaler()
product_agg_df[cols_to_scale] = scaler.fit_transform(product_agg_df[cols_to_scale])

# --- 4. Citește datele procesate de users și products din csv---
import os

base_path = os.path.abspath("tmp")
file_path = os.path.join(base_path, "Process_Users.csv")
users_df = pd.read_csv(file_path)

file_path_prod = os.path.join(base_path, "Processed_Products.csv")
products_df = pd.read_csv(file_path_prod)
products_df = products_df.rename(columns={"id": "product_id"})



# --- 5. Combină toate datele într-un singur DataFrame final ---

df = interactions_agg.merge(users_df, on="user_id", how="left")
df = df.merge(products_df, on="product_id", how="left")
df = df.merge(product_agg_df, on="product_id", how="left")

# --- 6. Aranjează coloanele în ordine ---

expected_cols = [
    "user_id", "product_id", "target", "has_bought", "has_wishlisted", "has_reviewed",
    "user_rating", "gender", "score_in_app", "price", "stock", "calories", "protein",
    "carbs", "fats", "fiber", "sugar", "category_Detox", "category_EnergyDrinks",
    "category_HealthySnacks", "category_OrganicFoods", "category_Supplements",
    "category_Vitamins", "category_WeightLoss", "allergen_eggs", "allergen_lactose",
    "allergen_none", "allergen_nuts", "allergen_gluten", "allergen_soy", "allergen_fish",
    "cluster_0", "cluster_1", "cluster_2", "cluster_3", "health_score", "total_units_sold",
    "wishlist_count", "average_rating", "review_count", "avg_likes_per_review"
]

df = df[expected_cols]

# --- 7. Salvează rezultatul final într-un singur CSV ---

df.to_csv("tmp/recommendation_data_final.csv", index=False)

print("CSV final pentru recommendation_data a fost generat cu succes.")

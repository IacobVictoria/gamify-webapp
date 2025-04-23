import pandas as pd
from sqlalchemy import create_engine

# Conexiunea la baza de date MySQL
engine = create_engine("mysql+pymysql://root:@127.0.0.1:3306/gamify_recommender")
print("✅ Conexiune reușită")

# Extrage datele
users_df = pd.read_sql("SELECT id AS user_id, gender, score FROM users", engine)
orders_df = pd.read_sql("SELECT id AS order_id, user_id FROM client_orders", engine)
order_products_df = pd.read_sql("SELECT order_id, product_id, quantity, price FROM order_products", engine)
products_df = pd.read_sql("SELECT * FROM products", engine)
wishlists_df = pd.read_sql("SELECT user_id, product_id FROM wishlists", engine)
reviews_df = pd.read_sql("SELECT user_id, product_id, rating, likes FROM reviews", engine)
print("Users:", users_df.shape)
print("Orders:", orders_df.shape)
print("Order-Products:", order_products_df.shape)
print("Products:", products_df.shape)
print("Wishlists:", wishlists_df.shape)
print("Reviews:", reviews_df.shape)
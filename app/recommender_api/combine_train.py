import pandas as pd
import os

# Calea relativă dinamică
base_dir = os.path.dirname(__file__)
pos_path = os.path.join(base_dir, "tmp", "Train_Dataset_Positive.csv")
neg_path = os.path.join(base_dir, "tmp", "Train_Dataset_Negative.csv")
final_path = os.path.join(base_dir, "tmp", "Train_Dataset_Final.csv")

# Încarcă și combină
pos = pd.read_csv(pos_path)
neg = pd.read_csv(neg_path)
df = pd.concat([pos, neg], ignore_index=True).sample(frac=1, random_state=42)

# === Verificare cold users și cold items ===
from sqlalchemy import create_engine

engine = create_engine("mysql+pymysql://root:@localhost/gamify_recommender")

# Încarcă useri și produse reale din DB
users_df = pd.read_sql("SELECT id FROM users", engine)
products_df = pd.read_sql("SELECT id FROM products", engine)

# Normalizează ca string
all_users = set(users_df["id"].astype(str))
all_products = set(products_df["id"].astype(str))

# Useri și produse folosite în datasetul combinat
used_users = set(df["user_id"].astype(str))
used_products = set(df["product_id"].astype(str))

# Diferențele = cold
cold_users = all_users - used_users
cold_items = all_products - used_products

print(f"\nCold users (care lipsesc din training set): {len(cold_users)}")
print(f"Cold items (care lipsesc din training set): {len(cold_items)}")

# Salvează
df.to_csv(final_path, index=False)
print(f"Train_Dataset_Final.csv salvat cu {len(df)} rânduri.")

import pandas as pd
import numpy as np
from sqlalchemy import create_engine
from sklearn.preprocessing import MinMaxScaler
from sklearn.cluster import KMeans
import os

#Conexiune DB
engine = create_engine("mysql+pymysql://root:@localhost/gamify_recommender")
products_df = pd.read_sql("SELECT * FROM products", engine)

#PASUL 1: Tratarea valorilor lipsă
products_df["allergens"] = products_df["allergens"].fillna("")

#PASUL 2: Codificare categorii, one hot encoding
products_df["raw_category"] = products_df["category"]
products_df = pd.get_dummies(products_df, columns=["category"], dtype=int)

#PASUL 2.1: Procesare alergeni
products_df["allergens_list"] = products_df["allergens"].apply(
    lambda x: [a.strip().lower() for a in x.split(",") if a.strip()]
)
all_allergens = set()
products_df["allergens_list"].apply(lambda x: all_allergens.update(x))
for allergen in all_allergens:
    products_df[f"allergen_{allergen}"] = products_df["allergens_list"].apply(lambda x: int(allergen in x))
products_df.drop(["allergens", "allergens_list"], axis=1, inplace=True)

#PASUL 3: Tratarea valorilor numerice + outlieri
numeric_cols = ["price", "calories", "protein", "carbs", "fats", "fiber", "sugar"]
products_df[numeric_cols] = products_df[numeric_cols].fillna(0)
for col in numeric_cols:
    products_df[col] = np.log1p(products_df[col])  # atenuare outlieri

#PASUL 4: Normalizare
scaler = MinMaxScaler()
products_df[numeric_cols] = scaler.fit_transform(products_df[numeric_cols])

#PASUL 5: Clustering nutrițional + scor sănătate
# 5.1 KMeans clustering
nutri_cols = ["calories", "protein", "carbs", "fats", "fiber", "sugar"]
kmeans = KMeans(n_clusters=4, random_state=42)
products_df["nutrition_cluster"] = kmeans.fit_predict(products_df[nutri_cols])

# 5.2 One-hot encoding clustere
products_df = pd.get_dummies(products_df, columns=["nutrition_cluster"], prefix="cluster")
cluster_cols = [col for col in products_df.columns if col.startswith("cluster_")]
products_df[cluster_cols] = products_df[cluster_cols].astype(int)

# 5.3 Calcul health_score
products_df["health_score"] = (
    products_df["protein"] +
    products_df["fiber"] -
    products_df["sugar"] -
    products_df["fats"] -
    products_df["calories"]
)

output_path = os.path.join(os.path.dirname(__file__), "tmp", "Processed_Products.csv")
products_df.to_csv(output_path, index=False)

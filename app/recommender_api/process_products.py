import pandas as pd
import numpy as np
from sqlalchemy import create_engine
from sklearn.cluster import KMeans
from sklearn.preprocessing import MinMaxScaler

engine = create_engine("mysql+pymysql://root:@localhost/gamify_recommender")

products_df = pd.read_sql("SELECT * FROM products", engine)
print(products_df.head())

products_df["raw_category"] = products_df["category"]

products_df = pd.get_dummies(products_df, columns=["category"], dtype=int) #one hot encoding pt category

products_df["allergens"] = products_df["allergens"].fillna("")

products_df["allergens_list"] = products_df["allergens"].apply(lambda x: [a.strip().lower() for a in x.split(",") if a.strip() != ""])

# Obține lista unică de alergeni
all_allergens = set()
products_df["allergens_list"].apply(lambda x: all_allergens.update(x))

# Adaugă câte o coloană binară pentru fiecare alergen
for allergen in all_allergens:
    products_df[f"allergen_{allergen}"] = products_df["allergens_list"].apply(lambda x: int(allergen in x))

products_df.drop(["allergens", "allergens_list"], axis=1, inplace=True)

#NORMALIZARE
numeric_cols = ["price","calories", "protein", "carbs", "fats", "fiber", "sugar"]
for col in numeric_cols:
    products_df[col] = np.log1p(products_df[col])

# Normalizare între 0 și 1
scaler = MinMaxScaler()
products_df[numeric_cols] = scaler.fit_transform(products_df[numeric_cols])

import os

output_path = os.path.join(os.path.dirname(__file__), "tmp", "Processed_Products.csv")
products_df.to_csv(output_path, index=False)

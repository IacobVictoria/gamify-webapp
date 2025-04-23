import pandas as pd
from sklearn.preprocessing import MinMaxScaler
from sqlalchemy import create_engine

# === Conexiune DB
engine = create_engine("mysql+pymysql://root:@localhost/gamify_recommender")

users_df = pd.read_sql("SELECT id AS user_id, gender, score AS score_in_app FROM users", engine)


# === Encode gender (male=1, female=0) ===
users_df["gender"] = users_df["gender"].map({"Male": 1, "Female": 0})

# === Normalize score_in_app între 0 și 1 ===
scaler = MinMaxScaler()
users_df["score_in_app"] = scaler.fit_transform(users_df[["score_in_app"]])

# === Selectează doar coloanele utile ===
processed_users = users_df[["user_id", "gender", "score_in_app"]]

import os

output_path = os.path.join(os.path.dirname(__file__), "tmp", "Process_Users.csv")
processed_users.to_csv(output_path, index=False)


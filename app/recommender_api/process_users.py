import pandas as pd
from sklearn.preprocessing import MinMaxScaler
from sqlalchemy import create_engine
import os

engine = create_engine("mysql+pymysql://root:@localhost/gamify_recommender")
users_df = pd.read_sql("SELECT id AS user_id, gender, score AS score_in_app FROM users", engine)

#PASUL 1: Tratarea valorilor lipsă
users_df["score_in_app"] = users_df["score_in_app"].fillna(0)
users_df["gender"] = users_df["gender"].fillna("Unknown")

#PASUL 2: Codificarea valorilor non-numerice (gender)
users_df["gender"] = users_df["gender"].map({"Male": 1, "Female": 0}).fillna(-1)

#PASUL 3: Tratarea valorilor extreme

#PASUL 4: Scalarea scorului în aplicație
scaler = MinMaxScaler()
users_df["score_in_app"] = scaler.fit_transform(users_df[["score_in_app"]])

processed_users = users_df[["user_id", "gender", "score_in_app"]]

output_path = os.path.join(os.path.dirname(__file__), "tmp", "Process_Users.csv")
processed_users.to_csv(output_path, index=False)

# save_recommendations.py
import pandas as pd
from sqlalchemy import create_engine, text
from datetime import datetime
import uuid

def generate_recommendations_to_db(csv_path=None, db_url="mysql+pymysql://root:@localhost/gamify_recommender"):
    engine = create_engine(db_url)

    import os

    base_dir = os.path.dirname(__file__)  # directorul scriptului
    csv_path = os.path.join(base_dir, "tmp", "recommendations.csv")


    with engine.begin() as conn:
        conn.execute(text("TRUNCATE TABLE recommended_products"))

    df = pd.read_csv(csv_path)

    top_recs = (
        df.sort_values(["user_id", "score"], ascending=[True, False])
        .groupby("user_id")
        .head(10)
        .reset_index(drop=True)
    )

    top_recs["id"] = [str(uuid.uuid4()) for _ in range(len(top_recs))]
    now = datetime.now()
    top_recs["created_at"] = now
    top_recs["updated_at"] = now

    top_recs = top_recs[["id", "user_id", "product_id", "score", "created_at", "updated_at"]]
    top_recs.to_sql("recommended_products", con=engine, if_exists="append", index=False)

    print("Top 10 recomandari per utilizator salvate in baza de date.")

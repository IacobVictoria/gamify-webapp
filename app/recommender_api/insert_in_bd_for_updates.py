import pandas as pd
from sqlalchemy import create_engine, text
from datetime import datetime

engine = create_engine("mysql+pymysql://root:@localhost/gamify_recommender")

df = pd.read_csv("tmp/recommendation_data_final.csv")

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

now = datetime.now()
df["created_at"] = now
df["updated_at"] = now

with engine.begin() as conn:
    records = df.to_dict(orient='records')
    conn.execute(
        text(f"""
            INSERT INTO recommendation_data ({', '.join(expected_cols)}, created_at, updated_at)
            VALUES ({', '.join(':' + col for col in expected_cols)}, :created_at, :updated_at)
        """),
        records
    )
    print(f"{len(records)} înregistrări au fost adăugate în recommendation_data.")

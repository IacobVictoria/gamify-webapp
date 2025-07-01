import pandas as pd
from sqlalchemy import create_engine

engine = create_engine("mysql+pymysql://root:@localhost/gamify_recommender")

query = """
SELECT t.*
FROM recommendation_data t
INNER JOIN (
    SELECT user_id, product_id, MAX(created_at) AS max_created
    FROM recommendation_data
    WHERE created_at >= (NOW() - INTERVAL 3 MONTH)
    GROUP BY user_id, product_id
) tmax ON t.user_id = tmax.user_id AND t.product_id = tmax.product_id AND t.created_at = tmax.max_created
"""

df = pd.read_sql(query, engine)

# Echilibrează datele: ia la fel de multe exemple cu target=1 și target=0

df_1 = df[df['target'] == 1]
df_0 = df[df['target'] == 0]

min_count = min(len(df_1), len(df_0))

df_1_balanced = df_1.sample(n=min_count, random_state=42)
df_0_balanced = df_0.sample(n=min_count, random_state=42)

df_balanced = pd.concat([df_1_balanced, df_0_balanced]).sample(frac=1, random_state=42).reset_index(drop=True)



df_balanced.to_csv("tmp/Train_Dataset_Final.csv", index=False)
print(f"CSV pregătit cu {len(df_balanced)} rânduri echilibrate (target=1 și 0)")

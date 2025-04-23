#regula de 50% pozitive (interacțiuni reale), 50% negative (non-interacțiuni)
#Useri activi: doar 85% dintre utilizatori au comenzi.

# Cold users: 15% dintre useri nu apar în comenzi.
#
# Date realiste: fiecare comandă are o dată aleatoare în ultimele 90 de zile.
#
# 1–5 comenzi per user activ, pentru diversitate.

import pandas as pd
import numpy as np
from datetime import datetime, timedelta
import random

# === Setări ===
np.random.seed(42)
random.seed(42)

# === Încarcă utilizatorii ===
users_df = pd.read_csv("../dataset/Generated_Users.csv") 
user_ids = users_df["id"].tolist()

# === Împarte userii în activi și inactivi ===
num_users = len(user_ids)
num_active = int(0.85 * num_users)
active_users = np.random.choice(user_ids, size=num_active, replace=False)
active_users_set = set(active_users)

# === Generează comenzi pentru userii activi ===
orders = []
order_id = 1
today = datetime.today()

for user_id in active_users:
    num_orders = np.random.randint(1, 6) 
    for _ in range(num_orders):
        order_date = today - timedelta(days=np.random.randint(1, 90))
        orders.append({
            "order_id": order_id,
            "user_id": user_id,
            "order_date": order_date.strftime("%Y-%m-%d")
        })
        order_id += 1

# === Salvare CSV ===
orders_df = pd.DataFrame(orders)
orders_df.to_csv("../dataset/Generated_Client_Orders.csv", index=False)

print(f"Au fost generate {len(orders_df)} comenzi pentru {len(active_users_set)} useri activi.")
print(f"{num_users - len(active_users_set)} useri sunt cold (fără comenzi).")

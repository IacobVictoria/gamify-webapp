#20% dintre useri pot recumpăra un produs dintr-o comandă anterioară
#Cold items: 10% dintre produse să nu apară niciodată în order_products.
#Noise control:
#Prețul = product_price * random.uniform(0.9, 1.1)
#Cantitatea = aleatoare: np.random.randint(1, 4)
#Nu repeta comenzi identice	Fiecare comandă să fie diferită într-un fel
# userii care au scor mare (score > 1000) → comenzi mai frecvente și mai variate
#useri cu scor mic → comenzi rare, 1 produs per comandă
# quantity mai mare pentru produse ieftine (ex: dacă preț < 10, cumpără 2–3)
#NU genera comenzi perfecte — mai pune și produse aleatorii uneori. dar Aloci fiecărui user: 2 categorii preferate
#Dacă ai useri cu scor > 1500 → consideră că sunt sănătoși.
#Pentru acești useri, filtrează produse cu sugar < 5, fats < 5, health_score > medie.
import pandas as pd
import numpy as np
import random
from datetime import datetime, timedelta

# === Setări ===
SEED = 42
random.seed(SEED)
np.random.seed(SEED)

# === Încărcare fișiere ===
users_df = pd.read_csv("../dataset/Generated_Users.csv")
products_df = pd.read_csv("../dataset/Processed_Products.csv")
orders_df = pd.read_csv("../dataset/Generated_Client_Orders.csv")

# Asigură-te că toate ID-urile sunt string
products_df["id"] = products_df["id"].astype(str)
users_df["id"] = users_df["id"].astype(str)
orders_df["order_id"] = orders_df["order_id"].astype(str)
orders_df["user_id"] = orders_df["user_id"].astype(str)

# === Filtrare Cold Items ===
cold_item_ids = set(np.random.choice(products_df["id"], size=int(0.1 * len(products_df)), replace=False))
products_df = products_df[~products_df["id"].isin(cold_item_ids)]

# === Preferințe pe categorii ===
category_cols = [col for col in products_df.columns if col.startswith("category_")]
user_preferences = {}
for user_id in users_df["id"]:
    preferred = np.random.choice(category_cols, size=2, replace=False)
    user_preferences[user_id] = preferred.tolist()

# === Alergeni ===
allergen_cols = [col for col in products_df.columns if col.startswith("allergen_")]
sensitive_users = set(np.random.choice(users_df["id"], size=int(0.1 * len(users_df)), replace=False))
user_allergen_exclude = {uid: ["allergen_nuts", "allergen_gluten", "allergen_lactose"] for uid in sensitive_users}

# === Comenzi generate ===
reorder_users = set(np.random.choice(users_df["id"], size=int(0.2 * len(users_df)), replace=False))
previous_orders = {}
order_products = []

for _, row in orders_df.iterrows():
    user_id = row["user_id"]
    order_id = row["order_id"]
    user_score = users_df.loc[users_df["id"] == user_id, "score"].values[0]

    # Selectează produse preferate
    preferred_categories = user_preferences[user_id]
    preferred_products = products_df[products_df[preferred_categories].sum(axis=1) > 0]

    # === Filtru user sănătos ===
    if user_score > 1500:
        preferred_products = preferred_products[(preferred_products["sugar"] < 5) &
                                                (preferred_products["fats"] < 5) &
                                                (preferred_products["health_score"] > products_df["health_score"].mean())]

    # === Filtru alergeni ===
    if user_id in user_allergen_exclude:
        for allergen in user_allergen_exclude[user_id]:
            preferred_products = preferred_products[preferred_products[allergen] == 0]

    # === Diversitate ===
    additional = products_df.sample(n=2)
    candidates = pd.concat([preferred_products, additional]).drop_duplicates(subset="id")

    # === Recumpărare ===
    if user_id in previous_orders and random.random() < 0.2:
        reused_ids = previous_orders[user_id]
        reused_products = products_df[products_df["id"].isin(reused_ids)]
        candidates = pd.concat([candidates, reused_products])

    # === Eliminare duplicate ===
    candidates = candidates.drop_duplicates(subset="id")
    if candidates.empty:
        continue

    # === Selectare produse finale ===
    num_products = 1 if user_score < 500 else np.random.randint(2, 5)
    selected = candidates.sample(n=min(num_products, len(candidates)), replace=False)

    previous_orders.setdefault(user_id, []).extend(selected["id"].tolist())

    for _, prod in selected.iterrows():
        quantity = np.random.randint(2, 4) if prod["price"] < 10 else 1
        noisy_price = round(prod["price"] * np.random.uniform(0.9, 1.1), 2)

        order_products.append({
            "order_id": order_id,
            "product_id": prod["id"],
            "quantity": quantity,
            "price": noisy_price
        })

# === Salvare CSV ===
pd.DataFrame(order_products).to_csv("../dataset/Generated_Order_Products_Positive.csv", index=False)
print(f"Generated_Order_Products_Positive.csv salvat cu {len(order_products)} înregistrări.")
import pandas as pd
import numpy as np

np.random.seed(42)

# === Încarcă datele ===
users_df = pd.read_csv("../dataset/Generated_Users.csv")
products_df = pd.read_csv("../dataset/Processed_Products.csv")
orders_df = pd.read_csv("../dataset/Generated_Client_Orders.csv")
order_products_df = pd.read_csv("../dataset/Generated_Order_Products_Positive.csv")

# Convertim toate ID-urile în string
users_df["id"] = users_df["id"].astype(str)
products_df["id"] = products_df["id"].astype(str)
orders_df["order_id"] = orders_df["order_id"].astype(str)
orders_df["user_id"] = orders_df["user_id"].astype(str)
order_products_df["order_id"] = order_products_df["order_id"].astype(str)
order_products_df["product_id"] = order_products_df["product_id"].astype(str)

order_products_df = order_products_df.merge(orders_df[["order_id", "user_id"]], on="order_id")

interacted_pairs = set(zip(order_products_df["user_id"], order_products_df["product_id"]))

# === Preferințe pe categorii ===
category_cols = [col for col in products_df.columns if col.startswith("category_")]
product_sales = order_products_df.groupby("product_id")["quantity"].sum().reset_index(name="total_units_sold")

# Combină cu dataframe-ul de produse
products_df = products_df.merge(product_sales, how="left", left_on="id", right_on="product_id").fillna(0)

# Ordonează după total_units_sold
popular_products = products_df.sort_values("total_units_sold", ascending=False)["id"].tolist()[:100]
mean_health_score = products_df["health_score"].mean()

# === Generare wishlist ===
wishlist = []
wishlist_id = 1

for _, user in users_df.iterrows():
    user_id = user["id"]
    score = user["score"]
    wishlist_size = np.random.randint(3, 7)

    # Preferințe: 2 categorii preferate
    preferred_cats = np.random.choice(category_cols, size=2, replace=False)
    preferred = products_df[products_df[preferred_cats].sum(axis=1) > 0]["id"].tolist()

    # Sănătos => produse cu zahăr & grăsimi scăzute
    healthy = []
    if score > 1500:
        healthy = products_df[(products_df["sugar"] < 5) &
                              (products_df["fats"] < 5) &
                              (products_df["health_score"] > mean_health_score)]["id"].tolist()

    # Random pool (diversitate)
    random_pool = list(set(products_df["id"]) - set(preferred) - set(healthy))

    # Combinație între preferate, sănătoase, populare și random
    chosen = []
    chosen += list(np.random.choice(preferred, size=min(int(wishlist_size * 0.4), len(preferred)), replace=False))
    chosen += list(np.random.choice(healthy, size=min(int(wishlist_size * 0.3), len(healthy)), replace=False))
    chosen += list(np.random.choice(popular_products, size=1, replace=False))
    chosen += list(np.random.choice(random_pool, size=max(0, wishlist_size - len(chosen)), replace=False))

    added = 0
    for product_id in chosen:
        if (user_id, product_id) not in interacted_pairs:
            wishlist.append({
                "id": wishlist_id,
                "user_id": user_id,
                "product_id": product_id
            })
            wishlist_id += 1
            added += 1
            if added >= wishlist_size:
                break

# === Salvare ===
wishlist_df = pd.DataFrame(wishlist)
wishlist_df.to_csv("../dataset/Generated_Wishlists.csv", index=False)
print(f"Wishlist generat cu {len(wishlist_df)} înregistrări.")

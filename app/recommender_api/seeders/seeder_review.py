# Produsele populare primesc mai multe review-uri.
# Review-urile pozitive au descrieri și like-uri mai bune.
# Cold users & cold items NU apar în reviews.
# Rating-ul este distribuit, cu bias pozitiv.
# Se evită overfitting: review-urile nu sunt perfect corelate cu scorul.
import pandas as pd
import numpy as np
import random

np.random.seed(42)
random.seed(42)

# === Încarcă fișierele necesare ===
users_df = pd.read_csv("../dataset/Generated_Users.csv")
products_df = pd.read_csv("../dataset/Processed_Products.csv")
orders_df = pd.read_csv("../dataset/Generated_Client_Orders.csv")
order_products_df = pd.read_csv("../dataset/Generated_Order_Products_Positive.csv")

# Asigură conversia ID-urilor
users_df["id"] = users_df["id"].astype(str)
products_df["id"] = products_df["id"].astype(str)
orders_df["order_id"] = orders_df["order_id"].astype(str)
orders_df["user_id"] = orders_df["user_id"].astype(str)
order_products_df["order_id"] = order_products_df["order_id"].astype(str)
order_products_df["product_id"] = order_products_df["product_id"].astype(str)

# === Alocă user_id pentru order_products ===
order_products_df = order_products_df.merge(orders_df[["order_id", "user_id"]], on="order_id")

# === Calculează popularitatea produselor ===
popular_products = order_products_df.groupby("product_id")["quantity"].sum().sort_values(ascending=False)
popular_products = popular_products.to_dict()

# === Useri & Produse active ===
active_users = set(order_products_df["user_id"])
active_products = set(order_products_df["product_id"])

# === Set de perechi unice user-product pentru care au existat comenzi ===
interacted_pairs = set(zip(order_products_df["user_id"], order_products_df["product_id"]))

# === Șabloane pentru titluri și descrieri ===
good_titles = ["Excelent!", "Super!", "Recomand cu drag", "Îmi place mult", "Calitate bună"]
bad_titles = ["Slab", "Dezamăgitor", "Nu recomand", "Sub așteptări", "Nu mi-a plăcut"]
good_descriptions = [
    "Am fost foarte mulțumit. Calitate excelentă!",
    "Livrare rapidă și gust bun.",
    "Un produs pe care îl voi comanda din nou.",
    "A fost exact ce căutam. Mulțumesc!",
    "Preț corect pentru calitate."
]
bad_descriptions = [
    "A venit deteriorat. Nu voi mai comanda.",
    "Calitate slabă pentru prețul cerut.",
    "Nu a corespuns descrierii.",
    "Livrare întârziată și ambalaj prost.",
    "Miroase ciudat și nu mi-a plăcut."
]

# === Generare review-uri ===
reviews = []
review_id = 1

for (user_id, product_id) in interacted_pairs:
    if user_id not in active_users or product_id not in active_products:
        continue

    # Probabilitate bazată pe popularitate
    popularity = popular_products.get(product_id, 1)
    prob = min(0.1 + popularity / 1000, 0.6)
    if np.random.rand() > prob:
        continue

    # === Rating ===
    rating = np.random.choice([1, 2, 3, 4, 5], p=[0.05, 0.10, 0.15, 0.35, 0.35])

    # === Descriere și like-uri ===
    if rating >= 4:
        title = random.choice(good_titles)
        description = random.choice(good_descriptions)
        likes = np.random.randint(10, 50)
    elif rating == 3:
        title = "Acceptabil"
        description = "Produs ok, dar se putea și mai bine."
        likes = np.random.randint(0, 10)
    else:
        title = random.choice(bad_titles)
        description = random.choice(bad_descriptions)
        likes = np.random.randint(0, 4)

    reviews.append({
        "id": review_id,
        "user_id": user_id,
        "product_id": product_id,
        "title": title,
        "rating": rating,
        "description": description,
        "likes": likes
    })
    review_id += 1

# === Salvare ===
reviews_df = pd.DataFrame(reviews)
reviews_df.to_csv("../dataset/Generated_Reviews.csv", index=False)

print(f"Reviews generate: {len(reviews_df)} salvate în 'dataset/Generated_Reviews.csv'")

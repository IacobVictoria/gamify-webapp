# recommender/recommendations.py

import pandas as pd


def recommend_top_categories(ordered_products, product_data):
    """Recomandă produse din cele mai frecvente categorii cumpărate de utilizator."""
    # Obține ID-urile produselor comandate
    ordered_product_ids = ordered_products['product_id'].unique()
    
    # Găsește produsele din baza de date pentru comenzile utilizatorului
    ordered_product_data = product_data[product_data['id'].isin(ordered_product_ids)]
    
    # Grupați produsele după categorie și obțineți cele mai frecvente 2
    category_counts = ordered_product_data['category'].value_counts().nlargest(2)
    top_categories = category_counts.index.tolist()
    
    # Recomandă produse din categoriile cele mai frecvente
    recommendations = product_data[product_data['category'].isin(top_categories)]

    sample_size = min(len(recommendations), 5)
    return recommendations.sample(n = sample_size)


def recommend_similar_product(user_data, product_data):
    """Recomandă produse similare cu cel mai frecvent comandat."""
    most_ordered_product_id = user_data['product_id'].mode()[0]
    most_ordered_product = product_data[product_data['product_id'] == most_ordered_product_id]
    
    calories_tolerance = 50
    similar_products = product_data[
        (abs(product_data['calories'] - most_ordered_product['calories'].values[0]) <= calories_tolerance) &
        (product_data['ingredients'].str.contains('|'.join(most_ordered_product['ingredients'].values[0].split(','))))
    ]
    return similar_products.sample(5)

def recommend_high_protein(user_data, product_data):
    """Recomandă produse bogate în proteine în intervalul de preț al utilizatorului."""
    user_avg_price = user_data['price'].mean()
    price_range = (user_avg_price * 0.8, user_avg_price * 1.2)
    
    high_protein_products = product_data[
        (product_data['protein'] > 20) & 
        (product_data['price'] >= price_range[0]) & 
        (product_data['price'] <= price_range[1])
    ]
    return high_protein_products.sample(5)

def recommend_allergen_free(user_data, product_data, allergens):
    """Recomandă produse care nu conțin alergenii la care utilizatorul este sensibil."""
    allergen_free_products = product_data[~product_data['ingredients'].str.contains('|'.join(allergens))]
    return allergen_free_products.sample(5)

def recommend_based_on_price(user_data, product_data):
    """Recomandă produse în funcție de prețul mediu al utilizatorului."""
    user_avg_price = user_data['price'].mean()
    price_range = (user_avg_price * 0.8, user_avg_price * 1.2)
    
    products_in_price_range = product_data[
        (product_data['price'] >= price_range[0]) & 
        (product_data['price'] <= price_range[1])
    ]
    return products_in_price_range.sample(5)

def recommend_low_carb(user_data, product_data):
    """Recomandă produse cu conținut scăzut de carbohidrați."""
    low_carb_threshold = 20
    
    low_carb_products = product_data[
        (product_data['carbs'] <= low_carb_threshold) &
        (product_data['category'].isin(user_data['category'].unique()))
    ]
    return low_carb_products.sample(5)

def recommend_based_on_similar_users(user_data, all_users_data, product_data):
    """Recomandă produse pe baza utilizatorilor similari."""
    similar_users = all_users_data[
        all_users_data['category'].isin(user_data['category'].unique())
    ]
    
    popular_products = similar_users['product_id'].value_counts().nlargest(5).index.tolist()
    return product_data[product_data['product_id'].isin(popular_products)]

def recommend_healthier_swaps(user_data, product_data):
    """Recomandă produse mai sănătoase pentru cele frecvent cumpărate."""
    most_ordered_product_id = user_data['product_id'].mode()[0]
    most_ordered_product = product_data[product_data['product_id'] == most_ordered_product_id]
    
    healthier_products = product_data[
        (product_data['calories'] < most_ordered_product['calories'].values[0]) &
        (product_data['fat'] < most_ordered_product['fat'].values[0])
    ]
    return healthier_products.sample(5)

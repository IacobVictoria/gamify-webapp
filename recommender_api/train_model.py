# train_model.py
import pandas as pd
from sqlalchemy import create_engine
from keras import models
from keras import layers
from keras import utils
import numpy as np
from recommendations import recommend_top_categories

def get_database_connection():
    db_user = 'root'
    db_password = ''
    db_host = '127.0.0.1'
    db_name = 'gamify_webapp'

    db_url = f"mysql+mysqlconnector://{db_user}:{db_password}@{db_host}/{db_name}"
    engine = create_engine(db_url)
    return engine

# în care fiecare poziție va reprezenta un produs, cu 1 dacă utilizatorul a cumpărat produsul și 0 dacă nu l-a cumpărat.
#unique(), vei obține toate combinațiile de user_id și product_id care există în baza de date.
def train_model():
    engine = get_database_connection()
    
    # Colectarea datelor
   # Interogarea tuturor produselor disponibile pe site
    query_products = "SELECT id,category FROM products"
    products_data = pd.read_sql(query_products, engine)

# Interogarea comenzilor, grupată pe utilizatori
    query_orders = """
        SELECT co.user_id, op.product_id
        FROM order_products op
        JOIN client_orders co ON op.order_id = co.id
    """
    orders_data = pd.read_sql(query_orders, engine)


    # Crearea setului de date de antrenament
    # Lista tuturor produselor disponibile
    all_products = products_data['id'].unique()

    # Listă unică de utilizatori
    users = orders_data['user_id'].unique()

    # Creare set de date de antrenament
    X = []
    y = []

    for user in users:
        # Produsele cumpărate de utilizatorul curent
        user_orders = orders_data[orders_data['user_id'] == user]
        ordered_products = user_orders[['product_id']]

        # Recomandă produse din cele mai frecvente categorii
        recommendations = recommend_top_categories(ordered_products, products_data)

        # Vectorul de intrare pentru fiecare produs
        user_vector = [1 if product in ordered_products['product_id'].values else 0 for product in all_products]
        X.append(user_vector)

        # Vectorul de ieșire pentru produsele recomandate
        y_vector = [1 if product in recommendations['id'].values else 0 for product in all_products]
        y.append(y_vector)

    X = np.array(X)
    y = np.array(y)

    print(f"X shape: {X.shape}, y shape: {y.shape}")
    print("X sample:", X[:5])  # Arată primele 5 rânduri din X
    print("y sample:", y[:5])  # Arată primele 5 rânduri din y
    print("Unique products in y:", np.unique(y))

    # Definirea modelului
    model = models.Sequential()
    model.add(layers.Dense(128, activation='relu', input_dim=X.shape[1]))
    model.add(layers.Dropout(0.5))
    model.add(layers.Dense(64, activation='relu'))
    model.add(layers.Dropout(0.5))
    model.add(layers.Dense(y.shape[1], activation='sigmoid'))  # Sigmoid pentru probabilități

    # Compilarea modelului
    model.compile(optimizer='adam', loss='binary_crossentropy', metrics=['accuracy'])

    # Antrenarea modelului
    model.fit(X, y, epochs=10, batch_size=1)

    # Salvarea modelului
    model.save('models/recommender_model.keras')


from flask import Flask, jsonify, request
from recommendations import recommend_top_categories
from sqlalchemy import create_engine
import pandas as pd
import sys
import os
from keras import models
from train_model import train_model
import numpy as np

# Adaugă directorul părinte (gamify-webapp) la sys.path
sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))

app = Flask(__name__)

# Încărcați modelul antrenat (dacă a fost salvat anterior)
try:
    model = models.load_model('models/recommender_model.h5')
except Exception as e:
    print(f"Error loading model: {e}")
    model = None  # Asigură-te că modelul este None dacă nu se încarcă

# Funcția de conectare la baza de date
def get_database_connection():
    db_user = 'root'       # Înlocuiește cu utilizatorul bazei de date
    db_password = ''       # Parola este un șir gol dacă nu există parolă
    db_host = '127.0.0.1'  # Adresa serverului MySQL (de obicei localhost)
    db_name = 'gamify_webapp'  # Numele bazei de date

    db_url = f"mysql+mysqlconnector://{db_user}:{db_password}@{db_host}/{db_name}"
    engine = create_engine(db_url)
    return engine

def get_user_count():
    engine = get_database_connection()

    # Interogare pentru a obține numărul de utilizatori cu rolul 'user'
    query_users = """
    SELECT COUNT(DISTINCT u.id) as user_count
    FROM users u
    JOIN user_roles ur ON u.id = ur.user_id
    JOIN roles r ON ur.role_id = r.id
    WHERE r.name = 'user'
    """
    user_count = pd.read_sql(query_users, engine).iloc[0]['user_count']
    return int(user_count)

# Endpoint pentru a recomanda produse bazate pe utilizator
@app.route('/api/recommendations/<int:user_id>', methods=['GET'])
def recommend_products(user_id):
    engine = get_database_connection()

    # Extrage datele comenzilor utilizatorului
    query_orders = f"""
    SELECT op.product_id, p.category
    FROM order_products op
    JOIN client_orders co ON op.order_id = co.id
    JOIN products p ON op.product_id = p.id
    WHERE co.user_id = '{user_id}'
    """
    ordered_products = pd.read_sql(query_orders, engine)

    # Extrage datele produselor
    query_products = "SELECT * FROM products"
    product_data = pd.read_sql(query_products, engine)

    # Fă o recomandare
    recommendations_list = recommend_top_categories(ordered_products, product_data)

    # Convertește rezultatul în JSON
    return jsonify(recommendations_list.to_dict(orient='records'))

# def prepare_user_vector(user_id, ordered_products, product_data):
#     # Obține ID-urile produselor și pregătește vectorul de intrare pentru model
#     product_ids = product_data['id'].unique()
#     user_vector = [0] * len(product_ids)

#     for product_id in ordered_products['product_id'].values:
#         if product_id in product_ids:
#             index = np.where(product_ids == product_id)[0][0]
#             user_vector[index] = 1  # Setează la 1 dacă utilizatorul a cumpărat produsul

#     return np.array([user_vector])  # Returnează ca matrice 2D


# @app.route('/api/recommendations/<int:user_id>', methods=['GET'])
# def recommend_products(user_id):
#     engine = get_database_connection()

#     # Extrage datele comenzilor utilizatorului
#     query_orders = f"""
#     SELECT op.product_id, p.category
#     FROM order_products op
#     JOIN client_orders co ON op.order_id = co.id
#     JOIN products p ON op.product_id = p.id
#     WHERE co.user_id = '{user_id}'
#     """
#     ordered_products = pd.read_sql(query_orders, engine)

#     # Extrage datele produselor
#     query_products = "SELECT * FROM products"
#     product_data = pd.read_sql(query_products, engine)

#     # Fă recomandări folosind modelul antrenat
#     if model is None:
#         return jsonify({'error': 'Modelul nu a fost antrenat.'}), 500

#     # Prepară datele pentru predicție
#     user_vector = prepare_user_vector(user_id, ordered_products, product_data)
    
#     # Predictie
#     predictions = model.predict(user_vector)
    
#     # Selectarea top N produse
#     top_n = 5
#     recommended_product_ids = np.argsort(predictions[0])[-top_n:][::-1]
    
#     # Obține detaliile produselor recomandate
#     recommended_products = product_data[product_data['id'].isin(recommended_product_ids)]
    
#     return jsonify(recommended_products.to_dict(orient='records'))


# Endpoint pentru a obține numărul de utilizatori
@app.route('/api/user_count', methods=['GET'])
def user_count():
    count = get_user_count()
    return jsonify({'user_count': count})

if __name__ == '__main__':
    app.run(debug=True)



# # # Endpoint pentru a antrena modelul
# @app.route('/api/train', methods=['POST'])
# def train():
#     try:
#         train_model()  # Apelăm funcția de antrenare
#         return jsonify({'message': 'Model antrenat cu succes!'}), 200
#     except Exception as e:
#         print(f"Error during training: {e}")
#         return jsonify({'error': str(e)}), 500

























# from flask import Flask, jsonify, request
# from recommendations import recommend_top_categories
# from sqlalchemy import create_engine
# import pandas as pd
# import sys
# import os

# # Adaugă directorul părinte (gamify-webapp) la sys.path
# sys.path.append(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))

# app = Flask(__name__)

# # Funcția de conectare la baza de date
# def get_database_connection():
#     db_user = 'root'       # Înlocuiește cu utilizatorul bazei de date
#     db_password = ''               # Parola este un șir gol dacă nu există parolă
#     db_host = '127.0.0.1'          # Adresa serverului MySQL (de obicei localhost)
#     db_name = 'gamify_webapp'       # Numele bazei de date

#     db_url = f"mysql+mysqlconnector://{db_user}:{db_password}@{db_host}/{db_name}"
#     engine = create_engine(db_url)
#     return engine

# def get_user_count():
#     engine = get_database_connection()

#     # Interogare pentru a obține numărul de utilizatori cu rolul 'user'
#     query_users = """
#     SELECT COUNT(DISTINCT u.id) as user_count
#     FROM users u
#     JOIN user_roles ur ON u.id = ur.user_id
#     JOIN roles r ON ur.role_id = r.id
#     WHERE r.name = 'user'
#     """
#     user_count = pd.read_sql(query_users, engine).iloc[0]['user_count']
#     return user_count



# # Endpoint pentru a recomanda produse bazate pe utilizator
# @app.route('/api/recommendations/<int:user_id>', methods=['GET'])
# def recommend_products(user_id):
#     engine = get_database_connection()

#     # Extrage datele comenzilor utilizatorului
#     query_orders = f"""
# SELECT op.product_id, p.category
# FROM order_products op
# JOIN client_orders co ON op.order_id = co.id
# JOIN products p ON op.product_id = p.id
# WHERE co.user_id = '{user_id}'
# """
#     ordered_products = pd.read_sql(query_orders, engine)

#     # Extrage datele produselor
#     query_products = "SELECT * FROM products"
#     product_data = pd.read_sql(query_products, engine)

#     # Fă o recomandare
#     recommendations_list = recommend_top_categories(ordered_products, product_data)

#     # Convertește rezultatul în JSON
#     return jsonify(recommendations_list.to_dict(orient='records'))

# if __name__ == '__main__':
#     app.run(debug=True)

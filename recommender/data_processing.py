from sqlalchemy import create_engine
import pandas as pd

# Conectarea directă la baza de date fără variabile de mediu
def get_database_connection():
    db_user = 'root'       # Înlocuiește cu utilizatorul bazei de date
    db_password = '' # Înlocuiește cu parola bazei de date
    db_host = '127.0.0.1'          # Înlocuiește cu adresa serverului MySQL (de obicei localhost)
    db_name = 'gamify_webapp'       # Înlocuiește cu numele bazei de date

    # Creează un URL pentru conectarea la baza de date
    db_url = f"mysql+mysqlconnector://{db_user}:{db_password}@{db_host}/{db_name}"
    
    # Creează un engine de conectare
    engine = create_engine(db_url)
    return engine

# Restul funcțiilor rămân la fel
def get_user_data(user_id):
    engine = get_database_connection()
    
    # Exemplu de interogare pentru a obține datele utilizatorului
    query = f"SELECT * FROM orders WHERE user_id = {user_id}"
    
    # Obține datele într-un DataFrame Pandas
    user_data = pd.read_sql(query, engine)
    return user_data

def get_product_data():
    engine = get_database_connection()
    
    # Exemplu de interogare pentru a obține toate produsele
    query = "SELECT * FROM products"
    
    # Obține datele într-un DataFrame Pandas
    product_data = pd.read_sql(query, engine)
    return product_data

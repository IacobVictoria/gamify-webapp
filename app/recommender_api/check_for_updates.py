import pandas as pd
from sqlalchemy import create_engine, text
from datetime import datetime, timedelta

engine = create_engine("mysql+pymysql://root:@localhost/gamify_recommender")
three_months_ago = datetime.now() - timedelta(days=90)

def check_table_for_updates(table_name, date_columns):
    
    conditions = " OR ".join([f"{col} >= :date" for col in date_columns])
    query = text(f"SELECT COUNT(*) AS cnt FROM {table_name} WHERE {conditions}")
    with engine.connect() as conn:
        result = conn.execute(query, {"date": three_months_ago}).fetchone()
    return result["cnt"] > 0

def main():
    # Listează tabelele și coloanele relevante pentru data update/creare
    tables_to_check = {
        "users": ["created_at", "updated_at"],
        "products": ["created_at", "updated_at"],
        "client_orders": ["created_at", "updated_at"],
        "order_products": ["created_at", "updated_at"],
        "wishlists": ["created_at", "updated_at"],
        "reviews": ["created_at", "updated_at"]
    }

    updates_found = {}

    for table, date_cols in tables_to_check.items():
        try:
            has_updates = check_table_for_updates(table, date_cols)
            updates_found[table] = has_updates
        except Exception as e:
            print(f"Nu am putut verifica tabelul {table}: {e}")
            updates_found[table] = False

    print("Rezultat verificare date noi/actualizate în ultimele 3 luni:")
    for table, updated in updates_found.items():
        print(f" - {table}: {'Da' if updated else 'Nu'}")

    return updates_found

if __name__ == "__main__":
    updates = main()

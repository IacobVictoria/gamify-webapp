import pymysql
import json
import os
from datetime import datetime

# === Config DB ===
connection = pymysql.connect(
    host="localhost",
    user="root",
    password="",
    database="gamify_recommender",
    cursorclass=pymysql.cursors.DictCursor
)

# === Tabele de urmărit ===
tables_to_check = ["wishlists", "reviews", "client_orders", "users", "products"]

# === Fișier cache pentru ultimele modificări ===
STATE_FILE = "app/recommender_api/tmp/last_updates.json"

def fetch_latest_updates():
    latest = {}
    with connection.cursor() as cursor:
        for table in tables_to_check:
            cursor.execute(f"SELECT MAX(updated_at) as last FROM {table}")
            result = cursor.fetchone()
            latest[table] = result["last"].isoformat() if result["last"] else None
    return latest

def load_cached_updates():
    if not os.path.exists(STATE_FILE):
        return {}
    with open(STATE_FILE, "r") as f:
        return json.load(f)

def save_current_updates(updates):
    os.makedirs(os.path.dirname(STATE_FILE), exist_ok=True)
    with open(STATE_FILE, "w") as f:
        json.dump(updates, f)

def has_changed(new, old):
    for table in tables_to_check:
        if new.get(table) != old.get(table):
            return True
    return False

if __name__ == "__main__":
    new_updates = fetch_latest_updates()
    old_updates = load_cached_updates()

    if has_changed(new_updates, old_updates):
        print("1")  # Semnal că trebuie să rulezi pipeline-ul
        save_current_updates(new_updates)
    else:
        print("0")  # Nimic de făcut

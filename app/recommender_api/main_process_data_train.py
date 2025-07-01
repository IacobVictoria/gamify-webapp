import subprocess

def run_process_users_script():
    print("Rulez preprocesarea pentru users...")
    subprocess.run(["python", "process_users.py"], check=True)

def run_process_products_script():
    print("Rulez preprocesarea pentru products...")
    subprocess.run(["python", "process_products.py"], check=True)


def main_process(updates):
    if updates.get("users"):
        run_process_users_script()
    else:
        print("Nicio schimbare la users în ultimele 3 luni.")

    if updates.get("products"):
        run_process_products_script()
    else:
        print("Nicio schimbare la products în ultimele 3 luni.")

    interactions_tables = ["client_orders", "order_products", "wishlists", "reviews"]
    if any(updates.get(table, False) for table in interactions_tables):
        subprocess.run(["python", "process_all_data"], check=True)
    else:
        print("Nicio schimbare la client_orders, order_products, wishlists sau reviews în ultimele 3 luni.")
        
        
if __name__ == "__main__":
    from check_for_updates import main  
    updates = main()  
    main_process(updates)  

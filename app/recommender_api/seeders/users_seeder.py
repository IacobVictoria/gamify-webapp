import pandas as pd
import numpy as np
import random
from faker import Faker

fake = Faker()
np.random.seed(42)
random.seed(42)

NUM_USERS = 1000
genders = ["male", "female"]

users = []
for user_id in range(1, NUM_USERS + 1):
    gender = random.choice(genders)
    name = fake.name_male() if gender == "male" else fake.name_female()
    email = fake.unique.email()
    score = random.randint(0, 2000)

    users.append({
        "id": user_id,
        "name": name,
        "email": email,
        "gender": gender,
        "score": score
    })

users_df = pd.DataFrame(users)
users_df.to_csv("../dataset/Generated_Users.csv", index=False)
print(f"✅ {NUM_USERS} useri salvați în 'Generated_Users.csv'")

import warnings
warnings.filterwarnings("ignore")

import pandas as pd
from mlxtend.frequent_patterns import fpgrowth, association_rules
import mysql.connector

db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="foodfleet"
)

cursor = db.cursor()

orders = pd.read_sql(
    "SELECT order_code, food_item FROM orders WHERE status='Placed'",
    db
)

basket = orders.groupby(['order_code','food_item'])\
.size().unstack().fillna(0)

basket = basket.astype(bool)

frequent = fpgrowth(basket, min_support=0.01, use_colnames=True)

rules = association_rules(
    frequent,
    metric="confidence",
    min_threshold=0.1
)

users = pd.read_sql(
    "SELECT id, allergy FROM users",
    db
)

food_data = {

"Butter Chicken": "Contains Dairy",
"Paneer Tikka": "Contains Dairy",
"Biryani": "None",
"Masala Dosa": "Contains Gluten",
"Chole Bhature": "Contains Gluten",
"Palak Paneer": "Contains Dairy",
"Tandoori Chicken": "Contains Dairy",
"Rajma Rice": "None",
"Samosa": "Contains Gluten",
"Gulab Jamun": "Contains Dairy",

"Pizza": "Contains Gluten & Dairy",
"Pasta Alfredo": "Contains Gluten & Dairy",
"Lasagna": "Contains Gluten & Dairy",
"Bruschetta": "Contains Gluten",
"Tiramisu": "Contains Dairy",

"Fried Rice": "Contains Soy",
"Hakka Noodles": "Contains Gluten",
"Spring Rolls": "Contains Gluten",
"Dumplings": "Contains Gluten",
"Manchurian": "Contains Soy",

"Chocolate Cake": "Contains Gluten & Dairy",
"Ice Cream": "Contains Dairy",
"Donut": "Contains Gluten",
"Cupcake": "Contains Gluten & Dairy",
"Cheesecake": "Contains Gluten & Dairy",

"Chocolate Milkshake": "Contains Dairy",
"Strawberry Milkshake": "Contains Dairy",
"Vanilla Milkshake": "Contains Dairy",
"Oreo Shake": "Contains Gluten & Dairy",
"Banana Shake": "Contains Nuts",

"Cappuccino": "Contains Dairy",
"Latte": "Contains Dairy",
"Americano": "None",
"Mocha": "Contains Dairy",
"Cold Brew": "None"
}

cursor.execute("""
DELETE FROM recommendations 
WHERE confidence < 0.5
""")

for _, user in users.iterrows():

    user_allergy = str(user['allergy']).lower()

    for _, row in rules.iterrows():

        antecedent = list(row['antecedents'])[0]
        consequent = list(row['consequents'])[0]
        confidence = float(row['confidence'])

        if consequent not in food_data:
            continue

        food_allergy = food_data[consequent].lower()

        if user_allergy != "none" and user_allergy in food_allergy:
            continue

        cursor.execute("""
        INSERT INTO recommendations(food_item,recommended_item,confidence)
        SELECT %s,%s,%s
        WHERE NOT EXISTS (
        SELECT 1 FROM recommendations 
        WHERE food_item=%s AND recommended_item=%s
        )
        """, (antecedent, consequent, confidence, antecedent, consequent))

db.commit()

print("Recommendations generated (Allergy-based only)")
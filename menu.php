<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>FoodFleet - Menu</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>

body{
margin:0;
font-family:'Poppins',sans-serif;
background:linear-gradient(120deg,#ffecd2,#fcb69f);
display:flex;
justify-content:center;
align-items:center;
min-height:100vh;
}

.container{
width:950px;
background:white;
box-shadow:0 10px 40px rgba(0,0,0,0.15);
border-radius:12px;
overflow:hidden;
display:flex;
flex-direction:column;
}

.header{
padding:20px;
border-bottom:1px solid #eee;
text-align:center;
font-weight:600;
font-size:20px;
}

.content{
padding:30px;
flex:1;
}

input{
width:100%;
padding:12px;
border-radius:6px;
border:1px solid #ccc;
margin-top:10px;
}

.section-title{
margin-top:25px;
font-weight:600;
}

.button-group{
display:flex;
gap:10px;
margin-top:10px;
flex-wrap:wrap;
}

.category-btn, .filter-btn{
padding:8px 15px;
border:none;
border-radius:6px;
background:#ddd;
cursor:pointer;
transition:0.2s ease;
}

.category-btn:hover, .filter-btn:hover{
background:#3b82f6;
color:white;
}

.category-btn.active,
.filter-btn.active{
background:#3b82f6;
color:white;
}

.food-grid{
display:grid;
grid-template-columns:repeat(3,1fr);
gap:20px;
margin-top:20px;
}

.food-card{
background:#f9f9f9;
border-radius:10px;
overflow:hidden;
box-shadow:0 4px 10px rgba(0,0,0,0.05);
transition:0.2s ease;
cursor:pointer;
}

.food-card:hover{
transform:scale(1.05);
}

.food-card img{
width:100%;
height:180px;
object-fit:cover;
}

.food-info{
padding:10px;
text-align:center;
}

.food-info h4{
margin:0;
font-size:14px;
}

.food-info p{
margin:4px 0 0;
font-size:12px;
color:#666;
}

.bottom-nav{
display:flex;
justify-content:space-around;
padding:15px;
border-top:1px solid #eee;
background:#fafafa;
}

.bottom-nav div{
cursor:pointer;
font-weight:500;
}

</style>
</head>

<body>
    <?php include "navbar.php"; ?>

<div class="container">

<div class="header">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <div style="font-weight:600;">Food Fleet</div>
        <div style="display:flex; gap:20px; font-size:14px;">
            <a href="menu.php" style="text-decoration:none; color:black;">Home</a>
            <a href="cart.php" style="text-decoration:none; color:black;">Cart 🛒</a>
            <a href="profile.php" style="text-decoration:none; color:black;">Profile 👤</a>
            <a href="logout.php" style="text-decoration:none; color:red;">Logout</a>
        </div>
    </div>
</div>

<div class="content">

<p style="font-weight:500;">Hello, <?php echo $_SESSION['user_name']; ?>!</p>

<input type="text" id="searchBar" placeholder="Search for food..." onkeyup="renderFoods()">

<div class="section-title">Categories</div>
<div class="button-group">
<button class="category-btn active" onclick="setCategory(this,'All')">All</button>
<button class="category-btn" onclick="setCategory(this,'Indian')">Indian</button>
<button class="category-btn" onclick="setCategory(this,'Italian')">Italian</button>
<button class="category-btn" onclick="setCategory(this,'Chinese')">Chinese</button>
<button class="category-btn" onclick="setCategory(this,'Desserts')">Desserts</button>
<button class="category-btn" onclick="setCategory(this,'Milkshake')">Milkshake</button>
<button class="category-btn" onclick="setCategory(this,'Coffee')">Coffee</button>
</div>

<div class="section-title">Filters</div>
<div class="button-group">
<button class="filter-btn active" onclick="setFilter(this,'All')">All</button>
<button class="filter-btn" onclick="setFilter(this,'Veg')">Veg</button>
<button class="filter-btn" onclick="setFilter(this,'Non-Veg')">Non-Veg</button>
<button class="filter-btn" onclick="setFilter(this,'Vegan')">Vegan</button>
<button class="filter-btn" onclick="setFilter(this,'Gluten-Free')">Gluten-Free</button>
</div>

<div id="foodContainer" class="food-grid"></div>

</div>

<div class="bottom-nav">
<div onclick="window.location.href='menu.php'">Home</div>
<div onclick="alert('Cart page next')">Cart</div>
<div onclick="alert('Profile page next')">Profile</div>
</div>

</div>

<script>

const foods = [

/* ---------- INDIAN ---------- */

{ name:"Butter Chicken", category:"Indian", type:"Non-Veg", price:350, calories:600, ingredients:"Chicken, Butter, Cream, Tomato gravy", allergy:"Contains Dairy", img:"Images/butterChicken.jpg" },

{ name:"Paneer Tikka", category:"Indian", type:"Veg", price:250, calories:400, ingredients:"Paneer, Yogurt, Spices", allergy:"Contains Dairy", img:"Images/paneerTikka.jpg" },

{ name:"Biryani", category:"Indian", type:"Non-Veg", price:300, calories:550, ingredients:"Rice, Chicken, Spices", allergy:"None", img:"Images/biryani.jpg" },

{ name:"Masala Dosa", category:"Indian", type:"Veg", price:180, calories:320, ingredients:"Rice batter, Potato filling", allergy:"Contains Gluten", img:"Images/masalaDosa.jpg" },

{ name:"Chole Bhature", category:"Indian", type:"Veg", price:200, calories:480, ingredients:"Chickpeas, Flour bread", allergy:"Contains Gluten", img:"Images/choleBhature.jpg" },

{ name:"Palak Paneer", category:"Indian", type:"Veg", price:240, calories:420, ingredients:"Spinach, Paneer, Spices", allergy:"Contains Dairy", img:"Images/palakPaneer.jpg" },

{ name:"Tandoori Chicken", category:"Indian", type:"Non-Veg", price:320, calories:500, ingredients:"Chicken, Yogurt marinade", allergy:"Contains Dairy", img:"Images/tandooriChicken.jpg" },

{ name:"Rajma Rice", category:"Indian", type:"Veg", price:190, calories:350, ingredients:"Kidney beans, Rice", allergy:"None", img:"Images/rajmaRice.jpg" },

{ name:"Samosa", category:"Indian", type:"Veg", price:60, calories:150, ingredients:"Potato, Flour pastry", allergy:"Contains Gluten", img:"Images/samosa.jpg" },

{ name:"Gulab Jamun", category:"Indian", type:"Veg", price:120, calories:300, ingredients:"Milk solids, Sugar syrup", allergy:"Contains Dairy", img:"Images/gulabJamun.jpg" },

/* ---------- ITALIAN ---------- */

{ name:"Pizza", category:"Italian", type:"Veg", price:450, calories:700, ingredients:"Cheese, Tomato sauce, Dough", allergy:"Contains Gluten & Dairy", img:"Images/pizza.jpg" },

{ name:"Pasta Alfredo", category:"Italian", type:"Veg", price:380, calories:650, ingredients:"Pasta, Cream sauce", allergy:"Contains Gluten & Dairy", img:"Images/pastaAlfredo.jpg" },

{ name:"Lasagna", category:"Italian", type:"Non-Veg", price:420, calories:720, ingredients:"Pasta sheets, Meat, Cheese", allergy:"Contains Gluten & Dairy", img:"Images/lasagna.jpg" },

{ name:"Bruschetta", category:"Italian", type:"Veg", price:180, calories:250, ingredients:"Bread, Tomato, Olive oil", allergy:"Contains Gluten", img:"Images/bruschetta.jpg" },

{ name:"Tiramisu", category:"Desserts", type:"Veg", price:220, calories:500, ingredients:"Mascarpone, Coffee, Cocoa", allergy:"Contains Dairy & Egg", img:"Images/tiramisu.jpg" },

/* ---------- CHINESE ---------- */

{ name:"Fried Rice", category:"Chinese", type:"Vegan", price:220, calories:380, ingredients:"Rice, Vegetables, Soy sauce", allergy:"Contains Soy", img:"Images/friedRice.jpg" },

{ name:"Hakka Noodles", category:"Chinese", type:"Vegan", price:200, calories:360, ingredients:"Noodles, Veggies, Sauce", allergy:"Contains Gluten", img:"Images/hakkaNoodles.jpg" },

{ name:"Spring Rolls", category:"Chinese", type:"Veg", price:150, calories:250, ingredients:"Cabbage, Carrot, Wrapper", allergy:"Contains Gluten", img:"Images/springRolls.jpg" },

{ name:"Dumplings", category:"Chinese", type:"Non-Veg", price:260, calories:400, ingredients:"Flour wrapper, Chicken filling", allergy:"Contains Gluten", img:"Images/dumplings.jpg" },

{ name:"Manchurian", category:"Chinese", type:"Veg", price:210, calories:330, ingredients:"Vegetable balls, Sauce", allergy:"Contains Soy", img:"Images/manchurian.jpg" },

/* ---------- DESSERTS ---------- */

{ name:"Chocolate Cake", category:"Desserts", type:"Veg", price:200, calories:500, ingredients:"Flour, Cocoa, Butter", allergy:"Contains Gluten & Dairy", img:"Images/chocolateCake.jpg" },

{ name:"Ice Cream", category:"Desserts", type:"Veg", price:150, calories:280, ingredients:"Milk, Sugar", allergy:"Contains Dairy", img:"Images/iceCream.jpg" },

{ name:"Donut", category:"Desserts", type:"Veg", price:90, calories:260, ingredients:"Flour, Sugar glaze", allergy:"Contains Gluten", img:"Images/donut.jpg" },

{ name:"Cupcake", category:"Desserts", type:"Veg", price:110, calories:300, ingredients:"Flour, Butter, Sugar", allergy:"Contains Gluten & Dairy", img:"Images/cupcake.jpg" },

{ name:"Cheesecake", category:"Desserts", type:"Veg", price:230, calories:450, ingredients:"Cream cheese, Biscuit base", allergy:"Contains Gluten & Dairy", img:"Images/cheesecake.jpg" },

/* ---------- MILKSHAKES ---------- */

{ name:"Chocolate Milkshake", category:"Milkshake", type:"Veg", price:180, calories:420, ingredients:"Milk, Chocolate syrup", allergy:"Contains Dairy", img:"Images/chocolateMilkshake.jpg" },

{ name:"Strawberry Milkshake", category:"Milkshake", type:"Veg", price:170, calories:390, ingredients:"Milk, Strawberry syrup", allergy:"Contains Dairy", img:"Images/strawberryMilkshake.jpg" },

{ name:"Vanilla Milkshake", category:"Milkshake", type:"Veg", price:160, calories:370, ingredients:"Milk, Vanilla essence", allergy:"Contains Dairy", img:"Images/vanillaMilkshake.webp" },

{ name:"Oreo Shake", category:"Milkshake", type:"Veg", price:200, calories:480, ingredients:"Milk, Oreo cookies", allergy:"Contains Gluten & Dairy", img:"Images/oreoShake.jpg" },

{ name:"Banana Shake", category:"Milkshake", type:"Vegan", price:150, calories:300, ingredients:"Banana, Almond milk", allergy:"Contains Nuts", img:"Images/bananaShake.jpg" },

/* ---------- COFFEE ---------- */

{ name:"Cappuccino", category:"Coffee", type:"Veg", price:140, calories:120, ingredients:"Espresso, Milk foam", allergy:"Contains Dairy", img:"Images/cappuccino.jpg" },

{ name:"Latte", category:"Coffee", type:"Veg", price:150, calories:140, ingredients:"Espresso, Steamed milk", allergy:"Contains Dairy", img:"Images/latte.jpg" },

{ name:"Americano", category:"Coffee", type:"Vegan", price:120, calories:15, ingredients:"Espresso, Hot water", allergy:"None", img:"Images/americano.jpg" },

{ name:"Mocha", category:"Coffee", type:"Veg", price:170, calories:220, ingredients:"Espresso, Chocolate, Milk", allergy:"Contains Dairy", img:"Images/mocha.jpg" },

{ name:"Cold Brew", category:"Coffee", type:"Vegan", price:160, calories:10, ingredients:"Cold brewed coffee", allergy:"None", img:"Images/coldBrew.jpg" }

];

let currentCategory = "All";
let currentFilter = "";

function setCategory(btn, cat){
currentCategory = cat;
document.querySelectorAll(".category-btn").forEach(b=>b.classList.remove("active"));
btn.classList.add("active");
renderFoods();
}

function setFilter(btn, type){
document.querySelectorAll(".filter-btn").forEach(b=>b.classList.remove("active"));
btn.classList.add("active");

currentFilter = type === "All" ? "" : type;
renderFoods();
}

function renderFoods(){

const container = document.getElementById("foodContainer");
container.innerHTML = "";

let searchText = document.getElementById("searchBar").value.toLowerCase();

let filtered = foods.filter(food =>
(currentCategory==="All" || food.category===currentCategory) &&
(currentFilter==="" || food.type===currentFilter) &&
food.name.toLowerCase().includes(searchText)
);

filtered.forEach((food)=>{
container.innerHTML += `
<div class="food-card" onclick='openDetails(${JSON.stringify(food)})'>
<img src="${food.img}">
<div class="food-info">
<h4>${food.name}</h4>
<p>${food.category} • ${food.type}</p>
</div>
</div>
`;
});
}

function openDetails(food){
localStorage.setItem("selectedFood", JSON.stringify(food));
window.location.href = "fooddetails.php";
}

renderFoods();
function updateCartCount(){
fetch("get_cart_count.php")
.then(res=>res.text())
.then(count=>{
document.getElementById("cartCount").innerText = count;
});
}


updateCartCount();
</script>
</body>
</html>

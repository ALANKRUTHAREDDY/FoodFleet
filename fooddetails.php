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
<title>Food Details</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
body{
margin:0;
font-family:'Poppins',sans-serif;
background:linear-gradient(120deg,#ffecd2,#fcb69f);
display:flex;
justify-content:center;
align-items:flex-start;
min-height:100vh;
padding-top:90px;
}

.container{
width:500px;
background:white;
border-radius:12px;
box-shadow:0 10px 40px rgba(0,0,0,0.15);
padding:20px;
}

img{
width:100%;
height:250px;
object-fit:cover;
border-radius:8px;
}

.section{
margin-top:15px;
}

.quantity{
display:flex;
gap:10px;
align-items:center;
margin-top:10px;
}

.quantity button{
width:30px;
height:30px;
border:none;
border-radius:6px;
background:#ddd;
cursor:pointer;
}

textarea{
width:100%;
height:80px;
border-radius:6px;
border:1px solid #ccc;
padding:10px;
margin-top:5px;
}

.add-btn{
width:100%;
padding:12px;
margin-top:20px;
border:none;
border-radius:6px;
background:#3b82f6;
color:white;
font-weight:500;
cursor:pointer;
}
</style>
</head>

<body>
<?php include "navbar.php"; ?>
<div class="container">

<img id="foodImg">

<h3 id="foodName"></h3>
<p id="foodPrice"></p>

<div class="section">
<b>Ingredients:</b>
<p id="foodIngredients"></p>
</div>

<div class="section">
<b>Allergy Warnings:</b>
<p id="foodAllergy"></p>
</div>

<div class="section">
<b>Quantity:</b>
<div class="quantity">
<button onclick="changeQty(-1)">-</button>
<span id="qty">1</span>
<button onclick="changeQty(1)">+</button>
</div>
</div>

<div class="section">
<b>Cooking Instructions:</b>
<textarea placeholder="e.g. No onions"></textarea>
</div>

<button class="add-btn" onclick="addToCart()">Add to Cart</button>
<div id="cartMsg" style="display:none; margin-top:15px; padding:15px; border-radius:8px; text-align:center; font-size:14px;"></div>

</div>

<script>

let food=JSON.parse(localStorage.getItem("selectedFood"));

if(!food){
window.location.href="menu.php";
}

document.getElementById("foodImg").src=food.img;
document.getElementById("foodName").innerText=food.name;
document.getElementById("foodPrice").innerText=
"Price: ₹"+food.price+" | Calories: "+food.calories;
document.getElementById("foodIngredients").innerText=food.ingredients;
document.getElementById("foodAllergy").innerText=food.allergy;

let quantity=1;

function changeQty(val){
quantity+=val;
if(quantity<1) quantity=1;
document.getElementById("qty").innerText=quantity;
}

function addToCart(){

let instructions = document.querySelector("textarea").value;

let cartData = {
name: food.name,
price: food.price,
qty: quantity,
image: food.img,
instructions: instructions
};

fetch("add_to_cart.php",{
method:"POST",
headers:{"Content-Type":"application/json"},
body: JSON.stringify(cartData)
})
.then(res=>res.text())
.then(data=>{

let msgBox = document.getElementById("cartMsg");

if(data==="added"){

msgBox.style.display="block";
msgBox.style.background="#e6ffed";
msgBox.style.color="#1a7f37";

msgBox.innerHTML = `
<strong>✔ Added to cart!</strong><br><br>
<a href="cart.php" style="
background:#ff4e50;
color:white;
padding:8px 15px;
border-radius:6px;
text-decoration:none;">
View Cart 🛒
</a>
`;

updateCartCount();

}else{

msgBox.style.display="block";
msgBox.style.background="#ffe6e6";
msgBox.style.color="#a10000";
msgBox.innerHTML="Something went wrong.";

}
});
}
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

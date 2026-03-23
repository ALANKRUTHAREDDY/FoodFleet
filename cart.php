<?php
session_start();
include "config.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
/* USER ALLERGY */
$userData = mysqli_query($conn,"SELECT allergy FROM users WHERE id='$user_id'");
$userRow = mysqli_fetch_assoc($userData);
$userAllergy = strtolower($userRow['allergy']);

$addressQuery = mysqli_query($conn,"SELECT * FROM addresses WHERE user_id='$user_id'");
$result = mysqli_query($conn,"SELECT * FROM cart WHERE user_id='$user_id'");

$total = 0;
$restaurant_lat = 17.4435;
$restaurant_lng = 78.3772;
$firstFood = "";

$menuMap = [

"Butter Chicken"=>["price"=>350,"image"=>"Images/butterChicken.jpg"],
"Paneer Tikka"=>["price"=>250,"image"=>"Images/paneerTikka.jpg"],
"Biryani"=>["price"=>300,"image"=>"Images/biryani.jpg"],
"Masala Dosa"=>["price"=>180,"image"=>"Images/masalaDosa.jpg"],
"Chole Bhature"=>["price"=>200,"image"=>"Images/choleBhature.jpg"],
"Palak Paneer"=>["price"=>240,"image"=>"Images/palakPaneer.jpg"],
"Tandoori Chicken"=>["price"=>320,"image"=>"Images/tandooriChicken.jpg"],
"Rajma Rice"=>["price"=>190,"image"=>"Images/rajmaRice.jpg"],
"Samosa"=>["price"=>60,"image"=>"Images/samosa.jpg"],
"Gulab Jamun"=>["price"=>120,"image"=>"Images/gulabJamun.jpg"],

"Pizza"=>["price"=>450,"image"=>"Images/pizza.jpg"],
"Pasta Alfredo"=>["price"=>380,"image"=>"Images/pastaAlfredo.jpg"],
"Lasagna"=>["price"=>420,"image"=>"Images/lasagna.jpg"],
"Bruschetta"=>["price"=>180,"image"=>"Images/bruschetta.jpg"],
"Tiramisu"=>["price"=>220,"image"=>"Images/tiramisu.jpg"],

"Fried Rice"=>["price"=>220,"image"=>"Images/friedRice.jpg"],
"Hakka Noodles"=>["price"=>200,"image"=>"Images/hakkaNoodles.jpg"],
"Spring Rolls"=>["price"=>150,"image"=>"Images/springRolls.jpg"],
"Dumplings"=>["price"=>260,"image"=>"Images/dumplings.jpg"],
"Manchurian"=>["price"=>210,"image"=>"Images/manchurian.jpg"],

"Chocolate Cake"=>["price"=>200,"image"=>"Images/chocolateCake.jpg"],
"Ice Cream"=>["price"=>150,"image"=>"Images/iceCream.jpg"],
"Donut"=>["price"=>90,"image"=>"Images/donut.jpg"],
"Cupcake"=>["price"=>110,"image"=>"Images/cupcake.jpg"],
"Cheesecake"=>["price"=>230,"image"=>"Images/cheesecake.jpg"],

"Chocolate Milkshake"=>["price"=>180,"image"=>"Images/chocolateMilkshake.jpg"],
"Strawberry Milkshake"=>["price"=>170,"image"=>"Images/strawberryMilkshake.jpg"],
"Vanilla Milkshake"=>["price"=>160,"image"=>"Images/vanillaMilkshake.webp"],
"Oreo Shake"=>["price"=>200,"image"=>"Images/oreoShake.jpg"],
"Banana Shake"=>["price"=>150,"image"=>"Images/bananaShake.jpg"],

"Cappuccino"=>["price"=>140,"image"=>"Images/cappuccino.jpg"],
"Latte"=>["price"=>150,"image"=>"Images/latte.jpg"],
"Americano"=>["price"=>120,"image"=>"Images/americano.jpg"],
"Mocha"=>["price"=>170,"image"=>"Images/mocha.jpg"],
"Cold Brew"=>["price"=>160,"image"=>"Images/coldBrew.jpg"],
];
$foodAllergyMap = [

"Butter Chicken"=>"Contains Dairy",
"Paneer Tikka"=>"Contains Dairy",
"Biryani"=>"None",
"Masala Dosa"=>"Contains Gluten",
"Chole Bhature"=>"Contains Gluten",
"Palak Paneer"=>"Contains Dairy",
"Tandoori Chicken"=>"Contains Dairy",
"Rajma Rice"=>"None",
"Samosa"=>"Contains Gluten",
"Gulab Jamun"=>"Contains Dairy",

"Pizza"=>"Contains Gluten & Dairy",
"Pasta Alfredo"=>"Contains Gluten & Dairy",
"Lasagna"=>"Contains Gluten & Dairy",
"Bruschetta"=>"Contains Gluten",
"Tiramisu"=>"Contains Dairy",

"Fried Rice"=>"Contains Soy",
"Hakka Noodles"=>"Contains Gluten",
"Spring Rolls"=>"Contains Gluten",
"Dumplings"=>"Contains Gluten",
"Manchurian"=>"Contains Soy",

"Chocolate Cake"=>"Contains Gluten & Dairy",
"Ice Cream"=>"Contains Dairy",
"Donut"=>"Contains Gluten",
"Cupcake"=>"Contains Gluten & Dairy",
"Cheesecake"=>"Contains Gluten & Dairy",

"Chocolate Milkshake"=>"Contains Dairy",
"Strawberry Milkshake"=>"Contains Dairy",
"Vanilla Milkshake"=>"Contains Dairy",
"Oreo Shake"=>"Contains Gluten & Dairy",
"Banana Shake"=>"Contains Nuts",

"Cappuccino"=>"Contains Dairy",
"Latte"=>"Contains Dairy",
"Americano"=>"None",
"Mocha"=>"Contains Dairy",
"Cold Brew"=>"None"

];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>FoodFleet - Cart</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
body{margin:0;padding-top:80px;font-family:'Poppins',sans-serif;background:linear-gradient(120deg,#ffecd2,#fcb69f);}
.container{width:900px;margin:auto;background:white;padding:30px;border-radius:12px;box-shadow:0 10px 40px rgba(0,0,0,0.15);}
.cart-item{display:flex;gap:20px;align-items:center;padding:15px 0;border-bottom:1px solid #eee;}
.cart-item img{width:90px;height:90px;object-fit:cover;border-radius:10px;}
.details{flex:1;}
.name{font-weight:600;font-size:16px;}
.qty-controls{display:flex;align-items:center;gap:10px;}
.qty-btn{width:30px;height:30px;border:none;border-radius:6px;background:#ff4e50;color:white;font-size:16px;font-weight:bold;cursor:pointer;}
.total-section{margin-top:20px;text-align:right;font-size:18px;font-weight:600;}
.checkout-btn{width:100%;margin-top:20px;padding:12px;border:none;border-radius:6px;background:#ff4e50;color:white;font-weight:500;cursor:pointer;}

.recommend-container{display:flex;gap:15px;overflow-x:auto;padding:10px 0;}
.recommend-card{min-width:220px;border:1px solid #eee;border-radius:10px;padding:12px;background:#fafafa;}
.recommend-card img{width:100%;height:130px;object-fit:cover;border-radius:8px;}
.recommend-card button{margin-top:8px;width:100%;padding:6px;border:none;background:#ff4e50;color:white;border-radius:5px;cursor:pointer;}
</style>
</head>

<body>

<?php include "navbar.php"; ?>

<div class="container">
<h2>Your Cart</h2>

<?php 
if(mysqli_num_rows($result)==0){
echo "Your cart is empty 🛒";
}else{

while($row=mysqli_fetch_assoc($result)){

if($firstFood==""){
$firstFood=$row['food_name'];
}

$subtotal=$row['price']*$row['quantity'];
$total+=$subtotal;
?>

<div class="cart-item">
<img src="<?php echo $row['image']; ?>">
<div class="details">
<div class="name"><?php echo $row['food_name']; ?></div>
<div>₹<?php echo $row['price']; ?> each</div>
</div>

<div class="qty-controls">
<form method="POST" action="update_qty.php">
<input type="hidden" name="cart_id" value="<?php echo $row['id']; ?>">
<button type="submit" name="action" value="minus" class="qty-btn">−</button>
</form>

<span><?php echo $row['quantity']; ?></span>

<form method="POST" action="update_qty.php">
<input type="hidden" name="cart_id" value="<?php echo $row['id']; ?>">
<button type="submit" name="action" value="plus" class="qty-btn">+</button>
</form>
</div>

<div>₹<?php echo $subtotal; ?></div>
</div>

<?php } } ?>

<?php
if($firstFood!=""){

$recQuery=mysqli_query($conn,"
SELECT recommended_item, MAX(confidence) as confidence
FROM recommendations 
WHERE food_item='$firstFood'
GROUP BY recommended_item
ORDER BY confidence DESC
LIMIT 3");

if(mysqli_num_rows($recQuery)>0){

echo "<h3 style='margin-top:30px;'>Recommended for you</h3>";
echo "<div class='recommend-container'>";

while($rec=mysqli_fetch_assoc($recQuery)){

$recommended=$rec['recommended_item'];
/* ALLERGY FILTER */
$foodAllergy = strtolower($foodAllergyMap[$recommended] ?? "none");
if($userAllergy!="none" && strpos($foodAllergy,$userAllergy)!==false) continue;

if(!isset($menuMap[$recommended])) continue;

$data=$menuMap[$recommended];
?>

<div class="recommend-card">
<img src="<?php echo $data['image']; ?>">
<b><?php echo $recommended; ?></b><br>
₹<?php echo $data['price']; ?>

<form method="POST" action="add_to_cart.php">

<input type="hidden" name="food_name" value="<?php echo htmlspecialchars($recommended); ?>">
<input type="hidden" name="price" value="<?php echo $data['price'] ?? 0; ?>">
<input type="hidden" name="image" value="<?php echo $data['image'] ?? ''; ?>">
<input type="hidden" name="quantity" value="1">

<button type="submit">Add to Cart</button>

</form>
</div>

<?php } echo "</div>"; } } ?>

<?php if($total>0){ ?>

<h3>Select Delivery Address</h3>

<select id="addressSelect" onchange="calculateDistance()" style="padding:8px;width:100%;">
<option value="">-- Select Address --</option>

<?php while($addr=mysqli_fetch_assoc($addressQuery)){ ?>
<option value="<?php echo $addr['id']; ?>"
data-lat="<?php echo $addr['latitude']; ?>"
data-lng="<?php echo $addr['longitude']; ?>">
<?php echo $addr['label']." - ".$addr['address_line']; ?>
</option>
<?php } ?>

</select>

<div class="total-section">
Items Total: ₹<?php echo $total; ?>
</div>

<div id="deliveryCharge" style="text-align:right;margin-top:10px;"></div>
<div id="grandTotal" style="text-align:right;font-size:20px;font-weight:bold;"></div>

<input type="hidden" id="deliveryFeeInput">

<button class="checkout-btn" onclick="goToCheckout()">Proceed to Checkout</button>

<?php } ?>

</div>

<script>
let cartTotal=<?php echo $total;?>;
let restaurantLat=<?php echo $restaurant_lat;?>;
let restaurantLng=<?php echo $restaurant_lng;?>;

function calculateDistance(){
let s=document.getElementById("addressSelect");
if(!s||s.value==="") return;

let o=s.options[s.selectedIndex];
let uLat=parseFloat(o.dataset.lat);
let uLng=parseFloat(o.dataset.lng);

let R=6371;
let dLat=(uLat-restaurantLat)*Math.PI/180;
let dLng=(uLng-restaurantLng)*Math.PI/180;

let a=Math.sin(dLat/2)**2+
Math.cos(restaurantLat*Math.PI/180)*
Math.cos(uLat*Math.PI/180)*
Math.sin(dLng/2)**2;

let c=2*Math.atan2(Math.sqrt(a),Math.sqrt(1-a));
let dist=R*c;

let fee=Math.ceil(dist*5);

document.getElementById("deliveryCharge").innerHTML=
"Distance: "+dist.toFixed(2)+" km<br>Delivery Fee: ₹"+fee;

document.getElementById("grandTotal").innerHTML=
"Grand Total: ₹"+(cartTotal+fee);

document.getElementById("deliveryFeeInput").value=fee;
}

function goToCheckout(){
let s=document.getElementById("addressSelect");
if(!s||s.value===""){alert("Select address");return;}

let fee=document.getElementById("deliveryFeeInput").value;
let id=s.value;

fetch("store_checkout_data.php",{
method:"POST",
headers:{"Content-Type":"application/x-www-form-urlencoded"},
body:"address_id="+id+"&delivery_fee="+fee
}).then(()=>window.location.href="checkout.php");
}

window.onload = function(){
let select = document.getElementById("addressSelect");
if(select && select.value !== ""){
calculateDistance();
}
};
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

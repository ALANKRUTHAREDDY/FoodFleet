<?php
session_start();
include "config.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user
$userQuery = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($userQuery);

// Fetch addresses
$addressQuery = mysqli_query($conn,
"SELECT * FROM addresses WHERE user_id='$user_id'");

// Fetch orders
$orderQuery = mysqli_query($conn,
"SELECT * FROM orders WHERE user_id='$user_id' ORDER BY order_time DESC");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>FoodFleet - Profile</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
margin:0;
padding-top:80px;
font-family:'Poppins',sans-serif;
background:linear-gradient(120deg,#ffecd2,#fcb69f);
}

.container{
width:900px;
margin:auto;
background:white;
padding:30px;
border-radius:12px;
box-shadow:0 10px 40px rgba(0,0,0,0.15);
}

.section{
margin-bottom:40px;
}

.section h3{
margin-bottom:15px;
font-weight:600;
}

.info{
background:#f7f7f7;
padding:15px;
border-radius:8px;
margin-bottom:10px;
}

.order-card{
background:#fff;
padding:15px;
border-radius:8px;
box-shadow:0 5px 20px rgba(0,0,0,0.08);
margin-bottom:15px;
}

.cancel-btn, .delete-btn{
background:#e63946;
color:white;
border:none;
padding:6px 12px;
border-radius:6px;
cursor:pointer;
margin-top:5px;
}

.add-form input{
width:100%;
padding:8px;
margin-bottom:10px;
border-radius:6px;
border:1px solid #ccc;
}
</style>
</head>

<body>

<?php include "navbar.php"; ?>

<div class="container">

<!-- ================= PERSONAL DETAILS ================= -->
<div class="section">
<h3>👤 Personal Details</h3>

<div class="info">Name: <?php echo $user['name']; ?></div>
<div class="info">Email: <?php echo $user['email']; ?></div>
<div class="info">Age: <?php echo $user['age']; ?></div>
<div class="info">Allergy: <?php echo $user['allergy']; ?></div>
</div>
<a href="edit_profile.php">
<button class="cancel-btn" style="background:#457b9d;">
✏️ Modify Profile
</button>
</a>


<!-- ================= ADD ADDRESS ================= -->
<div class="section">
<h3>➕ Add New Address</h3>

<form method="POST" action="save_address.php" class="add-form">

<input type="text" name="label" placeholder="Label (Home / Hostel)" required>

<input type="text" id="address_line" name="address_line"
placeholder="Flat / Street / Area" required>

<input type="text" id="city" name="city"
placeholder="City" required>

<input type="text" id="pincode" name="pincode"
placeholder="Pincode" required>

<!-- Hidden coordinates -->
<input type="hidden" id="latitude" name="latitude">
<input type="hidden" id="longitude" name="longitude">

<button type="button" onclick="getLocation()">📍 Use Current Location</button>

<button type="submit" class="cancel-btn">Save Address</button>

</form>
</div>


<!-- ================= SAVED ADDRESSES ================= -->                                    
<div class="section">
<h3>📍 Saved Addresses</h3>

<?php
if(mysqli_num_rows($addressQuery) == 0){
echo "<div class='info'>No addresses added.</div>";
}else{
while($addr = mysqli_fetch_assoc($addressQuery)){
?>
<div class="info">
<strong><?php echo $addr['label']; ?></strong><br>
<?php echo $addr['address_line']; ?><br>
<?php echo $addr['city']; ?> - <?php echo $addr['pincode']; ?>

<form method="POST" action="delete_address.php">
<input type="hidden" name="address_id" value="<?php echo $addr['id']; ?>">
<button type="submit" class="delete-btn">Delete</button>
</form>

</div>
<?php
}
}
?>
</div>


<!-- ================= ORDER HISTORY ================= -->
<div class="section">
<h3>🧾 Order History</h3>

<?php
if(mysqli_num_rows($orderQuery) == 0){
echo "<p>No orders placed yet.</p>";
}else{
while($order = mysqli_fetch_assoc($orderQuery)){

date_default_timezone_set("Asia/Kolkata");

$order_time = strtotime($order['order_time']);
$current_time = time();

$time_diff = $current_time - $order_time;
$remaining_time = max(0, 120 - $time_diff);
?>

<div class="order-card">
<strong><?php echo $order['food_item']; ?></strong><br>
Quantity: <?php echo $order['quantity']; ?><br>
Total: ₹<?php echo $order['total_price']; ?><br>
Status: <b><?php echo $order['status']; ?></b><br>

<?php
if($order['status'] == "Placed"){
if($remaining_time > 0){
?>
        <form method="POST" action="cancel_order.php">
        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
       <button type="submit" class="cancel-btn">
Cancel Order (<?php echo $remaining_time; ?>s)
</button>
        </form>
<?php
    } else {
        echo "<p style='color:gray;'>Cancellation window closed</p>";
    }

}
?>
</div>

<?php
}
}
?>
</div>

</div>

<script>
function updateCartCount(){
fetch("get_cart_count.php")
.then(res=>res.text())
.then(cont=>{
document.getElementById("cartCount").innerText = count;
});
}
updateCartCount();
function getLocation() {

    if (!navigator.geolocation) {
        alert("Geolocation not supported by your browser.");
        return;
    }

    navigator.geolocation.getCurrentPosition(
        function(position) {

            let lat = position.coords.latitude;
            let lng = position.coords.longitude;

            // Save coordinates
            document.getElementById("latitude").value = lat;
            document.getElementById("longitude").value = lng;

            // Reverse geocode using FREE OpenStreetMap
            fetch("https://nominatim.openstreetmap.org/reverse?format=json&lat="
                + lat + "&lon=" + lng)
            .then(response => response.json())
            .then(data => {

                if (data.address) {

                    document.getElementById("address_line").value =
                        data.display_name || "";

                    document.getElementById("city").value =
                        data.address.city ||
                        data.address.town ||
                        data.address.village || "";

                    document.getElementById("pincode").value =
                        data.address.postcode || "";

                }
            })
            .catch(error => {
                alert("Location fetched but address not resolved.");
            });

        },
        function(error) {
            alert("Permission denied or location unavailable.");
        }
    );
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
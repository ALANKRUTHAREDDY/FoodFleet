<?php
session_start();
include "config.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

if(!isset($_SESSION['selected_address_id'])){
    header("Location: cart.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$address_id = $_SESSION['selected_address_id'];

// Fetch address
$addressQuery = mysqli_query($conn,
"SELECT * FROM addresses WHERE id='$address_id'");
$address = mysqli_fetch_assoc($addressQuery);

// Fetch cart
$cartQuery = mysqli_query($conn,
"SELECT * FROM cart WHERE user_id='$user_id'");

$total = 0;

// Get delivery fee from session
$delivery_fee = isset($_SESSION['delivery_fee']) ? $_SESSION['delivery_fee'] : 0;
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>FoodFleet - Checkout</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

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
margin-bottom:30px;
}

.section h3{
margin-bottom:15px;
}

.address-box, .summary-box{
background:#f7f7f7;
padding:15px;
border-radius:8px;
margin-bottom:15px;
}

.payment-option{
margin-bottom:10px;
}

.payment-details{
display:none;
margin-top:10px;
}

input[type="text"], select{
width:100%;
padding:8px;
margin-bottom:10px;
border-radius:6px;
border:1px solid #ccc;
}

.confirm-btn{
width:100%;
padding:12px;
border:none;
border-radius:6px;
background:#ff4e50;
color:white;
font-weight:600;
cursor:pointer;
}
</style>
</head>

<body>

<?php include "navbar.php"; ?>

<div class="container">

<h2>Checkout</h2>

<!-- ================= ADDRESS ================= -->
<div class="section">
<h3>Delivery Address</h3>

<div class="address-box">
<?php echo $address['address_line']; ?><br>
<?php echo $address['city']; ?> - <?php echo $address['pincode']; ?>
</div>
</div>

<!-- ================= ORDER SUMMARY ================= -->
<div class="section">
<h3>Order Summary</h3>

<div class="summary-box">
<?php
while($item = mysqli_fetch_assoc($cartQuery)){
    $subtotal = $item['price'] * $item['quantity'];
    $total += $subtotal;

    echo "<div>".$item['food_name']." (Qty: ".$item['quantity'].") - ₹".$subtotal."</div>";
}
?>
</div>
<h3>Items Total: ₹<?php echo $total; ?></h3>
<h3>Delivery Fee: ₹<?php echo $delivery_fee; ?></h3>

<?php $grand_total = $total + $delivery_fee; ?>

<h2>Grand Total: ₹<?php echo $grand_total; ?></h2>
</div>

<!-- ================= PAYMENT ================= -->
<div class="section">
<h3>Select Payment Method</h3>

<form method="POST" action="place_order.php">

<div class="payment-option">
<input type="radio" name="payment_method" value="COD" checked onclick="togglePayment()"> Cash on Delivery
</div>

<div class="payment-option">
<input type="radio" name="payment_method" value="UPI" onclick="togglePayment()"> UPI
<div class="payment-details" id="upiDetails">
<input type="text" name="upi_id" placeholder="Enter UPI ID">
</div>
</div>

<div class="payment-option">
<input type="radio" name="payment_method" value="CARD" onclick="togglePayment()"> Credit/Debit Card
<div class="payment-details" id="cardDetails">
<input type="text" name="card_number" placeholder="Card Number">
<input type="text" name="expiry" placeholder="MM/YY">
<input type="text" name="cvv" placeholder="CVV">
</div>
</div>
<input type="hidden" name="final_total" value="<?php echo $grand_total; ?>">

<button type="submit" class="confirm-btn">Confirm Order</button>

</form>
</div>

</div>

<script>
function togglePayment(){
let method = document.querySelector('input[name="payment_method"]:checked').value;

document.getElementById("upiDetails").style.display = 
(method === "UPI") ? "block" : "none";

document.getElementById("cardDetails").style.display = 
(method === "CARD") ? "block" : "none";
}
</script>

</body>
</html>
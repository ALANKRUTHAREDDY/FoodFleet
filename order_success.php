<?php
session_start();
include "config.php";

date_default_timezone_set("Asia/Kolkata");

$user_id = $_SESSION['user_id'];

$orderQuery = mysqli_query($conn,
"SELECT * FROM orders WHERE user_id='$user_id' 
ORDER BY order_time DESC LIMIT 1");

$order = mysqli_fetch_assoc($orderQuery);

$order_id = $order['id'];
$order_code = $order['order_code'];

$order_time = strtotime($order['order_time']);
$current_time = time();
$time_diff = $current_time - $order_time;
$remaining_time = max(0, 120 - $time_diff);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>FoodFleet - Order Success</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">

<style>
body{
margin:0;
font-family:'Poppins',sans-serif;
background:linear-gradient(120deg,#ffecd2,#fcb69f);
display:flex;
justify-content:center;
align-items:center;
height:100vh;
}

.card{
background:white;
padding:40px;
border-radius:15px;
box-shadow:0 15px 40px rgba(0,0,0,0.2);
text-align:center;
width:400px;
}

.success{
font-size:60px;
color:#4CAF50;
}

.order-id{
font-weight:600;
margin-top:10px;
}

.timer{
margin-top:20px;
font-size:18px;
color:#ff4e50;
}

.cancel-btn{
margin-top:20px;
padding:10px 20px;
border:none;
border-radius:8px;
background:#e63946;
color:white;
font-weight:600;
cursor:pointer;
}

.disabled{
background:gray;
cursor:not-allowed;
}
</style>
</head>

<body>

<div class="card">

<div class="success">✅</div>

<h2>Order Placed Successfully!</h2>

<div class="order-id">
Order ID: #<?php echo $order_code; ?>
</div>

<div class="timer">
You can cancel within <span id="countdown"><?php echo $remaining_time; ?></span> seconds
</div>

<?php
if($order && $order['status'] == "Placed" && $remaining_time > 0){
?>

<form method="POST" action="cancel_order.php">
<input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
<button type="submit" class="cancel-btn" id="cancelBtn">
Cancel Order
</button>
</form>

<?php
} else {
?>

<p style="color:gray; margin-top:15px;">❌ Cancellation not available</p>



<?php
}
?>
<a href="menu.php" style="text-decoration:none;">
<button class="cancel-btn" style="background:#2a9d8f; margin-top:10px;">
🍽️ Go to Menu
</button>
</a>
</div>

<script>
let timeLeft = <?php echo $remaining_time; ?>;
let countdownElement = document.getElementById("countdown");

let timer = setInterval(function(){

if(timeLeft <= 0){
clearInterval(timer);
countdownElement.innerHTML = "0";

let btn = document.getElementById("cancelBtn");
if(btn){
btn.disabled = true;
btn.classList.add("disabled");
btn.innerHTML = "Cancellation Closed";
}

}else{
countdownElement.innerHTML = timeLeft;
timeLeft--;
}

},1000);
</script>

</body>
</html>
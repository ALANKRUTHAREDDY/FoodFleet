<?php
session_start();
include "config.php";

$user_id = $_SESSION['user_id'];
$payment_method = $_POST['payment_method'];

$cartQuery = mysqli_query($conn,
"SELECT * FROM cart WHERE user_id='$user_id'");

/* ✅ Generate ONE order_code */
$order_id = "FF" . rand(10000,99999);

while($item = mysqli_fetch_assoc($cartQuery)){

$subtotal = $item['price'] * $item['quantity'];

mysqli_query($conn,"INSERT INTO orders
(user_id,food_item,quantity,total_price,status,payment_method,order_code)
VALUES
('$user_id','".$item['food_name']."','".$item['quantity']."',
'$subtotal','Placed','$payment_method','$order_id')");

}

/* clear cart */
mysqli_query($conn,"DELETE FROM cart WHERE user_id='$user_id'");

header("Location: order_success.php");
exit();
?>


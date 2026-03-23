<?php
session_start();
include "config.php";

$order_id = $_POST['order_id'];

$orderQuery = mysqli_query($conn,
"SELECT * FROM orders WHERE id='$order_id'");

$order = mysqli_fetch_assoc($orderQuery);

$order_time = strtotime($order['order_time']);
$current_time = time();
$time_diff = $current_time - $order_time;

if($time_diff < 120 && $order['status'] == "Placed"){
    mysqli_query($conn,
    "UPDATE orders SET status='Cancelled' WHERE id='$order_id'");
}

header("Location: profile.php");
exit();
?>
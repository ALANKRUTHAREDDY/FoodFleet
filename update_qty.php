<?php
session_start();
include "config.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$cart_id = $_POST['cart_id'];
$action = $_POST['action'];

// Get current quantity
$query = mysqli_query($conn,
"SELECT quantity FROM cart WHERE id='$cart_id' AND user_id='$user_id'");

$item = mysqli_fetch_assoc($query);

if(!$item){
    header("Location: cart.php");
    exit();
}

$current_qty = $item['quantity'];

if($action == "plus"){
    $new_qty = $current_qty + 1;

    mysqli_query($conn,
    "UPDATE cart SET quantity='$new_qty' WHERE id='$cart_id'");

}
elseif($action == "minus"){

    if($current_qty > 1){
        $new_qty = $current_qty - 1;

        mysqli_query($conn,
        "UPDATE cart SET quantity='$new_qty' WHERE id='$cart_id'");
    }
    else{
        // If quantity becomes 0 → remove item
        mysqli_query($conn,
        "DELETE FROM cart WHERE id='$cart_id'");
    }
}

header("Location: cart.php");
exit();
?>
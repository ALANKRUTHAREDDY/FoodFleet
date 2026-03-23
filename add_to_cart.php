<?php
session_start();
include "config.php";

if(!isset($_SESSION['user_id'])){
    echo "login_required";
    exit();
}

$user_id = $_SESSION['user_id'];

// Get JSON data
$data = json_decode(file_get_contents("php://input"), true);

if(!$data){
    echo "no_data";
    exit();
}

$name = mysqli_real_escape_string($conn, $data['name']);
$price = $data['price'];
$qty = $data['qty'];
$image = mysqli_real_escape_string($conn, $data['image']);
$instructions = mysqli_real_escape_string($conn, $data['instructions']);

// Check if item already exists in cart
$result = mysqli_query($conn,
"SELECT * FROM cart WHERE user_id='$user_id' AND food_name='$name'");

if(mysqli_num_rows($result) > 0){

    // If exists → increase quantity
    mysqli_query($conn,
    "UPDATE cart 
     SET quantity = quantity + $qty 
     WHERE user_id='$user_id' AND food_name='$name'");

}else{

    // If not exists → insert new row
    mysqli_query($conn,
    "INSERT INTO cart 
    (user_id, food_name, price, quantity, image, instructions)
    VALUES
    ('$user_id','$name','$price','$qty','$image','$instructions')");
}

echo "added";
?>
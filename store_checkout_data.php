<?php
session_start();

$_SESSION['selected_address_id'] = $_POST['address_id'];
$_SESSION['delivery_fee'] = $_POST['delivery_fee'];

echo "ok";
?>
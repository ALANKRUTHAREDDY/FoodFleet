<?php
session_start();
include "config.php";

$user_id = $_SESSION['user_id'];

$result = mysqli_query($conn,"SELECT SUM(quantity) as total FROM cart WHERE user_id='$user_id'");
$row = mysqli_fetch_assoc($result);

echo $row['total'] ? $row['total'] : 0;
?>
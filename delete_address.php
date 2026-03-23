<?php
session_start();
include "config.php";

$id = $_POST['address_id'];
mysqli_query($conn,"DELETE FROM addresses WHERE id='$id'");

header("Location: profile.php");
?>
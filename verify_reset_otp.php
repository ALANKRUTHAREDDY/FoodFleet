<?php
session_start();

if(isset($_POST['otp'])){

$enteredOtp = $_POST['otp'];

if(!isset($_SESSION['reset_otp'])){
    echo "session_expired";
    exit();
}

if($enteredOtp == $_SESSION['reset_otp']){
    echo "verified";
} else {
    echo "invalid";
}

}
?>
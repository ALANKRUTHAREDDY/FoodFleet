<?php
session_start();
include "config.php";

require 'PHPMailer.php';
require 'SMTP.php';
require 'Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if(isset($_POST['email'])){

$email = mysqli_real_escape_string($conn, $_POST['email']);

$result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

if(mysqli_num_rows($result) == 0){
    echo "not_found";
    exit();
}

$otp = rand(100000,999999);
$_SESSION['reset_otp'] = $otp;
$_SESSION['reset_email'] = $email;

$mail = new PHPMailer(true);

$mail->isSMTP();
$mail->Host       = 'smtp.gmail.com';
$mail->SMTPAuth   = true;
$mail->Username   = 'USERNAME@gmail.com';   // CHANGE THIS
$mail->Password   = 'APPPASSWORD';       // CHANGE THIS
$mail->SMTPSecure = 'tls';
$mail->Port       = 587;

$mail->setFrom('USERNAME@gmail.com', 'FoodFleet');
$mail->addAddress($email);

$mail->isHTML(true);
$mail->Subject = 'FoodFleet Password Reset OTP';
$mail->Body    = "Your OTP is <b>$otp</b>";

if($mail->send()){
    echo "sent";
} else {
    echo "Mail error";
}

}
?>
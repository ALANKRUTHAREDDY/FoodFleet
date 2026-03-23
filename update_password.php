<?php
session_start();
include "config.php";

if(!isset($_SESSION['reset_email'])){
    header("Location: forgot.php");
    exit();
}

if(isset($_POST['new_password']) && isset($_POST['confirm_password'])){

$new = $_POST['new_password'];
$confirm = $_POST['confirm_password'];

if($new !== $confirm){
    echo "Passwords do not match.";
    exit();
}

$email = $_SESSION['reset_email'];

$hashedPassword = password_hash($new, PASSWORD_DEFAULT);

mysqli_query($conn, "UPDATE users SET password='$hashedPassword' WHERE email='$email'");

// Clear session
unset($_SESSION['reset_otp']);
unset($_SESSION['reset_email']);

echo "<script>
alert('Password Updated Successfully');
window.location.href='login.php';
</script>";

}
?>
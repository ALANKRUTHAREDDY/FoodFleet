<?php
session_start();

if(!isset($_SESSION['reset_email'])){
    header("Location: forgot.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>FoodFleet - Reset Password</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
body{
margin:0;
font-family:'Poppins',sans-serif;
background:linear-gradient(120deg,#ffecd2,#fcb69f);
display:flex;
justify-content:center;
align-items:center;
min-height:100vh;
}

.container{
width:850px;
background:white;
box-shadow:0 10px 40px rgba(0,0,0,0.15);
border-radius:12px;
display:flex;
overflow:hidden;
}

.left{
width:40%;
background:linear-gradient(135deg,#ff4e50,#f9d423);
color:white;
padding:40px;
display:flex;
flex-direction:column;
justify-content:center;
}

.right{
width:60%;
padding:40px;
}

input{
width:100%;
padding:12px;
margin-top:15px;
border-radius:6px;
border:1px solid #ccc;
}

button{
width:100%;
padding:12px;
margin-top:20px;
border:none;
border-radius:6px;
background:#ff4e50;
color:white;
cursor:pointer;
font-weight:500;
}

.message{
margin-top:15px;
font-size:14px;
color:red;
}
</style>
</head>

<body>

<div class="container">

<div class="left">
<h1>FoodFleet</h1>
<p>Create a new password</p>
</div>

<div class="right">

<h2>Reset Password</h2>

<form method="POST" action="update_password.php">

<input type="password" name="new_password" placeholder="New Password" required>

<input type="password" name="confirm_password" placeholder="Confirm Password" required>

<button type="submit">Update Password</button>

</form>

</div>
</div>

</body>
</html>
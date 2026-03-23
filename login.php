<?php
include "config.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if (mysqli_num_rows($result) > 0) {

        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            header("Location: menu.php");
            exit();

        } else {
            $error = "Invalid Password";
        }

    } else {
        $error = "Email not registered";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>FoodFleet - Login</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
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
width:800px;
background:white;
border-radius:12px;
box-shadow:0 10px 40px rgba(0,0,0,0.15);
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
margin-top:10px;
border-radius:6px;
border:1px solid #ccc;
}
button{
width:100%;
padding:12px;
margin-top:15px;
border:none;
border-radius:6px;
background:#ff4e50;
color:white;
cursor:pointer;
}
button:hover{opacity:0.9;}
.error{color:red;margin-top:10px;}
.link{margin-top:15px;}
a{text-decoration:none;}
</style>
</head>

<body>

<div class="container">
<div class="left">
<h1>FoodFleet</h1>
<p>Cravings Don’t Wait 🍕</p>
</div>

<div class="right">
<h2>Login</h2>

<?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>

<form method="POST">
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>
<button type="submit">Login</button>
</form>

<div class="link">
<a href="forgot.php">Forgot Password?</a>
</div>

<div class="link">
Don't have an account? <a href="register.php">Register</a>
</div>

</div>
</div>

</body>
</html>
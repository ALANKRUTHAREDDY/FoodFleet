<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $age = $_POST['age'];
    $allergy = $_POST['allergy'];
    $nutrition = $_POST['nutrition'];
    $password = $_POST['password'];

    $hashed = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO users 
              (name, phone, email, age, allergy, nutrition, password)
              VALUES 
              ('$name', '$phone', '$email', '$age', '$allergy', '$nutrition', '$hashed')";

    if (mysqli_query($conn, $query)) {
        header("Location: login.php");
        exit();
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>FoodFleet - Register</title>
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
width:900px;
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
input,select{
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
</style>
</head>

<body>

<div class="container">
<div class="left">
<h1>FoodFleet</h1>
<p>Cooking is love made visible 🍽️</p>
</div>

<div class="right">
<h2>Create Account</h2>

<?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>

<form method="POST">

<input type="text" name="name" placeholder="Full Name" required>
<input type="text" name="phone" placeholder="Phone Number" required>
<input type="email" name="email" placeholder="Email" required>
<input type="number" name="age" placeholder="Age" required>

<input type="password" name="password" placeholder="Password" required>

<label>Allergy</label>
<select name="allergy" required>
<option value="None">None</option>
<option value="Gluten">Gluten</option>
<option value="Dairy">Dairy</option>
<option value="Soy">Soy</option>
<option value="Nuts">Nuts</option>
<option value="Egg">Egg</option>
</select>


<button type="submit">Register</button>

</form>

<p style="margin-top:15px;">
Already have an account? <a href="login.php">Login</a>
</p>

</div>
</div>

</body>
</html>
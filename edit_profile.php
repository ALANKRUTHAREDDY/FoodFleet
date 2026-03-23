<?php
session_start();
include "config.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

date_default_timezone_set("Asia/Kolkata");

$user_id = $_SESSION['user_id'];

// Fetch current user data
$result = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($result);

// Update profile
if(isset($_POST['update'])){

$name = mysqli_real_escape_string($conn, $_POST['name']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$age = mysqli_real_escape_string($conn, $_POST['age']);
$allergy = mysqli_real_escape_string($conn, $_POST['allergy']);


mysqli_query($conn,
"UPDATE users SET 
name='$name',
email='$email',
age='$age',
allergy='$allergy'
WHERE id='$user_id'");

// Redirect back to profile
header("Location: profile.php?updated=1");
exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Edit Profile - FoodFleet</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">

<style>
body{
margin:0;
font-family:'Poppins',sans-serif;
background:linear-gradient(120deg,#ffecd2,#fcb69f);
display:flex;
justify-content:center;
align-items:center;
height:100vh;
}

.container{
background:white;
padding:30px;
border-radius:12px;
box-shadow:0 15px 40px rgba(0,0,0,0.2);
width:420px;
}

h2{
text-align:center;
margin-bottom:20px;
}

input, select{
width:100%;
padding:10px;
margin-bottom:12px;
border-radius:6px;
border:1px solid #ccc;
font-size:14px;
}

button{
width:100%;
padding:10px;
border:none;
border-radius:6px;
background:#e63946;
color:white;
font-size:16px;
cursor:pointer;
}

.back-btn{
margin-top:10px;
background:#2a9d8f;
}
</style>
</head>

<body>

<div class="container">

<h2>✏️ Edit Profile</h2>

<form method="POST">

<input type="text" name="name" 
value="<?php echo $user['name']; ?>" required>

<input type="email" name="email" 
value="<?php echo $user['email']; ?>" required>

<input type="number" name="age" 
value="<?php echo $user['age']; ?>" required>

<label>Allergy</label>
<select name="allergy" required>

<option value="None" <?php if($user['allergy']=="None") echo "selected"; ?>>None</option>
<option value="Gluten" <?php if($user['allergy']=="Gluten") echo "selected"; ?>>Gluten</option>
<option value="Dairy" <?php if($user['allergy']=="Dairy") echo "selected"; ?>>Dairy</option>
<option value="Soy" <?php if($user['allergy']=="Soy") echo "selected"; ?>>Soy</option>
<option value="Nuts" <?php if($user['allergy']=="Nuts") echo "selected"; ?>>Nuts</option>
<option value="Egg" <?php if($user['allergy']=="Egg") echo "selected"; ?>>Egg</option>

</select>



<button type="submit" name="update">Update Profile</button>

</form>

<a href="profile.php">
<button class="back-btn">⬅ Back to Profile</button>
</a>

</div>

</body>
</html>
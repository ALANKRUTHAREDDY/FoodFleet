<!DOCTYPE html>
<html>
<head>
<title>FoodFleet - Home</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
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
border-radius:12px;
box-shadow:0 10px 40px rgba(0,0,0,0.15);
display:flex;
overflow:hidden;
}

.left{
width:45%;
background:linear-gradient(135deg,#ff4e50,#f9d423);
color:white;
padding:50px;
display:flex;
flex-direction:column;
justify-content:center;
}

.left h1{
font-size:32px;
margin-bottom:15px;
}

.left p{
font-size:16px;
opacity:0.9;
}

.right{
width:55%;
padding:50px;
display:flex;
flex-direction:column;
justify-content:center;
}

.right h2{
margin-bottom:20px;
}

button{
width:100%;
padding:12px;
margin-top:15px;
border:none;
border-radius:6px;
font-size:15px;
cursor:pointer;
transition:0.3s ease;
}

.primary{
background:#ff4e50;
color:white;
}

.secondary{
background:white;
color:#ff4e50;
border:2px solid #ff4e50;
}

.primary:hover{opacity:0.9;}
.secondary:hover{
background:#ff4e50;
color:white;
}
</style>
</head>

<body>

<div class="container">

<div class="left">
<h1>FoodFleet</h1>
<p>Cravings Don’t Wait. Neither Do We. 🍔🍕</p>
</div>

<div class="right">
<h2>Welcome</h2>

<a href="login.php">
<button class="primary">Login</button>
</a>

<a href="register.php">
<button class="secondary">Register</button>
</a>

</div>

</div>

</body>
</html>
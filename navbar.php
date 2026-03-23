<style>
.navbar{
position:fixed;
top:0;
left:0;
right:0;              /* IMPORTANT */
height:70px;
background:white;
display:flex;
justify-content:space-between;
align-items:center;
padding:0 40px;
box-shadow:0 4px 10px rgba(0,0,0,0.1);
z-index:1000;
font-family:'Poppins',sans-serif;
box-sizing:border-box;
}

.logo{
font-weight:600;
font-size:24px;
}

.nav-links{
display:flex;
gap:30px;
align-items:center;
}

.nav-links a{
text-decoration:none;
color:#333;
font-weight:500;
font-size:14px;
white-space:nowrap;
}

.nav-links a:hover{
color:lightpink;
}

.logout{
color:red;
}
</style>

<div class="navbar">
<div class="logo">FoodFleet🍕</div>

<div class="nav-links">
<a href="menu.php">Home</a>
<a href="cart.php">
Cart 🛒 
<span id="cartCount" style="
background:#ff4e50;
color:white;
padding:2px 6px;
border-radius:50%;
font-size:12px;
margin-left:4px;">
0
</span>
</a>
<a href="profile.php">Profile 👤</a>
<a href="logout.php" class="logout">Logout</a>
</div>
</div>
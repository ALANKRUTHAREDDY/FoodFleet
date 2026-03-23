<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>FoodFleet - Forgot Password</title>
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

h2{
margin-bottom:20px;
}

input[type="email"]{
width:100%;
padding:12px;
margin-top:15px;
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
font-weight:500;
}

button:hover{
opacity:0.9;
}

.otp{
display:flex;
gap:12px;
justify-content:center;
margin-top:20px;
}

.otp input{
width:50px;
height:55px;
text-align:center;
font-size:22px;
border-radius:8px;
border:1px solid #ccc;
transition:0.2s;
}

.otp input:focus{
outline:none;
border:2px solid #ff4e50;
box-shadow:0 0 6px rgba(255,78,80,0.4);
}

.step{
margin-top:15px;
color:#666;
font-size:14px;
}

.message{
margin-top:15px;
font-size:14px;
}

.success{
color:green;
}

.error{
color:red;
}

.back{
margin-top:20px;
font-size:13px;
}

.back a{
color:#ff4e50;
text-decoration:none;
}

</style>
</head>

<body>

<div class="container">

<div class="left">
<h1>FoodFleet</h1>
<p>Secure Account Recovery</p>
</div>

<div class="right">

<h2>Password Recovery</h2>

<label>
<input type="radio" name="method" value="email">
Recover via Email
</label><br>

<label>
<input type="radio" name="method" value="phone" disabled>
Recover via Phone (Coming Soon)
</label>

<div id="emailSection" style="display:none;">
<input type="email" id="email" placeholder="Enter your registered email">
<button onclick="sendOTP()">Send OTP</button>
</div>

<div id="otpSection" style="display:none;">
<div class="step">Enter 6-digit OTP sent to your email</div>

<div class="otp">
<input maxlength="1" type="text" inputmode="numeric">
<input maxlength="1" type="text" inputmode="numeric">
<input maxlength="1" type="text" inputmode="numeric">
<input maxlength="1" type="text" inputmode="numeric">
<input maxlength="1" type="text" inputmode="numeric">
<input maxlength="1" type="text" inputmode="numeric">
</div>

<button onclick="verifyOTP()">Verify OTP</button>
</div>

<div id="msg" class="message"></div>

<div class="back">
<a href="login.php">← Back to Login</a>
</div>

</div>
</div>

<script>

const msg = document.getElementById("msg");
const emailSection = document.getElementById("emailSection");
const otpSection = document.getElementById("otpSection");

document.querySelectorAll('input[name="method"]').forEach(radio=>{
radio.addEventListener('change',()=>{
if(radio.value==="email"){
emailSection.style.display="block";
otpSection.style.display="none";
msg.innerText="";
}
});
});
function sendOTP(){

let email=document.getElementById("email").value.trim();

if(email===""){
msg.innerText="Please enter email";
msg.className="message error";
return;
}

fetch("send_reset_otp.php",{
method:"POST",
headers:{"Content-Type":"application/x-www-form-urlencoded"},
body:"email="+email
})
.then(res=>res.text())
.then(data=>{

if(data==="sent"){
msg.innerText="OTP sent to "+email;
msg.className="message success";
otpSection.style.display="block";
}
else if(data==="not_found"){
msg.innerText="Email not registered";
msg.className="message error";
}
else{
msg.innerText=data;   // show actual backend error
msg.className="message error";
}

})
.catch(error=>{
msg.innerText="Network error";
msg.className="message error";
});

}
const otpInputs = document.querySelectorAll(".otp input");
function verifyOTP(){

let otp="";
otpInputs.forEach(input=> otp+=input.value);

if(otp.length!==6){
msg.innerText="Enter complete 6-digit OTP";
msg.className="message error";
return;
}

fetch("verify_reset_otp.php",{
method:"POST",
headers:{"Content-Type":"application/x-www-form-urlencoded"},
body:"otp="+otp
})
.then(res=>res.text())
.then(data=>{

if(data==="verified"){
msg.innerText="OTP Verified ✔";
msg.className="message success";
window.location.href="reset_password.php";
}
else if(data==="invalid"){
msg.innerText="Invalid OTP";
msg.className="message error";
}
else if(data==="session_expired"){
msg.innerText="Session expired. Try again.";
msg.className="message error";
}
else{
msg.innerText=data;
msg.className="message error";
}

});
}

</script>

</body>
</html>
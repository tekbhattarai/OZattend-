
<?php  
session_start();// Starting Session
//if session exit, user nither need to signin nor need to signup
if(isset($_SESSION['login_id'])){
  if (isset($_SESSION['pageStore'])) {
      $pageStore = $_SESSION['pageStore'];
header("location: $pageStore"); // Redirecting To Profile Page
    }
}
?>
<!DOCTYPE html>
<html>
<head>
	<style>
body {font-family: Arial, Helvetica, sans-serif;}
form {display:;}
/* Full-width input fields */
input[type=email], input[type=password], input[type=text] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

/* Set a style for all buttons */
button {
  background-color: green;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: auto;
}

button:hover {
  opacity: 0.8;
}
p {
	text-align: center;
	color:blue;
	 font-size: 30px;
	text-shadow: 2px 2px 4px #000000;
}

/* Extra styles for the cancel button */
h1 {text-align: center;}
/* Center the image and position the close button */
.imgcontainer {
  text-align: center;
  margin: 24px 0 12px 0;
  position: relative;
}

img.avatar {
  width: 20%;
  border-radius: 50%;
}

.container {
  padding: 16px;
}

span.psw {
  float: right;
  padding-top: 16px;
}

/* Add Zoom Animation */
.animate {
  -webkit-animation: animatezoom 1s;
  animation: animatezoom 1s
}

@-webkit-keyframes animatezoom {
  from {-webkit-transform: scale(0)} 
  to {-webkit-transform: scale(1)}
}
  
@keyframes animatezoom {
  from {transform: scale(0)} 
  to {transform: scale(1)}
}

/* Change styles for span and cancel button on extra small screens */
@media screen and (max-width: 300px) {
  span.psw {
     display: block;
     float: none;
  }
  .cancelbtn {
     width: 100%;
  }
}
</style>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Register</title>
	<link rel="stylesheet" href="auth.css">
</head>
<body>
		<img src = "background.jpg" alt="HTML5 Icon" style="width: 140px;height: 128px;">
 <div class="rlform">
  <div class="rlform rlform-wrapper">
   <div class="rlform-box">
	<div class="rlform-box-inner">
	 <form action="reg_val.php" method="post" oninput='validatePassword()'>
	  <div class="imgcontainer">
     
      <img src="attend.PNG" alt="Avatar" class="avatar">
    </div>
	<div class="rlform-group">
	<label>First Name</label>
	<input type="text" name="FirstName" class= "rlform-input" required>
	</div>
     <div class="rlform-group">
	  <label>Last Name</label>
	  <input type="text" name="LastName" class="rlform-input" required>
	 </div>
		
	 <div class="rlform-group">					
	  <label>Email</label>
	  <input type="email" name="email" id="email" class="rlform-input" required>
	 </div>
		
	 <div class="rlform-group">					
	  <label>Password</label>
	  <input type="password" name="newPassword" id="newPass" class="rlform-input" required>
     </div>

     <div class="rlform-group">
	  <label>Confirm password</label>
	  <input type="password" name="conformpassword" id="conformPass" class="rlform-input" required>
     </div>

	  <button class="rlform-btn" name="signUp">Sign Up
	  </button>

	  <div class="text-foot">
	   Already have an account? <a href="login.php">Login</a>
	  </div>
	 </form>
	</div>
   </div>
  </div>
 </div>

<script>
		function validatePassword(){
  if(newPass.value != conformPass.value) {
    conformPass.setCustomValidity('Passwords do not match.');
  } else {
    conformPass.setCustomValidity('');
  }

if(email.value.includes("@oswego.edu")){
        email.setCustomValidity('');
        } else {
    email.setCustomValidity('Must be an oswego.edu email address');
  }
}
</script>
</body>
</html>










<?php
session_start(); // Starting Session
$_SESSION['admin'] = "ulises.mejias@oswego.edu";

//if session exit, user nither need to signin nor need to signup
if(isset($_SESSION['login_id'])){
  if (isset($_SESSION['pageStore'])) {
      $pageStore = $_SESSION['pageStore'];
header("location: $pageStore"); // Redirecting To Profile Page
    }
}
//Login progess start, if user press the signin button
if (isset($_POST['signIn'])) {
if (empty($_POST['email']) || empty($_POST['password'])) {
echo "Username & Password should not be empty";
}
else
{
$email = $_POST['email'];
$password = $_POST['password'];

// Make a connection with MySQL server.
include('config.php');
$sQuery = "SELECT participantID, password from Participant where email=? LIMIT 1";
// To protect MySQL injection for Security purpose
$stmt = $conn->prepare($sQuery);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($participantID, $hash);
$stmt->store_result();
if($stmt->fetch()) { 
  if (password_verify($password, $hash)) {
          $_SESSION['part_id'] = $participantID;
	  $_SESSION['email'] = $email;
          if (isset($_SESSION['pageStore'])) {
            $pageStore = $_SESSION['pageStore'];
          }
          else {
            $pageStore = "index.php";
          }
          header("location: $pageStore"); // Redirecting To Profile
          $stmt->close();
          $conn->close();
        }
else {
	echo "Invalid user email or password.";
     }
      } else {
       echo 'Invalid user email or password.';
     }
$stmt->close();
$conn->close(); // Closing database Connection
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
input[type=email], input[type=password] {
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
  <title>OzAttend Login</title>
</head>
<body>
 <div>
   <form method="post"class="modal-content animate">
   	<img src = "background.jpg" alt="HTML5 Icon" style="width: 140px;height: 128px;">
    <p>Welcome to OzAttend</p>
<div class="imgcontainer">
     
      <img src="attend.PNG" alt="Avatar" class="avatar">
    </div>
    <div>
     <label>Email</label>
     <input type="email" name="email" required>
    </div>

    <div>
     <label>Password</label>
     <input type="password" name="password"required>
    </div>

    <button type="submit" name="signIn">Sign In
    </button>

    <div class="text-foot" align="right">
    Don't have an account? <a href="register.php">Register</a>
    <br>
    Forgot your password? <a href="reset_val.php">Reset Password</a>
    </div>
   </form>
 </div>
 </body>
</html>

<?php
session_start();
$confcode = $_POST['conf'];
$firstName = $_POST['FirstName'];
$lastName =$_POST['LastName'];
$email = $_POST['email'];
$hash = $_POST['hash'];

if (isset($_POST['confirm'])){
        if (empty($_POST['code'])){
                echo "No code entered!";
        } elseif (strcasecmp($confcode, $_POST['code']) == 0){
		include('config.php');
		$iQuery = "INSERT Into Participant (firstName, lastName, email, password) values(?, ?, ?, ?)";
		$stmt = $conn->prepare($iQuery);
          	$stmt->bind_param("ssss", $firstName, $lastName, $email, $hash);
                if($stmt->execute()) {
        	  echo 'Registration successful.';
		}
		header("location: reg_return.php");
        } else {
                echo "Code does not match!";
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
input[type=text] {
  width: 50%;
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

/* Extra styles for the cancel button */
h1 {
	text-align: left;
	padding: 14px 20px;
	margin: 8px 0;
}

.container {
  padding: 16px;
}

span.psw {
  float: right;
  padding-top: 16px;
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
  <title>Confirm Registration Code</title>
</head>
<body>

<h1>Welcome To OzAttend</h1>
  <div class="container">
    <label for="code">

      <b>Enter Code</b>
      <br>
    </label>

<form method="post" class="modal-content animate">
    <input type="hidden" name="conf" value="<?= $confcode?>">
    <input type="hidden" name="FirstName" value="<?= $firstName?>">
    <input type="hidden" name="LastName" value="<?= $lastName?>">
    <input type="hidden" name="email" value="<?= $email?>">
    <input type="hidden" name="hash" value="<?= $hash?>">
    <input type="text" placeholder="CODE" name="code" required>
    <br>
    <label for="code">
      <br>
    <button type="submit", name="confirm", id="confirm">Finish</button>
</form>

</form>
  </div>
</body>
</html>

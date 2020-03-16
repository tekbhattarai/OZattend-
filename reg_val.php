<?php
session_start();
include 'genrand.php';
$confcode = genKey();

if (isset($_POST['signUp'])) {

if (empty($_POST['FirstName']) || empty($_POST['LastName']) || empty($_POST['email']) || empty($_POST['newPassword'])) {
echo "Please fill in all of the required fields.";
header("location: register.php");
}
else
{
$firstName = $_POST['FirstName'];
$lastName =$_POST['LastName'];
$email = $_POST['email'];
$password = $_POST['newPassword'];
$hash = password_hash($password, PASSWORD_DEFAULT);
// Make a connection with MySQL server.
include('config.php');

//Make sure the entered email address is not already in the database
$sQuery = "SELECT participantID from Participant where email=? LIMIT 1";
// To protect MySQL injection for Security purpose
$stmt = $conn->prepare($sQuery);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($participantID);
$stmt->store_result();
$rnum = $stmt->num_rows;
if($rnum==0) { //if true, insert new data
	// the message
	$msg = "Your OzAttend confirmation code is\n$confcode";

	// use wordwrap() if lines are longer than 70 characters
	$msg = wordwrap($msg,70);

	// use header because otherwise it just says www-data
	$header = "From: OzAttend@cs.oswego.edu\r\n";
	$header .= "Reply-To: aleague@cs.oswego.edu\r\n";
	$header .= "Return-Path: aleague@cs.oswego.edu\r\n";

	// send email
	$sent = mail($email,"OzAttend Confirmation Code",$msg,$header);
	if ($sent) echo "<h1>Email has been sent.</h1>";
	else echo "FAILED TO SEND CONFIRMATION EMAIL!";
} else {
       echo '<h2>Someone has already registered with this email address.<h2>';
	header("location: reg_fail.php");
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

input {
  display: inline-block;
  float: left;
  padding: 14px 20px;
  margin: 8px 0;

}

h2 {
	text-align: left;
	padding: 14px 20px;
	margin: 8px 0;
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
  <title>Email Sent</title>
</head>
<body>

<form method="post" action="reg_conf.php">
    <input type="hidden" name="conf" value="<?= $confcode?>">
    <input type="hidden" name="FirstName" value="<?= $firstName?>">
    <input type="hidden" name="LastName" value="<?= $lastName?>">
    <input type="hidden" name="email" value="<?= $email?>">
    <input type="hidden" name="hash" value="<?= $hash?>">
    <button type="submit" name="Validate">Validate</button>
</form>

</body>
</html>


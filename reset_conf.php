<?php
session_start();
$email = $_POST['email'];
$confcode = $_POST['confcode'];
include('config.php');

if(isset($_POST['validate'])) {
	$pass = $_POST['pass'];
	$cpass = $_POST['cpass'];
	if ((strcasecmp($confcode, $_POST['match_code']) == 0) && (strcasecmp($pass, $cpass) == 0)){
                $iQuery = "UPDATE Participant SET password=? WHERE email=?";
		$hash = password_hash($pass, PASSWORD_DEFAULT);
                $stmt = $conn->prepare($iQuery);
                $stmt->bind_param("ss", $hash, $email);
                if($stmt->execute()) {
                  echo 'Password update successful.';
		  $stmt->close();
		  $conn->close();
                  header("location: reset_return.php");
		} else {
		  echo "Unable to update password.";
		}
        } else {
                echo "Code does not match!";
        }
} else {
if(isset($_POST['Finish'])){

	$query = "SELECT participantID FROM Participant WHERE email =?";
	$stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
	$stmt->execute();
	$stmt->store_result();
        if($stmt->fetch()) {
		// the message
        	$msg = "Someone (hopefully you) has requested a password\n"
			."change for your OzAttend account.\n\n"
			."Your OzAttend password validation code is\n$confcode";

        	// use wordwrap() if lines are longer than 70 characters
        	$msg = wordwrap($msg,70);

        	// use header because otherwise it just says www-data
        	$header = "From: OzAttend@cs.oswego.edu\r\n";
        	$header .= "Reply-To: aleague@cs.oswego.edu\r\n";
        	$header .= "Return-Path: aleague@cs.oswego.edu\r\n";

        	// send email
		$sent = mail($email,"OzAttend Password Service",$msg,$header);
        	if ($sent) echo "<h1>Email has been sent.</h1>";
	        else echo "FAILED TO SEND CONFIRMATION EMAIL!";
	}else{
		echo "This email does not exist in the database.";
	}
	$stmt->close();
	$conn->close();
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
input[type=text], input[type=password]{

  width:20%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
}
button {
  background-color: green;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: auto;
}

</style>
<title>Confirm Password Change</title>
</head>
<body>
<form method="post">
 <div class="container">
      <input type="hidden" name="confcode" value="<?= $confcode?>">
      <input type="hidden" name="email" value="<?= $email?>">
      <label for="email"><h3>New password:</h3></label>
      <input type="password" placeholder="new password" name="pass" required>
      <label for="email"><h3>Confirm password:</h3></label>
      <input type="password" placeholder="confirm password" name="cpass" required>
      <label for="email"><h3>Enter confirmation code:</h3></label>
      <input type="text" placeholder="CODE" name="match_code" required><br>
      <button type="submit" name = "validate">Validate</button>
      
</div>
</form>
</body>
</html>


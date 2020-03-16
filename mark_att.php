<?php
session_start();
if (!isset($_SESSION['email'])) {
        header("location: logout.php");
}
$evt_key = $_POST['evt_key'];
$evt_name = $_POST['evt_name'];
$evt_id = $_GET['evt_id'];

include 'config.php';
//Check the connection
if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['confirm'])){
        if (empty($_POST['key_val'])){
                echo "<h2 style=\"color: OrangeRed\">No key entered!</h2>";
        } elseif (strcasecmp($evt_key, $_POST['key_val']) == 0){
		//mysql query
		$part_id = $_SESSION['part_id'];
		$query = "INSERT INTO Participant_has_Event "
			. "(Participant_participantID,Event_eventID,timeattended) "
			. "values('$part_id','$evt_id',NOW())";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$stmt->store_result();
		if($stmt->fetch()){
		} else {
		       echo "Well that didn't work";
		}
		$stmt->close();
		$conn->close();		

		// the message
		$msg = "You have attended $evt_name.\nHope you had a good time!\n\n-OzAttend";

		// use wordwrap() if lines are longer than 70 characters
		$msg = wordwrap($msg,70);

		// use header because otherwise it just says www-data
		$header = "From: OzAttend@cs.oswego.edu\r\n";
		$header .= "Reply-To: aleague@cs.oswego.edu\r\n";
		$header .= "Return-Path: aleague@cs.oswego.edu\r\n";

		// send email
		$sent = mail($_SESSION['email'],"OzAttend Notification",$msg,$header);
		if ($sent) echo "<h1>Email has been sent.</h1>";
		else echo "FAILED TO SEND CONFIRMATION EMAIL!";

                header("location: conf_att.php");
        } else {
                echo "<h2 style=\"color: OrangeRed\">Event key does not match!</h2>";
        }
}

//mysql query
$query = "SELECT * FROM Event WHERE eventID=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $evt_id);
$stmt->execute();
$stmt->bind_result($evt_id, $evt_name, $bldg, $room, $evt_key, $date, $time, $end);
$stmt->store_result();
if($stmt->fetch()){
} else {
	echo "Well that didn't work";
}
$stmt->close();
$conn->close();
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
h2 {
        text-align: left;
        padding: 14px 20px;
        margin: 8px 0;
}

h4 {
	text-align: left;
	color: green;
	line-height: 200%;
	background-color: Gainsboro;
	padding: 12px 20px;
 	margin: 8px 16px;
	border: 1px solid grey;
	display: inline-block;
 	box-sizing: border-box;
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
  <title>Event Info</title>
</head>
<body>

  <h2><?= $evt_name?></h2>

  <h4>
	<b>Where:</b> <?= $bldg?> <?= $room?>
  	<br>
  	When: <?= $date?> at <?= $time?>
	<br>
	Until: <?= $end?>
  </h4>

<?php
if (strcasecmp(date("Y-m-d"), $date) == 0 
	&& strcasecmp(date("H:i:s"), $time) >= 0 
	&& strcasecmp(date("H:i:s"), $end) <=0) {
	echo  "<div class=\"container\">
		<label for=\"key_val\">
		<b>Enter event key for $evt_name</b>
      		<br>
    		</label>

	<form method=\"post\" class=\"modal-content animate\">
    		<input type=\"hidden\" name=\"evt_key\" value=\"$evt_key\">
    		<input type=\"hidden\" name=\"evt_name\" value=\"$evt_name\">
		<input type=\"text\" placeholder=\"EVENT KEY\" name=\"key_val\" required>
		<br><br>
    	<button type=\"submit\", name=\"confirm\", id=\"confirm\">Attend</button>
	</form>
	<br>";
} else {
	echo "<h3>This event is not yet live.</h3>";
}
?>

<a href="index.php">Return to event page.</a>

</form>
  </div>
</body>
</html>

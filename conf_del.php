<?php
session_start();
if (!isset($_SESSION['email'])) {
	header("location: logout.php");
}
$evt_id = $_POST['evt_id'];
$evt_name = $_POST['evt_name'];
$evt_key = $_POST['evt_key'];

if (isset($_POST['confirm'])){
	if (empty($_POST['key_val'])){
		echo "<h2 style=\"color: DarkRed\">No key entered!</h2>";
	} elseif (strcasecmp($evt_key, $_POST['key_val']) == 0){

		include 'config.php';
		//Check the connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}

		//get the organizer emails before deleting them
		$equery = "SELECT organizerName FROM Organizer WHERE Event_eventID = '$evt_id'";
		$result = $conn->query($equery);

		//delete event from organizer table
		$query = "DELETE FROM Organizer WHERE Event_eventID = '$evt_id'";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$stmt->store_result();
		$stmt->close();

		//delete event from Participant_has_Event table
		$query = "DELETE FROM Participant_has_Event WHERE Event_eventID = '$evt_id'";
		$stmt = $conn->prepare($query);
		$stmt->execute();
		$stmt->store_result();
		$stmt->close();

		//now we can delete the event sans dependencies
		$dquery = "DELETE FROM Event WHERE eventKey = '$evt_key'";
		if ($conn->query($dquery) === TRUE){

			//if event deletion works, email all organizers
			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					if (strcasecmp($row['organizerName'], $_SESSION['admin']) != 0){
						// the message
						$msg = "Your event $evt_name has been deleted.\n\n-OzAttend";

						// use wordwrap() if lines are longer than 70 characters
						$msg = wordwrap($msg,70);

						// use header because otherwise it just says www-data
						$header = "From: OzAttend@cs.oswego.edu\r\n";
						$header .= "Reply-To: aleague@cs.oswego.edu\r\n";
						$header .= "Return-Path: aleague@cs.oswego.edu\r\n";

						// send email
						$sent = mail($row['organizerName'],"OzAttend Notification",$msg,$header);
					}
				}
			} else {
				echo "This event seems to have no organizers.";
			}

			$conn->close();		

			header("location: index.php");
		} else{
			echo "<h2>Failed to delete event $evt_name!</h2>";
		}
	} else {
		echo "<h2 style=\"color: OrangeRed\">Event key does not match!</h2>";
	}
} else echo "<h2>Are you sure you want to delete $evt_name?</h2>"
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
<title>Confirm Event Deletion</title>
</head>
<body>

<div class="container">
<label for="key_val">

<b>Enter event key for <?= $evt_name?></b>
<br>
</label>

<form method="post" class="modal-content animate">
<input type="hidden" name="evt_key" value="<?= $evt_key?>">
<input type="hidden" name="evt_id" value="<?= $evt_id?>">
<input type="hidden" name="evt_name" value="<?= $evt_name?>">
<input type="text" placeholder="EVENT KEY" name="key_val" required>
<br>
<label for="key_val">
<br>
<button type="submit", name="confirm", id="confirm">Yes, delete</button>
</form>

<br>
<a href="edit.php?evt_id=<?= $evt_id?>">No, go back.</a>

</div>
</body>
</html>

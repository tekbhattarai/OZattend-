<?php
session_start();
if (!isset($_SESSION['email'])) {
	header("location: logout.php");
}
include 'genrand.php';
$eventKey= genKey();
if (isset($_POST['Finish'])) {
	$eventName = $_POST['eventName'];
	$buildingName = $_POST['buildingName'];
	$roomNumber = $_POST['roomNum'];
	$startTime = $_POST['stime'];
	$endTime = $_POST['etime'];
	$eventDate =$_POST['Date'];

	// Make a connection with MySQL server.
	include('config.php');
	$querry = "INSERT Into Event (eventName,buildingName, roomNumber,eventKey,eventDate,startTime,endTime) values('$eventName','$buildingName','$roomNumber','$eventKey','$eventDate','$startTime','$endTime')";
	if($conn->query($querry)){
		$query = "SELECT eventID FROM Event WHERE eventKey =?";
		$stmt = $conn->prepare($query);
		$stmt->bind_param("s", $eventKey);
		$stmt->execute();
		$stmt->bind_result($evt_id);
		$stmt->store_result();
		if($stmt->fetch()) {
			$oquery = "INSERT INTO Organizer (organizerName,Event_eventID) values(?,?)";
			$ostmt = $conn->prepare($oquery);
			$ostmt->bind_param("ss", $_SESSION['email'],$evt_id);
			if ($ostmt->execute()) {} else echo "error saving organizer";
			$ostmt->close();
			if (strcasecmp($_SESSION['email'], $_SESSION['admin']) != 0){
				$astmt = $conn->prepare($oquery);
				$astmt->bind_param("si", $_SESSION['admin'],$evt_id);
				if ($astmt->execute()) {} else echo "error saving admin";
				$astmt->close();
			}
		} else {
			echo "error retrieving event id";
		}
		$stmt->close();

	}
	else{
		echo "error creating event";
	}
	$conn->close(); // Closing database Connection
	header( 'Location: index.php' ) ;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>


body {font-family: Arial, Helvetica, sans-serif;}
form {display:;}
/* Full-width input fields */
input[type=time], input[type=date], input[type=text] {
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
<title>Create Event</title>
</head>
<body>

<h2>OzAttend</h2>
<div class="imgcontainer">

<img src="attend.PNG" alt="Avatar" class="avatar">
</div>

<form method = "POST" oninput='validateTime()'>
<div class="rlform-group">
<label>Event Name</label>
<input type="text" name="eventName" class="rlform-input" required>
</div>

<div class="rlform-group">
<label>Building</label>
<input type="text" name="buildingName" class="rlform-input" required>
</div>

<div class="rlform-group">         
<label>RoomNum</label>
<input type="text" name="roomNum" class="rlform-input">
</div>

<div class="rlform-group">         
<label>Date</label>
<input type="date" name="Date" class="rlform-input" required>
</div>

<div class= "rlform-group">
<label>Start Time</label>
<input type="time" name = "stime" id="start" class ="rlform-input" required>
</div>

<div class= "rlform-group">
<label>End Time</label>
<input type="time" name = "etime" id="end" class ="rlform-input" required>
</div>

<button class="rlform-btn" name="Finish">Finish</button>
</form> 

<a href="index.php">Return to Event List</a>

<script>
  function validateTime(){
  if(start.value < end.value) {
        end.setCustomValidity('');
        } else {
    end.setCustomValidity('Event cannot end before it has begun.');
  }
}
</script>

</body>
</html>

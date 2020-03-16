<?php
session_start();
if (!isset($_SESSION['email'])) {
        header("location: logout.php");
}
include('config.php');
$evt_id = $_GET['evt_id'];

if (isset($_POST['Finish'])) {
	$evt_name = $_POST['eventName'];
	$bldg = $_POST['buildingName'];
	$room = $_POST['roomNum'];
	$time = $_POST['stime'];
	$end = $_POST['etime'];
	$date =$_POST['Date'];
	$eventID = $_POST['eventID'];
	//echo $evt_name . $bldg . $room . $time . $end . $date . $eventID;
	// Make a connection with MySQL server.

	$querry = "UPDATE Event SET eventName =? , buildingName =? , roomNumber =? , eventDate =? , startTime =? , endTime =? WHERE eventID =?";
	$stmt = $conn->prepare($querry);
	$stmt->bind_param("ssssssi", $evt_name, $bldg, $room, $date, $time, $end, $eventID);
if($stmt->execute()) {

		echo "Data saved";

		header( 'Location: index.php' ) ; 
	}
	else{
		echo "There was an error when updating your data!";
	}
	$stmt->close();
	$conn->close(); // Closing database Connection
} else {
	//make query to populate default values for fields
	$query = "SELECT eventName, buildingName, roomNumber, eventKey, eventDate, "
		. "startTime, endTime FROM Organizer JOIN Event "
		. "WHERE Event.eventID = Organizer.Event_eventID "
		. "AND Event.eventID=? AND Organizer.organizerName=?";
	$stmt = $conn->prepare($query);
	$stmt->bind_param("is", $evt_id, $_SESSION['email']);
	$stmt->execute();
	$stmt->bind_result($evt_name, $bldg, $room, $evt_key, $date, $time, $end);
	$stmt->store_result();
	if($stmt->fetch()){
	} else {
		header("location: index.php");
	}
	$stmt->close();
	$conn->close();
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
<title>Edit Event</title>
</head>
<body>

<h2>OzAttend</h2>
<h3>Event Key: <?= $evt_key?></h3> 

<form method = "POST" oninput='validateTime()'>
   <div class="rlform-group">
    <label>Event Name</label>
    <input type="text" name="eventName" class="rlform-input" value="<?= $evt_name?>" required>
   </div>

  <div class="rlform-group">
    <label>Building</label>
    <input type="text" name="buildingName" class="rlform-input" value="<?= $bldg?>" required>
   </div>
    
   <div class="rlform-group">         
    <label>Room Number</label>
    <input type="text" name="roomNum" class="rlform-input" value="<?= $room?>">
   </div>
    
   <div class="rlform-group">         
    <label>Date</label>
    <input type="date" name="Date" class="rlform-input" value="<?= $date?>" required>
     </div>

    <div class= "rlform-group">
   <label>Start Time</label>
<input type="time" name = "stime" id="start" class ="rlform-input" value="<?= $time?>" required>
    </div>

<div class= "rlform-group">
   <label>End Time</label>
<input type="time" name = "etime" id="end" class ="rlform-input" value="<?= $end?>" required>
    </div>

<input type="hidden" name = "eventID" class ="rlform-input" value="<?= $evt_id?>">

    <button class="rlform-btn" name="Finish">Update</button>
</form>
<form method="post" action="conf_del.php">
	<input type="hidden" name="evt_id" value="<?= $evt_id?>">
	<input type="hidden" name="evt_name" value="<?= $evt_name?>">
	<input type="hidden" name="evt_key" value="<?= $evt_key?>">
	<button name="Delete">Delete</button>
</form>

<form method="post" action="coorgs.php">
        <input type="hidden" name="evt_id" value="<?= $evt_id?>">
        <input type="hidden" name="evt_name" value="<?= $evt_name?>">
        <button name="coorgs">Add/Edit Organizers</button>
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

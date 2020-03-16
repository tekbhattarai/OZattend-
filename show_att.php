<?php
session_start();
if (!isset($_SESSION['email'])) {
        header("location: logout.php");
}
$evt_id = $_GET['evt_id'];

include 'config.php';
//Check the connection
if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
}

//mysql query
$query = "SELECT eventName FROM Event WHERE eventID=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $evt_id);
$stmt->execute();
$stmt->bind_result($evt_name);
$stmt->store_result();
if($stmt->fetch()){
} else {
        echo "Well that didn't work";
}
$stmt->close();

//mysql query to get list of attendees
$query = "SELECT firstName, lastName, email "
	. "FROM Participant JOIN Participant_has_Event "
	. "WHERE Participant_has_Event.Participant_participantID = Participant.participantID "
	. "AND Participant_has_Event.Event_eventID =?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $evt_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
          //output the data
	echo "<h2>Attendees For $evt_name</h2>";
	echo "<table border='1'>";
	echo "<tr><th>Name</th><th>Email</th></tr>";
          while ($row = $result->fetch_assoc()) {
                echo "<tr><td>";
                echo $row['firstName'] . " ";
                echo $row['lastName'];
                echo "</td><td>";
                echo $row['email'];
                echo "</td></tr>";
            }
	echo "</table>";
          
} else {
	echo "<b>No one has attended this event yet.</b>";
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

/* Set a style for all buttons */
button {
  background-color: green;
  color: white;
  padding: 14px 20px;
  margin: 16px 20px;
  border: none;
  cursor: pointer;
  width: auto;
}

table {
    border-collapse: collapse;
    margin: 0px 20px;
  }
  th, td {
    padding: 10px;
    text-align: left;
  }
  tr:nth-child(even) {
    background-color: #eee;
  }
  tr:nth-child(odd) {
    background-color: #fff;
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

a {
	margin: 0px 20px;
}

</style>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Attendee List</title>
</head>
<body>

<form method="get" action="edit.php">
    <input type="hidden" name="evt_id" value="<?= $evt_id?>">
    <button type="submit" name="Validate">Edit Event</button>
</form>

<a href="index.php">Return to event page.</a>

</body>
</html>

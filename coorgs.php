<?php
session_start();
if(!isset($_SESSION['email'])) {
         header("location: logout.php");
}
include('config.php');
$evt_id = $_POST['evt_id'];
$evt_name = $_POST['evt_name'];

if (isset($_POST['add'])) {
	$new_org = $_POST['new_org'];
	$query = "SELECT * FROM Organizer WHERE organizerName=? AND Event_eventID=? LIMIT 1";
	$stmt = $conn->prepare($query);
	$stmt->bind_param("si", $new_org, $evt_id);
	$stmt->execute();
	$stmt->store_result();
	if($stmt->fetch()) {
		echo "<h2 style=\"color: OrangeRed\">$new_org is already an organizer!</h2>\n";
	} else{

		// Make a connection with MySQL server.
		$query = "INSERT INTO Organizer (organizerName, Event_eventID) values(?,?)";
		$stmt = $conn->prepare($query);
		$stmt->bind_param("si", $new_org, $evt_id);
		if($stmt->execute()) {} else{
			echo "Failed to add $new_org!";
		}
	}
	$stmt->close();
}

if (isset($_POST['remove'])) {
	$bad_org = $_POST['bad_org'];
	$query = "DELETE FROM Organizer WHERE organizerName=? AND Event_eventID=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $bad_org, $evt_id);
        if($stmt->execute()) {} else{
                echo "Failed to remove $new_org!";
        }
        $stmt->close();
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
input[type=email] {
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

.ibox {
  border: 1px solid Grey;
  display: block;
  padding: 3px 20px;
  text-align: left;
  background-color: Gainsboro;
  display: inline-block;
  box-sizing: border-box;
  margin: 8px 0;
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
<title>Edit Organizers</title>
</head>
<html>
<body>

<h2>Organizers for <?= $evt_name?>:</h2>

<?php
	//make query to populate default values for fields
        $query = "SELECT organizerName FROM Organizer JOIN Event "
                . "WHERE Event.eventID = Organizer.Event_eventID "
                . "AND Event.eventID =?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $evt_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
                //output the data
		$count = 1;
		while ($row = $result->fetch_assoc()) {
			if (strcasecmp($row['organizerName'], $_SESSION['admin']) != 0){
			echo "<div class='ibox'><form method='post'>
				<b>{$row['organizerName']}</b>&nbsp&nbsp&nbsp<wbr>
				<input type='hidden' name='evt_id' value='$evt_id'>
				<input type='hidden' name='evt_name' value='$evt_name'>
				<input type='hidden' name='bad_org' value='{$row['organizerName']}'>
				<button name='remove'>Remove</button>
				</form></div>
				<br>";	
			$count++;
			}
           	}
		if ($count < 2) {
			echo "<h3 style=\"color: OrangeRed\">WARNING: if you don't add "
				. "any organizers, this event may become unusable!</h3>\n";
		}
} else {
        echo "<h3 style=\"color: OrangeRed\">Organizer data was not successfully retrieved.</h3>";
}
        $stmt->close();
        $conn->close();
?>

    <label for="new_org">
      <br>
      <b>Enter the new co-organizer's email:</b>
      <br>
    </label>

<form method="post" oninput='validateEmail()'>
	<input type="hidden" name="evt_id" value="<?= $evt_id?>">
	<input type="hidden" name="evt_name" value="<?= $evt_name?>">
	<input type="email" placeholder="somebody@oswego.edu" name="new_org" id="newEmail" required>
	<button name="add">Add</button>
</form>
<a href="edit.php?evt_id=<?= $evt_id ?>">Return to edit page</a> 

<script>
  function validateEmail(){
  if(newEmail.value.includes("@oswego.edu")){
        newEmail.setCustomValidity('');
        } else {
    newEmail.setCustomValidity('Must be an oswego.edu email address');
  }
}
</script>

</body>
</html>

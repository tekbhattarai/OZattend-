<?php
session_start();
include 'genrand.php';
$confcode=genKey();
?>



<!DOCTYPE html>
<html>
<head>
<style>
body {font-family: Arial, Helvetica, sans-serif;}
form {display:;}
/* Full-width input fields */
input[type=email]{

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
<title>Enter Email</title>
</head>
<body>
<form method="post" action="reset_conf.php">
 <div class="container">
      <input type="hidden" name="confcode" value="<?= $confcode?>">
      <label for="email"><h2>Enter email:</h2></label><br>
      <input type="email" placeholder="Enter email" name="email" required>
	<br>
      <button type="submit" name = "Finish">Validate</button>
      
</div>
</form>
</body>
</html>


<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
            * {
                box-sizing: border-box;
            }

            #myInput , #mymyInput {
                background-image: url('/css/searchicon.png');
                background-position: 10px 12px;
                background-repeat: no-repeat;
                width: 100%;
                font-size: 16px;
                padding: 12px 20px 12px 40px;
                border: 1px solid #ddd;
                margin-bottom: 12px;
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

            button:hover {
                opacity: 0.8;
            }

	    .event {
		text-align:left;
		vertical-align:top;
		width:49%;
		display: inline-block;
		white-space: pre-line;
	    }

	    .datetime {
		text-align:right;
		width:49%;
		color: DarkGreen;
		display: inline-block;
	    }

	    #mymyUL {
                list-style-type: none;
                padding: 0;
                margin: 0;
            }

            #mymyUL li a {
                border: 1px solid black;
                margin-top: -1px; /* Prevent double borders */
                background-color: Wheat;
                padding: 12px;
                text-decoration: none;
                font-size: 18px;
                color: black;
                display: block
            }

            #mymyUL li a:hover:not(.header) {
                background-color: #eee;
            }

	    #myUL {
                list-style-type: none;
                padding: 0;
                margin: 0;
            }

            #myUL li a {
                border: 1px solid #ddd;
                margin-top: -1px; /* Prevent double borders */
                background-color: #f6f6f6;
                padding: 12px;
                text-decoration: none;
                font-size: 18px;
                color: black;
                display: block;
            }

            #myUL li a:hover:not(.header) {
                background-color: #eee;
            }

	    .live {
	        color: DarkGreen;
	        background-color: White;
	        border: 1px solid Maroon;
	        padding: 3px;
		display: inline-block;
	    }

	     blink {
		-webkit-animation:1s linear infinite condemned_blink_effect; // for Android
		animation: 2s linear infinite condemned_blink_effect;
		color: DarkGreen;
                background-color: White;
                border: 1px solid Maroon;
                padding: 3px;
                display: inline-block;
	    }
@-webkit-keyframes condemned_blink_effect { // for Android
  0% {
    visibility: hidden;
  }
  50% {
    visibility: hidden;
  }
  100% {
    visibility: visible;
  }
}
@keyframes condemned_blink_effect {
  0% {
    visibility: hidden;
  }
  50% {
    visibility: hidden;
  }
  100% {
    visibility: visible;
  }
}

        </style>
	<title>Event List</title>
    </head>
    <body>
        <?php
	session_start();
	if (!isset($_SESSION['email'])) {
		header("location: logout.php");
	}
        include 'config.php';
        //Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

	//mysql query for My Events
	$email = $_SESSION['email'];
        $my_query = "SELECT eventID, eventKey , eventName , eventDate, startTime, endTime "
		. "FROM Organizer JOIN Event "
		. "WHERE Organizer.Event_eventID = Event.eventID "
		. "AND Organizer.organizerName = '$email' "
		. "ORDER BY eventDate, startTime, eventName";
        $my_result = $conn->query($my_query);

        //mysql query for all events
        $query = "SELECT eventID , eventName , eventDate, startTime, endTime "
		. "FROM Event ORDER BY eventDate, startTime, eventName";
        $result = $conn->query($query);
        ?>
        <a href="logout.php">
            <button style="width: auto; float: right; type= submit">Sign Out </button>
        </a>

	<br>
	<h2>My Events</h2>
	<input type="text" id="mymyInput" onkeyup="mymyFunction()" placeholder="Search your events.." title="Type in a name">

	<ul id="mymyUL">
          <?php
            if ($my_result->num_rows > 0) {
            //output the data
          while ($my_row = $my_result->fetch_assoc()) {
		if ((strcasecmp(date("Y-m-d"), $my_row['eventDate']) == 0 
			&& strcasecmp(date("H:i:s"), $my_row['startTime']) >= 0) 
			|| strcasecmp(date("Y-m-d"), $my_row['eventDate']) > 0){
			$goto = "show_att.php";
		} else {
			$goto = "edit.php";
		}
                    echo
                      "<li><a href='{$goto}?evt_id={$my_row['eventID']}'>"
			. "<div class=\"event\"><label>{$my_row['eventName']}</label>"
			. "&nbsp&nbsp&nbsp<wbr> <b>Key:&nbsp;{$my_row['eventKey']}</b></div>"
			. "<div class=\"datetime\">Date:&nbsp;{$my_row['eventDate']}<wbr>"
			. "&nbsp;&nbsp;&nbsp;Time:&nbsp;{$my_row['startTime']}</div></a></li>\n";
          }
            } else {
                echo "<h3>You currently are not organizing any events.</h3><br>";
          }
            ?>
	</ul>

        <h2>Event list</h2>

        <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for names.." title="Type in a name">

        <ul id="myUL">
          <?php
            if ($result->num_rows > 0) {
            //output the data
          while ($row = $result->fetch_assoc()) {
                if (strcasecmp(date("Y-m-d"), $row['eventDate']) == 0
                        && strcasecmp(date("H:i:s"), $row['startTime']) >= 0
                        && strcasecmp(date("H:i:s"), $row['endTime']) <= 0){
                        $activeStatus = "&nbsp&nbsp&nbsp<wbr> <blink>LIVE</blink>";
                } else {
                        $activeStatus = "";
                }

		if ((strcasecmp(date("Y-m-d"), $row['eventDate']) == 0
                        && strcasecmp(date("H:i:s"), $row['endTime']) <= 0)
                        || strcasecmp(date("Y-m-d"), $row['eventDate']) < 0){
		    echo
                      "<li><a href='mark_att.php?evt_id={$row['eventID']}'>"
			. "<div class=\"event\"><label>{$row['eventName']}</label>{$activeStatus}</div> <div class=\"datetime\">"
			. "Date:&nbsp{$row['eventDate']} &nbsp;&nbsp;&nbsp;"
			. "Time:&nbsp{$row['startTime']}</div></a></li>\n";
		}         
		}
            } else {
                echo "There are no events here";
          }
            ?>
	</ul>

            <a href="create.php">
                <button style="width:auto; type= submit">Create Event</button>
            </a>
            <script>
                function myFunction() {
                    var input, filter, ul, li, a, i, txtValue;
                    input = document.getElementById("myInput");
                    filter = input.value.toUpperCase();
                    ul = document.getElementById("myUL");
                    li = ul.getElementsByTagName("li");
                    for (i = 0; i < li.length; i++) {
                        a = li[i].getElementsByTagName("label")[0];
                        txtValue = a.textContent || a.innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            li[i].style.display = "";
                        } else {
                            li[i].style.display = "none";
                        }
                    }
                }

		function mymyFunction() {
                    var input, filter, ul, li, a, i, txtValue;
                    input = document.getElementById("mymyInput");
                    filter = input.value.toUpperCase();
                    ul = document.getElementById("mymyUL");
                    li = ul.getElementsByTagName("li");
                    for (i = 0; i < li.length; i++) {
                        a = li[i].getElementsByTagName("label")[0];
                        txtValue = a.textContent || a.innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            li[i].style.display = "";
                        } else {
                            li[i].style.display = "none";
                        }
                    }
                }
            </script>
    </body>
</html>



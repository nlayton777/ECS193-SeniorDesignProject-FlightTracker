<?php
/*
 * this file initiates the background search for
 * the user request. it displays a summary of their 
 * travel itinerary and displays the remaining search
 * time
 */

/*
 * don't need to check if user is already logged
 * in because this page will log them into their 
 * new account.
 * 
 * set the session variables with the new user 
 * id and email
 */
session_start();

require_once('flight_tracker.php');
$post = $_POST;
$email = $post['email'];
$userSource = $post['origin'];
$userDestination = $post['destination'];
$userID = createNewSearch($post);
$_SESSION['email'] = $email;
$_SESSION['id'] = $userID;
$remaining = getRemainingTime($userID,$email);
?>
<!DOCTYPE html>
<html>
    <head>
	<title>SoFly | Background Search</title>

	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="stylesheet" href="bootstrap.css"/>
	<script src="jquery-2.1.3.js"/></script>
	<script src="bootstrap.js"></script>
	<link rel="stylesheet" href="styles.css"/>
	<link rel="stylesheet" href="flipclock.css"/>
	<script src="flipclock.min.js"></script>
	<script src="flight_tracker.js"></script>

	<script>
	    /*
	     * when the page loads, generate an AJAX
	     * request to the background_search.php script
	     * to initiate the background search with the 
	     * specified ID and email
	     */
	    window.onload = function() {
		var xmlhttp;
		if (window.XMLHttpRequest) { xmlhttp = new XMLHttpRequest(); }
		else { xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); }

		xmlhttp.onreadystatechange = function() {
		    if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
		    { 
			document.getElementById("test").innerHTML = xmlhttp.responseText;
		    }
		}

		// package the URL for the GET request to be sent
		var str = "id=<?php echo $userID; ?>";
		str += "&email=<?php echo $email; ?>";
		str += "&source=<?php echo $userSource; ?>";
		str += "&destination=<?php echo $userDestination; ?>";
		str += "&searchTime=<?php echo $post['search_time']; ?>";
		xmlhttp.open("GET","background_search.php?" + str,true);
		xmlhttp.send();
	    }; // sendMessage()
	</script>
    </head>

    <body>
	<!-- navigation bar for top of page -->
	<nav class="navbar navbar-inverse">
	    <div id="main" class="container-fluid">
		<div class="navbar-header">
		    <button type="button" class="navbar-toggle" 
			data-toggle="collapse" data-target="#mynavbar">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		    </button>
		    <a class="navbar-brand" href="index.php">SoFly</a>
		</div>

		<div class="collapse navbar-collapse" id="mynavbar">
		    <ul class="nav navbar-nav">
			<li><a href="index.php">Find a Flight</a></li>
			<li><a href="results.php">My Search</a></li>
			<li class="active"><a href="#">Search Summary</a></li>
		    </ul>
		    <ul class="nav navbar-nav navbar-right">
			<li><a href="about.php">About</a></li>
			<li><a href="contact.php">Contact</a></li>
			<li><a href="javascript:;" onclick="submitForm()">Log Out</a></li>
		    </ul>
		</div>
	    </div>
	</nav>

	
	<!-- 
	    the container below holds the remaining search time
	    and a summary of the search parameters
	-->
	<div class="containter">
	    <div class="col-md-2"></div>
	    <div class="col-md-8 countdown">
		<!-- 
		    display the header and the countdown
		    clock that is set to the user-specified
		    search interval
		-->
		<h1>Search Time Remaining</h1>
		<div class="clock"></div>
		<p>We have begun your background search and will notify you once
		   we have either found your results or reached the end of your 
		   search time. We have provided a summary of your search 
		   parameters below. Please stay near your phone or computer because  
		   we will be updating you via email. Be sure to have your 
		   Request ID and Email ready when you return for the updated 
		   search results.
		</p>
		<hr>
		<h3>Summary of Itinerary</h3>
		<hr>

		<?php
		    // start countdown clock with time specified by user
		    echo "<script>CountdownClock({$remaining})</script>";

		    /*
		     * parse date information to be 
		     * displayed in the itinerary
		     */
		    $returnDate = $post['return_date'];
		    if (!isset($post['return_date']) || $post['return_date'] == "NULL")
			$returnDate = "N/A";

		    /*
		     * display itinerary
		     */
		    echo <<<_SECTION1
		    <div class="row">
			<div class="col-md-6">
			    <ul>
				<li>Request ID: <strong>{$userID}</strong></li>
				<li>Email: <strong>{$post['email']}</strong></li>
				<li>Search Time: {$post['search_time']} hours</li>
				<li>Maximum Price Limit: \${$post['price']}</li>
			    </ul>
			</div>

			<div class="col-md-6">
			    <ul>
				<li>Origin: {$post['origin']}</li>
				<li>Destination: {$post['destination']}</li>
				<li>Date of Departure: {$post['depart_date']}</li>
				<li>Date of Return: {$returnDate}</li>
			    </ul>
			</div>
		    </div>
		    <hr>
			
		    <div class='row'>
			<div class="col-md-6">
			    <ul>
				<li>Number of Passengers:</li>
				<ul>
_SECTION1;

				/*
				 * scan the passenger types and display
				 * their numbers depending on what was
				 * specified by the user
				 */
				$type = array('adults', 'children', 'seniors',
					      'seat_infant', 'lap_infant');
				$name = array(
				    'adults'	    => 'Adults', 
				    'children'	    => 'Children', 
				    'seniors'	    => 'Seniors',
				    'seat_infant'   => 'Seat Infant', 
				    'lap_infant'    => 'Lap Infant'
				);

				foreach ($type as $t)
				{
				    if (isset($post[$t]) && $post[$t] > 0)
				    {
					$str = "";
					$temp = explode("_", $name[$t]);
					if (count($temp) > 1)
					{
					    $pieces = array();
					    foreach ($temp as $word)
						$pieces[] = ucfirst($word);
					    $str = implode(" ", $pieces);
					} else
					    $str = ucfirst($temp[0]);

					echo "<li>{$str}: {$post[$t]}</li>";
				    } // if
				} // foreach
				
				$s = "";
				$countFlag = false;
				if (count($post['airline']) > 1)
				{
				    $countFlag = true;
				    $s = "s";
				}
				echo <<<_SECTION1A
				</ul>
			    </ul>
			</div>

			<div class='col-md-6'>
			    <ul>
				<li>Airline Preference{$s}:</li>
				<ul>
_SECTION1A;

				/* 
				 * scan the airline preferences and display
				 * their codes as specified by the user
				 */
				if (file_exists('airlines.txt')) 
				{
				    $codes = fopen('airlines.txt', 'r');
				    $names = array();
				    foreach ($post['airline'] as $airline) {
					while ($line = fgets($codes)) {
					    if (strpos($line, $airline) == true)
					    {
						$names[] = $line;
						break;
					    }
					} // while
				    } // for
				} // if

				$i = 1;
				if ($countFlag)
				{
				    foreach ($names as $airline) 
				    {
					echo "<li>Airline {$i}: {$airline}</li>";
					$i++;
				    } // foreach airline
				} else // if there's only 1 airline
				    echo "<li>{$airline}</li>";

				echo <<<_SECTION2
				</ul>
			    </ul>
			</div>
		    </div>
		    
		    <div id="test" class="row" style="margin-left: 200px; background-color: yellow;">stuff</div>
		    
_SECTION2;
		?>
	    </div>
	    <div class="col-md-2"></div>
	</div>

	<!--
	    hidden form submits information to
	    the logout page for managing sessions
	-->
	<form id="hiddenForm" method="post" action="logout.php">
	    <input type="hidden" name="webpage" value="index.php" \>
	</form>
    </body>
</html>

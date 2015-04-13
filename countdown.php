<!DOCTYPE html>
<html>
    <head>
	<title>UCD Flight Tracker</title>

	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="stylesheet" href="bootstrap.css"/>
	<script src="jquery-2.1.3.js"/></script>
	<script src="bootstrap.js"></script>
	<link rel="stylesheet" href="styles.css"/>
	<link rel="stylesheet" href="flipclock.css"/>
	<script src="flipclock.min.js"></script>
	<script src="flight_tracker.js"></script>
    </head>

    <body>
	<nav class="navbar navbar-inverse" style="visibility: hidden;"></nav>
	<nav class="navbar navbar-inverse navbar-fixed-top">
	    <div id="main" class="container-fluid">
		<div class="navbar-header">
		    <button type="button" class="navbar-toggle" 
			data-toggle="collapse" data-target="#mynavbar">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		    </button>
		    <a class="navbar-brand" href="index.php">Flight Tracker</a>
		</div>

		<div class="collapse navbar-collapse" id="mynavbar">
		    <ul class="nav navbar-nav">
			<li class="active"><a href="index.php">Search</a></li>
			<li><a href="about.php">About</a></li>
		    </ul>
		    <ul class="nav navbar-nav navbar-right">
			<li><a href="contact.php">Contact</a></li>
		    </ul>
		</div>
	    </div>
	</nav>

	<div class="containter">
	    <div class="jumbotron countdown">
		<h1>Search Time Remaining</h1>
		<div class="clock timer" ></div>
		<p>We have begun your background search and will notify you once
		   we have either found your results or reached the end of your 
		   search time. We have provided a summary of your search 
		   parameters below. Please stay near your phone or computer 
		   since we will contact you via email. Be sure to have your 
		   Request ID and Email ready when you return for the update 
		   search results.
		</p>
		<h3>Summary of Itinerary</h3>

		<?php
		    define('__ROOT4__',dirname(__FILE__));
		    require_once(__ROOT4__ . '/flight_tracker.php');
		    echo "<pre>";
		    print_r($_POST);
		    echo "</pre>";
		    echo "<br>";
		    $post = $_POST;

		    $last_id = createNewSearch($post);

		    echo "<script>CountdownClock({$post['search_time']})</script>";
		    echo "<div class=\"row\">";
			echo "<div class=\"col-md-3\"></div>";
			echo "<div class=\"col-md-3\">";
			    echo "<ul>";
				echo "<li>Request ID: {$last_id}</li>";
				echo "<li>Email: {$post['email']}</li>";
				echo "<li>Search Time: {$post['search_time']} hours</li>";
				echo "<li>Origin: {$post['origin']}</li>";
				echo "<li>Destination: {$post['destination']}</li>";
			    echo "</ul>";
			echo "</div>";

			echo "<div class=\"col-md-3\">";
			    echo "<ul>";
				echo "<li>Date of Departure: {$post['depart_date']}</li>";
				echo "<li>Date of Return: {$post['return_date']}</li>";

				$type = array(1 => 'adults', 2 => 'children', 3 => 'seniors', 4 => 'seat_infant', 5 => 'lap_infant');
				foreach ($type as $t)
				    if (isset($post[$t]) && $post[$t] > 0)
					echo "<li>Number of {$t}: {$post[$t]}</li>";

				$i = 1;
				foreach ($post['airline'] as $airline)
				{
				    if (count($post['airline']) > 1)
					echo "<li>Airline Preference {$i}: {$airline}</li>";
				    else
					echo "<li>Airline Preference: {$airline}</li>";
				    $i++;
				}

				echo "<li>Maximum Price Limit: \${$post['price']}</li>";
			    echo "</ul>";
			echo "</div>";
			echo "<div class=\"col-md-3\"></div>";
		    echo "</div>";
		    
		    echo "<input id=\"search-submit-button\" class=\"btn btn-info btn-md\" 
			type=\"button\" onclick=\"sendMessage({$last_id},{$post['email']},{$post['search_time']})\" value=\"Begin Search!\"/>";
		?>
	    </div>
	</div>
</html>

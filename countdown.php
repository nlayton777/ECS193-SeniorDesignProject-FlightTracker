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
	<script src="count.js"></script>
	
	<script src="jquery.js"></script>
	<script src="flipclock.min.js"></script>
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

	<!-- this code is for the flipclock -->
	<div class="clock" ></div>


	<!-- end of code for flipclock -->

	    <div class="containter-fluid">
	    <h3>Summary of Itinerary</h3>

	    <?php
		define('__ROOT4__',dirname(__FILE__));
		require_once(__ROOT4__ . '/flight_tracker.php');

		echo "<pre>";
		print_r($_POST);
		echo "</pre>";
		echo "<br>";

		$post = $_POST;
		echo "<script>CountdownClock({$post['search_time']})</script>";
		echo "<div class=\"row\">";
		    echo "<div class=\"col-md-6\">";
			echo "<ul>";
			    echo "<li>Origin: {$post['origin']}</li>";
			    echo "<li>Destination: {$post['destination']}</li>";
			    echo "<li>Date of Departure: {$post['depart_date']}</li>";
			    echo "<li>Date of Return: {$post['return_date']}</li>";
			echo "</ul>";
		    echo "</div>";
		    echo "<div class=\"col-md-6\">";
		    echo "</div>";
		echo "</div>";

		createNewSearch($post)

	    ?>

	</div>
</html>

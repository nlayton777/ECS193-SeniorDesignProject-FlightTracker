<!DOCTYPE html>
<html>
    <head>
	<title>UCD Flight Tracker</title>
	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="stylesheet" href="bootstrap.css"/>
	<script src="jquery-2.1.3.js"/></script>
	<script src="bootstrap.js"></script>
    </head>

    <body>
	<nav class="navbar navbar-inverse ">
	    <div class="container-fluid">
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
			<li class="active">
			    <a href="index.php">Search</a>
			</li>
			<li>
			    <a href="about.php">About</a>
			</li>
		    </ul>
		    <ul class="nav navbar-nav navbar-right">
			<li>
			    <a href="contact.php">Contact</a>
			</li>
		    </ul>
		</div>
	    </div>
	</nav>
	<?php
	    define('__ROOT__',dirname(__FILE__));
	    require_once(__ROOT__ . 
		'/google-api-php-client/src/Google/Service/QPXExpress.php');
	    require_once(__ROOT__ .
		'/google-api-php-client/src/Google/Client.php');

	    $client = new Google_Client();
	    $client->setApplicationName("Flight Tracker");
	    $client->setDeveloperKey("AIzaSyAxaZBEiV9Lwr8tni1sx2V6WVj8LKnrCas");
	    $service = new Google_Service_QPXExpress($client);


	    print_r($_POST);
	    echo '</br>';
	    echo 'Source '.$_POST['source'].'</br>';
	    echo 'Destination '.$_POST['destination'].'</br>';
	    echo 'Departure Date '.$_POST['depart_date'].'</br>';
	    echo 'Return Date '.$_POST['return_date'].'</br>';
	    echo 'Passengers '.$_POST['depart_date'].'</br>';
	    echo 'Price '.$_POST['depart_date'].'</br>';
	    echo 'Airline '.$_POST['depart_date'].'</br>';
	    echo 'Search Window '.$_POST['depart_date'].'</br>';
	    echo 'Email '.$_POST['depart_date'].'</br>';
	    echo 'Phone Number: '.$_POST['depart_date'].'</br>';
	?>
    </body>
</html>

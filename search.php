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

	    // post request from index
	    $post = $_POST;

	    $client = new Google_Client();
	    $client->setApplicationName("Flight Tracker");
	    $client->setDeveloperKey("AIzaSyAxaZBEiV9Lwr8tni1sx2V6WVj8LKnrCas");
	    $service = new Google_Service_QPXExpress($client);
	    $trips = $service->trips;

	    print_r($post);

	    //source
	    if (isset($post['source']))
	    {
	    } 
	    else
	    {
	    }

	    //destination
	    if (isset($post['destination']))
	    {
	    }
	    else
	    {
	    }

	


	    //depart_date; have to account for it one-way or not
	    if (isset($post['depart_date']))
	    {
		// parse departure date
		$depart = explode('/', $post['depart_date']);
		
		// check if one-way
		if (isset($post['one_way']) && $post['one_way'] == 'checked')
		{
		    // good to send query for one-way trip
		} else if (isset($post['return_date'])){
		    $return = explode('/', $post['return_date']);
		    print_r($depart);
		    print_r($return);
		    
		    if (($depart[0] <= $return[0]) && ($depart[1] < $return[1]) && ($depart[2] <= $return[2]))
		    {
			// good to send query for round trip
		    }else{echo "invalid travel dates </br>";}
		}else{echo "Return date NOT set </br>";}
	    }else{echo "Depart date NOT set </br>";}

	    //passengers
	    if (isset($post['passengers']))
	    {
		echo 'passengers is set </br>';
	    }else{echo 'passengers is NOT set </br>';}

	    //price
	    if (isset($post['price']) && ($post['price'] > 0))
	    {
		// good
	    }else{echo 'price is NOT set </br>';}

	    //airline
	    if (isset($post['airline']))
	    {
		echo 'airline is set </br>';
	    }else{echo 'airline is NOT set </br>';}


	    // passengers
	    /*
	    $passengers = new Google_Service_QPXExpress_PassengerCounts();
	    $passengers->setAdultCount(1);
	    
	    // slices/ trips
	    $slice = new Google_Service_QPXExpress_SliceInput();
	    $slice->setDestination('LUX');
	    $slice->setOrigin('FRA');
	    $slice->setDate('2015-09-09');

	    $request = new Google_Service_QPXExpress_TripOptionsRequest();
	    $request->setSolutions(1);
	    $request->setPassengers($passengers);
	    $request->setSlice(array($slice));

	    $searchRequest = new Google_Service_QPXExpress_TripsSearchRequest();
	    $searchRequest->setRequest($request);

	    // search
	    $result = $service->trips->search($searchRequest);
	    print_r($result);
	    */

	    
	    //window
	    if (isset($post['window']) && ($post['window'] >= 0))
	    {
		echo 'window is set </br>';
	    }else{echo 'window is NOT set </br>';}

	    //email
	    if (isset($post['email']))
	    {
		echo 'email is set </br>';
	    }else{echo 'email is NOT set </br>';}

	    //phone
	    if (isset($post['phone']))
	    {
		echo 'phone is set </br>';
	    }else{echo 'phone is NOT set </br>';}
	?>
    </body>
</html>

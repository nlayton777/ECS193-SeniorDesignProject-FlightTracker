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
	    print_r($post);
	    echo "</br>";
	    foreach ($post as $item)
		echo $item . "</br>";
	    isOneWay($post['oneway']);

	    // create client 
	    $client = new Google_Client();
	    $client->setApplicationName("Flight Tracker");
	    $client->setDeveloperKey("AIzaSyAxaZBEiV9Lwr8tni1sx2V6WVj8LKnrCas");

	    // create QPX service
	    $service = new Google_Service_QPXExpress($client);
	    $trips = $service->trips;

	    // create passenger counts
	    $passengers = new Google_Service_QPXExpress_PassengerCounts();

	    // create first and second slices
	    $slice1 = new Google_Service_QPXExpress_SliceInput();
	    $slice2 = new Google_Service_QPXExpress_SliceInput();

	    // set origin information
	    if (isset($post['source'])) {
		$slice1->setOrigin($post['source']);
		if (!isOneWay($post)) // if round-trip 
		    $slice2->setDestination($post['source']);
	    } else {echo "source not set";}

	    // set destination information
	    if (isset($post['destination'])) {
		$slice1->setDestination($post['destination']);
		if (!isOneWay($post)) // if round-trip
		    $slice2->setOrigin($post['destination']);
	    } else {echo "destination not set";}

	    // manage/set date information
	    if (isset($post['depart_date'])){
		$dep = explode('/', $post['depart_date']); // parse departure date
		if (isOneWay($post)) {
		    $dep_date = $dep[2] . "-" . $dep[0] . "-" . $dep[1];
		    print_r($dep_date);
		    $slice1->setDate($dep_date);
		} else if ((!isOneWay($post)) && isset($post['return_date'])){
		    $ret = explode('/', $post['return_date']);
		    if (($dep[0] <= $ret[0]) && ($dep[1] < $ret[1]) 
					     && ($dep[2] <= $ret[2])){ // check if valid dates
			$ret_date = $ret[2] . "-" . $ret[0] . "-" . $ret[1];
			$slice2->setDate($ret_date);
		    }else{echo "invalid travel dates </br>";}
		}else{echo "Return date NOT set </br>";}
	    }else{echo "Depart date NOT set </br>";}

	    //passengers
	    if (isset($post['passengers'])){
		//echo 'passengers is set </br>';
		$passengers->setAdultCount(1);
	    }else{echo 'passengers is NOT set </br>';}

	    //price
	    if (isset($post['price']) && ($post['price'] > 0)){
		echo 'price is set </br>';
	    }else{echo 'price is NOT set </br>';}

	    //airline
	    if (isset($post['airline'])){
		echo 'airline is set </br>';
	    }else{echo 'airline is NOT set </br>';}


	    // create request
	    /*
	    $request = new Google_Service_QPXExpress_TripOptionsRequest();
	    $request->setSolutions(5);
	    $request->setSlice(array($slice1));
	    $request->setPassengers($passengers);
	    $searchRequest = new Google_Service_QPXExpress_TripsSearchRequest();
	    $searchRequest->setRequest($request);
	    print_r($request);
	    */

	    // search
	    //$result = $service->trips->search($searchRequest);
	    //print_r($result);

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

	    function isOneWay(&$val) {
		$rv = false;
		if (isset($val['oneway'])) {
		    echo "oneway: " . $val['oneway'];
		    if ($val['oneway'] == "yes") $rv = true;
		}
		return $rv;
	    }
	?>
    </body>
</html>

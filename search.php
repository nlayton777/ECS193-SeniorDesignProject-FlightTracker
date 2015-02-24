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

	    // create client 
	    $client = new Google_Client();
	    $client->setApplicationName("Flight Tracker");
	    $client->setDeveloperKey("AIzaSyAxaZBEiV9Lwr8tni1sx2V6WVj8LKnrCas");
	    //$client->setDeveloperKey("IzaSyAgWz2bB0YHTwCzWJcS-99pJnzjImluqyg");

	    // create QPX service
	    $service = new Google_Service_QPXExpress($client);

	    // create slices: slice1 for one-way, slice2 for round trip
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

	    // set/manage date information
	    if (isset($post['depart_date'])){
		// parse departure date
		$dep = explode('/', $post['depart_date']); 
		// reformat date
		$dep_date = $dep[2] . "-" . $dep[0] . "-" . $dep[1];
		// set date in request message
		$slice1->setDate($dep_date);
		// if not one-way
		if (!isOneWay($post) && isset($post['return_date'])){
		    // parse departure date
		    $ret = explode('/', $post['return_date']);
		    // reformat date
		    $ret_date = $ret[2] . "-" . $ret[0] . "-" . $ret[1];
		    // set date in request message
		    $slice2->setDate($ret_date);
		}else{echo "Return date NOT set </br>";}
	    }else{echo "Depart date NOT set </br>";}

	    // create passenger counts
	    $passengers = new Google_Service_QPXExpress_PassengerCounts();

	    // set adult count
	    if (isset($post['adults'])) {
		$passengers->setAdultCount($post['adults']);
	    }

	    // set children count
	    if (isset($post['children'])) {
		$passengers->setChildCount($post['children']);
	    }

	    // set senior count
	    if (isset($post['seniors'])) {
		$passengers->setSeniorCount($post['seniors']);
	    }

	    // set seat infant count
	    if (isset($post['seat_infants'])) {
		$passengers->setInfantInSeatCount($post['seat_infants']);
	    }

	    // set lap infant count
	    if (isset($post['lap_infants'])) {
		$passengers->setInfantInLapCount($post['lap_infants']);
	    }

	    // set carrier information
	    if (isset($post['airline'])){
		if ((!in_array("none",$post['airline'],true)) && 
		    (!in_array("--Select an Origin--",$post['airline'],true))){
		    $slice1->setPermittedCarrier($post['airline']);
		    if (!isOneWay($post)) 
			$slice2->setPermittedCarrier($post['airline']); 
	    }}else{echo 'airline is NOT set </br>';}

	    // create request and initialize request
	    $request = new Google_Service_QPXExpress_TripOptionsRequest();
	    $request->setSolutions(1);
	    if (isOneWay($post))
		$request->setSlice(array($slice1));
	    else
		$request->setSlice(array($slice1,$slice2));
	    $request->setPassengers($passengers);
//	    $request->setSaleCountry("US");

	    // create and initialize search request
	    $searchRequest = new Google_Service_QPXExpress_TripsSearchRequest();
	    $searchRequest->setRequest($request);

	    // search
	    $result = $service->trips->search($searchRequest);
	    //print_r($result);
	    $trips = $result->getTrips();
	    print_r($trips);
	    $data = $trips->getData();
	    print_r($data);


	    // manage pricing info
	    $price = 0;
	    if (isset($post['price']) && ($post['price'] > 0)){
		$price = $post['price'];
	    }else{echo 'price is NOT set </br>';}

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
		if (isset($val['one_way'])) 
		    if ($val['one_way'] == "yes") $rv = true;
		return $rv;
	    }
	?>
    </body>
</html>

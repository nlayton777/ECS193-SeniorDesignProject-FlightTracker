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
	    foreach ($post as $item)
	    {
		print_r($item);
		echo "</br>";
	    }

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

	    // set solutions
	    $request->setSolutions(1);
	    
	    // set slices
	    if (isOneWay($post))
		$request->setSlice(array($slice1));
	    else
		$request->setSlice(array($slice1,$slice2));

	    // set passengers
	    $request->setPassengers($passengers);

//	    $request->setSaleCountry("US");

	    // create and initialize search request
	    $searchRequest = new Google_Service_QPXExpress_TripsSearchRequest();
	    $searchRequest->setRequest($request);

	    // search
	    $trips = $service->trips;
	    $result = $trips->search($searchRequest);
	    $trips = $result->getTrips();

	    echo "<div class=\"col-md-2\"></div>";
	    echo "<div class=\"col-md-8\">";

	    // parsing
	    $count1 = 1;
	    $count2 = 1;
	    $count3 = 1;
	    $count4 = 1;
	    $count5 = 1;
	    echo "<h1>TRIP OPTION ARRAY $count1:</h1></br>";
	    foreach (($result->getTrips()->getTripOption()) as $option)
	    {
		echo "</br><h2>TRIP OPTION ARRAY $count1: PRICING $count2:</h2></br>";
		foreach (($option->getPricing()) as $pricing)
		{
		    echo "</br><h2>TRIP OPTION ARRAY $count1: PRICING $count2: TOTALS:</h2></br>";
		    echo "Base Fare Total: " . $pricing->getBaseFareTotal() . "</br>"; 
		    echo "Sale Fare Total: " . $pricing->getSaleFareTotal() . "</br>";
		    echo "Sale Tax Total: " . $pricing->getSaleTaxTotal() . "</br>";
		    echo "Sale Total: " . $pricing->getSaleTotal() . "</br>";
		    $pass = $pricing->getPassengers();
		    echo "For...</br>";
		    echo "......" . $pass->getAdultCount() . " Adults</br>";
		    echo "......" . $pass->getChildCount() . " Children</br>";
		    echo "......" . $pass->getInfantInLapCount() . " Lap Infants</br>";
		    echo "......" . $pass->getInfantInSeatCount() . " Seat Infants</br>";
		    echo "......" . $pass->getSeniorCount() . " Seniors</br>";

		    echo "</br><h2>TRIP OPTION ARRAY $count1: PRICING $count2: FARE $count3</h2></br>";
		    foreach (($pricing->getFare()) as $fare)
		    {
			echo "Fare $count3 ID: " . $fare->getId() . "</br>";
			echo "Fare $count3 Carrier: " . $fare->getCarrier() . "</br>";
			echo "Fare $count3 Origin: " . $fare->getOrigin() . "</br>";
			echo "Fare $count3 Destination: " . $fare->getDestination() . "</br>";
			$count3++;
		    }
		    $count3 = 1;

		    echo "</br><h2>TRIP OPTION ARRAY $count1: PRICING $count2: SEGMENT PRICING $count3</h2></br>";
		    foreach (($pricing->getSegmentPricing()) as $segPrice)
		    {
			echo "Segment Pricing $count3 Fare ID: " . 
			    $segPrice->getFareId() . "</br>";
			echo "Segment Pricing $count3 Segment ID: " . 
			    $segPrice->getSegmentId() . "</br>";
			foreach ($segPrice->getFreeBaggageOption() as $bagOption)
			{
			    echo "Segment Pricing $count3 Free Baggage Option $count4:</br>";  
			    foreach ($bagOption->getBagDescriptor() as $descriptor)
			    {
				echo "Segment Pricing $count3 Free Baggage Option $count4: Bag Descriptor $count5</br>";
				echo "Commercial Name: " . $descriptor->getCommercialName() . "</br>";
				echo "Count: " . $descriptor->getCount() . "</br>";
				echo "Descriptions:</br>";
				foreach ($descriptor->getDescription() as $description)
				{
				    echo $description . "</br>";
				}
			    }
			    $count5 = 0;
			    $count4++;
			}
			$count4 = 1;
			$count3++;
		    }
		    $count3 = 1;
		    $count2++;
		}
		$count2 = 1;

		foreach(($option->getSlice()) as $slice)
		{
		    echo "</br><h2>TRIP OPTION ARRAY $count1: SLICE $count2:<h2></br>";
		    foreach (($slice->getSegment()) as $segment) {
			echo "</br><h3>TRIP OPTION ARRAY $count1: 
			    SLICE $count2: SEGMENT $count3:</h3></br>";
			echo "Segment ID: " . $segment->getId() . "</br>";
			echo "Segment duration: " . $segment->getDuration() . "</br>";
			echo "Segment flight carrier: " . 
			    $segment->getFlight()->getCarrier() . "</br>";
			echo "Segment flight number: " . 
			    $segment->getFlight()->getNumber() . "</br>";

			echo "</br><h4>TRIP OPTION ARRAY $count1: SLICE $count2: 
			SEGMENT $count3: LEG $count4:</h4></br>";
			foreach ($segment->getLeg() as $leg)
			{
			    echo "Leg $count4 ID: " . $leg->getId() . "</br>";
			    echo "Leg $count4 Departure Time: " . $leg->getDepartureTime() . "</br>";
			    echo "Leg $count4 Origin: " . $leg->getOrigin() . "</br>";
			    echo "Leg $count4 Arrival Time: " . $leg->getArrivalTime() . "</br>";
			    echo "Leg $count4 Destination: " . $leg->getDestination() . "</br>";
			    echo "Leg $count4 Duration: " . $leg->getDuration() . "</br>";
			    echo "Leg $count4 Mileage: " . $leg->getMileage() . "</br>";
			    echo "Leg $count4 Meal: " . $leg->getMeal() . "</br>";

			    echo "</br>";
			    $count4++;
			}
			$count4 = 1;
			$count3++;
		    }
		    $count3 = 1;
		    $count2++;
		}
		$count2 = 1;
		$count1++;
	    }

	    echo "</div>";
	    echo "<div class=\"col-md-2\"></div>";


	    // manage pricing info
	    $price = 0;
	    if (isset($post['price']) && ($post['price'] > 0)){
		$price = $post['price'];
	    }else{/*echo 'price is NOT set </br>';*/}

	    //window
	    if (isset($post['window']) && ($post['window'] >= 0))
	    {
		echo 'window is set </br>';
	    }else{/*echo 'window is NOT set </br>';*/}

	    //email
	    if (isset($post['email']))
	    {
		echo 'email is set </br>';
	    }else{/*echo 'email is NOT set </br>';*/}

	    //phone
	    if (isset($post['phone']))
	    {
		echo 'phone is set </br>';
	    }else{/*echo 'phone is NOT set </br>';*/}

	    function isOneWay(&$val) {
		$rv = false;
		if (isset($val['one_way'])) 
		    if ($val['one_way'] == "yes") $rv = true;
		return $rv;
	    }
	?>
    </body>
</html>

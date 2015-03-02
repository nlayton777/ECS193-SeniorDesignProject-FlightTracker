<?php
    define('__ROOT__',dirname(__FILE__));
    require_once(__ROOT__ . 
	'/google-api-php-client/src/Google/Service/QPXExpress.php');
    require_once(__ROOT__ .
	'/google-api-php-client/src/Google/Client.php');

    function isOneWay(&$val) {
	$rv = false;
	if (isset($val['one_way'])) 
	    if ($val['one_way'] == "yes") $rv = true;
	return $rv;
    }

    function printResults($trips, $oneWay)
    {
	$rowCount = 0;
	foreach ($trips->getTripOption() as $option) 
	{
	    echo "<tr>";
		echo "<td>\$" . substr($option->getSaleTotal(),3) . "</td>";
		echo "<td>"; // start nested text

		    // print if one way
		    if (!$oneWay)
			echo "<h5>On the way there...</h5>";

		    foreach ($option->getSlice()[0]->getSegment() as $segment)
		    {
			echo "<p>";
			foreach ($segment->getLeg() as $leg)
			{
			    echo "<strong> ".$leg->getOrigin()." </strong>";
			    $time = explode("T",$leg->getDepartureTime());
			    $time2 = explode("-",$time[1]);
			    echo " ".$time2[0]." ";
			    echo " &rarr; ";
			    echo "<strong> ".$leg->getDestination()." </strong>";
			    $time = explode("T",$leg->getArrivalTime());
			    $time2 = explode("-",$time[1]);
			    echo $time2[0];
			} // end for
			echo "</p>";
		    } // end for

		    if (!$oneWay) {
			echo "<h5>On the way back...</h5>";

			foreach ($option->getSlice()[1]->getSegment() as $segment)
			{
			    echo "<p>";
			    foreach ($segment->getLeg() as $leg)
			    {
				echo "<strong> ".$leg->getOrigin()." </strong>";
				$time = explode("T",$leg->getDepartureTime());
				$time2 = explode("-",$time[1]);
				echo " ".$time2[0]." ";
				echo " &rarr; ";
				echo "<strong> ".$leg->getDestination()." </strong>";
				$time = explode("T",$leg->getArrivalTime());
				$time2 = explode("-",$time[1]);
				echo " ".$time2[0];
			    } // end for
			    echo "</p>";
			} // end for
		    } // end if

		    echo "<div class=\"dropdown\" id=\"row$rowCount\">";

			echo "<table id=\"dropdown-table\">";
			echo "<tr>";
			    echo "<th>Leg</th>";
			    echo "<th>Carrier</th>";
			    echo "<th>Cabin</th>";
			    echo "<th>Aircraft</th>";
			    echo "<th>Meal</th>";
			    echo "<th>Mileage</th>";
			    echo "<th>Duration</th>";
			echo "</tr>";
			foreach ($option->getSlice() as $slice)
			{
			    foreach ($slice->getSegment() as $segment)
			    {
				foreach ($segment->getLeg() as $leg)
				{
				    echo "<tr>";
					echo "<td>";
					echo "<strong> ".$leg->getOrigin()." </strong>";
					echo " &rarr; ";
					echo "<strong> ".$leg->getDestination()." </strong>: ";
					echo "</td>";

					echo "<td>";
					$carrier = $segment->getFlight()->getCarrier(); 
					foreach ($trips->getData()->getCarrier() as $carrier)
					{
					    if ($carrier->getCode() == $segment->getFlight()->getCarrier())
						echo $carrier->getName();
					}
					echo "</td>";

					echo "<td>";
					echo ucfirst(strtolower($segment->getCabin()));
					echo "</td>";
					
					echo "<td>";
					echo $leg->getAircraft();
					echo "</td>";

					echo "<td>";
					$meal = $leg->getMeal();
					if (isset($meal))
					    echo $meal;
					else
					    echo "None";
					echo "</td>";

					echo "<td>";
					echo $leg->getMileage();
					echo " miles</td>";

					echo "<td>";
					echo $leg->getDuration();
					echo " minutes</td>";
				    echo "</tr>";
				} // end for
			    } // end for
			} // end for
			echo "</table>";
		    echo "</div>";  // end dropdown div
		echo "</td>";	// end td with table inside
		echo "<td class=\"expandButton\">";
		    echo "<input type=\"button\" id=\"btnExpCol$rowCount\" class=\"btn btn-info search\" 
			onclick=\"Expand()\" value=\" Expand \"/>";
		echo "</td>";
	    echo "</tr>";
	    $rowCount++;
	}
	return ($rowCount);
    }

    function getResults(&$post) {
	// create client 
	$client = new Google_Client();
	$client->setApplicationName("Flight Tracker");
	// nick
	//$client->setDeveloperKey("AIzaSyAxaZBEiV9Lwr8tni1sx2V6WVj8LKnrCas");
	// rupali
	//$client->setDeveloperKey("AIzaSyAgWz2bB0YHTwCzWJcS-99pJnzjImluqyg");
	// kirsten
	//$client->setDeveloperKey("AIzaSyB-cjP2Pfmkq_50JqmB8TcRx5sVgAWW5_Y");
	// nina
	$client->setDeveloperKey("AIzaSyDsAGm880MwQmxzceJPEfMLwEE9W84wl8s");

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
	    }else{/*echo "Return date NOT set </br>";*/}
	}else{/*echo "Depart date NOT set </br>";*/}

	// create passenger counts
	$passengers = new Google_Service_QPXExpress_PassengerCounts();

	// set adult count
	if (isset($post['adults'])) $passengers->setAdultCount($post['adults']);

	// set children count
	if (isset($post['children'])) $passengers->setChildCount($post['children']);

	// set senior count
	if (isset($post['seniors'])) $passengers->setSeniorCount($post['seniors']);

	// set seat infant count
	if (isset($post['seat_infants'])) $passengers->setInfantInSeatCount($post['seat_infants']);

	// set lap infant count
	if (isset($post['lap_infants'])) $passengers->setInfantInLapCount($post['lap_infants']);

	// set carrier information
	if (isset($post['airline'])){
	    if ((!in_array("none",$post['airline'],true)) && 
		(!in_array("--Select an Origin--",$post['airline'],true))){
		$slice1->setPermittedCarrier($post['airline']);
		if (!isOneWay($post)) 
		    $slice2->setPermittedCarrier($post['airline']); 
	}}else{/*echo 'airline is NOT set </br>';*/}

	// create request and initialize request
	$request = new Google_Service_QPXExpress_TripOptionsRequest();

	// set solutions
	$request->setSolutions(20);
	
	// set slices
	if (isOneWay($post))
	    $request->setSlice(array($slice1));
	else
	    $request->setSlice(array($slice1,$slice2));

	// set passengers
	$request->setPassengers($passengers);

	// $request->setSaleCountry("US");

	// create and initialize search request
	$searchRequest = new Google_Service_QPXExpress_TripsSearchRequest();
	$searchRequest->setRequest($request);

	// search
	$trips = $service->trips;
	$result = $trips->search($searchRequest);
	return($result);
    }
?>
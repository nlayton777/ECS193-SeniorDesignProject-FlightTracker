<?php
    define('__ROOT__',dirname(__FILE__));

    /*		NEED THESE FILES FOR QPX API		*/  
    require_once(__ROOT__ . 
	'/google-api-php-client/src/Google/Service/QPXExpress.php');
    require_once(__ROOT__ .
	'/google-api-php-client/src/Google/Client.php');

    function isOneWay(&$val) {
	$rv = false;
	if (isset($val['one_way'])) 
	    if ($val['one_way'] == "yes") $rv = true;
	return $rv;
    } // isOneWay($val)

    function printResults($trips, $post)
    {
	$rowCount = 0;
	$options = $trips->getTripOption();
	if (isset($options)) 
	{
	    $multPass = false;
	    if ($post['adults'] > 1 || $post['children'] > 1 || $post['seniors'] > 1 || 
		$post['seat_infants'] > 1 || $post['lap_infants'] > 1)
		$multPass = true;

	    // print headers
	    echo "<table id=\"results\" class=\"table table-hover\" 
		style=\"background-color: rgba(150, 150, 150, 0)\" align=\"center\">";
		echo "<tr>";
		    echo "<th id=\"price\">Total ";
		    if ($multPass)
			echo "Group ";
		    echo "Price</th>";
		    echo "<th id=\"it\">Itinerary</th>";
		    echo "<th id=\"info\">More Info</th>";
		echo "</tr>";
	    foreach ($options as $option) 
	    {
		echo "<tr>";
		    echo "<td>\$" . substr($option->getSaleTotal(),3) . "</td>";
		    echo "<td>"; // start nested text

			// print if one way
			if (!isOneWay($post))
			{
			    echo "<div id=\"on-the-way-there\">";
			    echo "<h5>On the way there...</h5>";
			}

			foreach ($option->getSlice()[0]->getSegment() as $segment)
			{
			    foreach ($segment->getLeg() as $leg)
			    {
				echo "<p>";
				echo "<strong> ".$leg->getOrigin()." </strong>";
				$time = explode("T",$leg->getDepartureTime());
				$time2 = explode("-",$time[1]);
				echo " ".$time2[0]." ";
				echo " &rarr; ";
				echo "<strong> ".$leg->getDestination()." </strong>";
				$time = explode("T",$leg->getArrivalTime());
				$time2 = explode("-",$time[1]);
				echo $time2[0];
				echo "</p>";
			    } // end for
			} // end for

			if (!isOneWay($post)) {
			    echo "</div>";
			    echo "<div id=\"on-the-way-back\">";
			    echo "<h5>On the way back...</h5>";

			    foreach ($option->getSlice()[1]->getSegment() as $segment)
			    {
				foreach ($segment->getLeg() as $leg)
				{
				    echo "<p>";
				    echo "<strong> ".$leg->getOrigin()." </strong>";
				    $time = explode("T",$leg->getDepartureTime());
				    $time2 = explode("-",$time[1]);
				    echo " ".$time2[0]." ";
				    echo " &rarr; ";
				    echo "<strong> ".$leg->getDestination()." </strong>";
				    $time = explode("T",$leg->getArrivalTime());
				    $time2 = explode("-",$time[1]);
				    echo " ".$time2[0];
				    echo "</p>";
				} // end for
			    } // end for
			    echo "</div>";
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
	    } // end foreach(Trips)
	    echo "</table>";
	} else
	{
	    echo "<h2>Sorry, we could not find any flights that match your".
		" preferences. We suggest broadening your search parameters".
		" to improve your chances at finding results.</h2>";
	} // end if/else
	return ($rowCount);
    } // printResults($post)

    function getResults(&$post,$num) {
	// create client 
	$client = new Google_Client();
	$client->setApplicationName("Flight Tracker");
	// nick
	//$client->setDeveloperKey("AIzaSyAxaZBEiV9Lwr8tni1sx2V6WVj8LKnrCas");
	// rupali
	$client->setDeveloperKey("AIzaSyAgWz2bB0YHTwCzWJcS-99pJnzjImluqyg");
	// kirsten
	//$client->setDeveloperKey("AIzaSyB-cjP2Pfmkq_50JqmB8TcRx5sVgAWW5_Y");
	// nina
	//$client->setDeveloperKey("AIzaSyDsAGm880MwQmxzceJPEfMLwEE9W84wl8s");

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
	/*
	    if ((!in_array("none",$post['airline'],true)) && 
		(!in_array("--Select an Origin--",$post['airline'],true))){
		$slice1->setPermittedCarrier($post['airline']);
		*/
	    $temp1 = array();
	    $temp2 = array();
	    foreach ($post['airline'] as $airline)
	    {
		if ($airline != "none")
		{
		   $temp1[] = $airline; 
		    if (!isOneWay($post)) 
			$temp2[] =$airline;
		}
	    }
	    $slice1->setPermittedCarrier($temp1); 
	    $slice2->setPermittedCarrier($temp2); 
	}else{/*echo 'airline is NOT set </br>';*/}

	// create request and initialize request
	$request = new Google_Service_QPXExpress_TripOptionsRequest();

	// set solutions
	$request->setSolutions($num);
	$request->setMaxPrice("USD".$post['price']);
	
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
    } // getResults($post)

    function createNewSearch(&$post)
    {
	/*		NEED THIS FILE FOR DATABASE	    */
	require_once('login.php');

	// connect to database
	$connection = new mysqli ($db_hostname, $db_username);
	if($connection->connect_error) die($connection->connect_error);
	mysqli_select_db($connection,"flight_tracker");

	// add user info to db
	$d_date = explode("/",$post['depart_date']);
	$d_date = implode("-",array($d_date[2],$d_date[0],$d_date[1]));
	if ($post['return_date'] != "NULL")
	{
	    $r_date = explode("/",$post['return_date']);
	    $r_date = implode("-",array($r_date[2],$r_date[0],$r_date[1]));
	    $r_date = "'".$r_date."'";
	} else
	    $r_date = "NULL";
	$query3 = "INSERT INTO searches ".
		  "(email,origin,destination,depart_date,return_date,adults,".
		  "children,seniors,seat_infant,lap_infant,price,current,end,lowest_price) ".
		  "VALUES (".
			"'{$post['email']}',".
			"'{$post['origin']}','{$post['destination']}',".
			"'{$d_date}',{$r_date},".
			"{$post['adults']},{$post['children']},".
			"{$post['seniors']},{$post['seat_infant']},".
			"{$post['lap_infant']},{$post['price']},".
			"now(),'".getEndTime($post['search_time'])."',".
			$post['price'].
		  ");";
		  /*
	echo $query3;
	echo "<br>";
	*/
	$result3 = $connection->query($query3);
	if (!$result3) die($connection->error);

	$last_id = $connection->insert_id;
	if (isset($post['airline']))
	{
	    $query5 = "INSERT INTO airlines ".
		      "(search_id,email,airline) ".
		      "VALUES ";

	    $last = end($post['airline']);
	    foreach ($post['airline'] as $airline)
		if ($airline != $last)
		    $query5 .= "({$last_id},'{$post['email']}','{$airline}'), ";
	    $query5 .= "({$last_id},'{$post['email']}','{$airline}');";
	    $result5 = $connection->query($query5);
	    if (!$result5) die($connection->error);
	} // foreach(airline)

	$connection->close();
	return $last_id;
    } // createNewSearch($post)

    function getEndTime($search_time)
    //{ return date('Y-m-d H:i:s', time() + ($search_time * 60 * 60));} 
    { return date('Y-m-d H:i:s',time() + (2 * 60)); }
    // getEndTime($search_time)

    function getConfirmationEmail(&$post,$userSource,$userDestination,$userID)
    {
	$returnArr = array(
		    'from'    => 'UCD Flight Tracker <ucd.flight.tracker@gmail.com>',
		    'to'      => '<'.$post['email'].'>',
		    'subject' => 'Thank you for using UCD Flight Tracker ',
		    'html'    => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				  <html xmlns="http://www.w3.org/1999/xhtml" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
					<head>
					    <meta name="viewport" content="width=device-width" />
					    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
					    <title>UCD Flight Tracker</title>
					</head>
					<body itemscope itemtype="http://schema.org/EmailMessage" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6">
					    <table class="body-wrap" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6">
						<tr style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
						    <td style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top"></td>
						    <td class="container" width="600" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; width: 100% !important; margin: 0 auto;" valign="top">
						    <div class="content" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 10px;">
						    <table class="main" width="100%" cellpadding="0" cellspacing="0" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;" bgcolor="#fff">
							<tr style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
							    <td class="alert alert-warning" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 30px; vertical-align: top; color: #fff; font-weight: 500; text-align: center; border-radius: 3px 3px 0 0; background-color: #f3a56f; margin: 0; padding: 20px;" align="center" bgcolor="#f3a56f" valign="top">
							    It\'s Time to Fly!
							    </td>
							</tr>
							<tr style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
							    <td class="content-wrap" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 10px;" valign="top">
								<table width="100%" cellpadding="0" cellspacing="0" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
								    <tr style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
									<td class="image" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
									    <img src="https://download.unsplash.com/photo-1422464804701-7d8356b3a42f" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; max-width: 100%; margin: 0;" />
									</td>
								    </tr>
									<td class="content-block" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
									    Thank you for submitting a search to UCD Flight Tracker. 
									</td>
								    </tr>
								    <tr style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
									<td class="content-block" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
									    Our search bot will continue searching for your flight from '.$userSource.' to ' .$userDestination. '. Your request ID is ' .$userID. '. Please make note of your ID so that you can check the progress of your search and check your email for a notification from us when we find you the perfect flight! 
									</td>
								    </tr>
								    <tr style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
									<td class="content-block-button" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;" align="center" valign="top">
									    <a href="http://www.mailgun.com" class="btn-primary" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; color: #FFF; text-decoration: none; line-height: 2; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; text-transform: capitalize; background-color: #5e4763; margin: 0; border-color: #5e4763; border-style: solid; border-width: 10px 20px;">Pack Your Bags!</a>
									</td>
								    </tr>
								    <tr style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
									<td class="content-block-button" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;" align="center" valign="top">
									    Thanks for choosing UCD Flight Tracker!
									</td>
								    </tr>
								</table>
							    </td>
							</tr>
						    </table>
						    <div class="footer" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; clear: both; color: #999; margin: 0; padding: 20px;">
							<table width="100%" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
							    <tr style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
								<td class="aligncenter content-block" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 12px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;" align="center" valign="top"><a href="http://www.mailgun.com" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 12px; color: #999; text-decoration: underline; margin: 0;">Unsubscribe</a> from these alerts.</td>
							    </tr>
							</table>
						    </div>
						</div>
					    </td>
					    <td style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"></td>
					</tr>
				    </table>
				    <style type="text/css">
					img { max-width: 100% !important; }
					body { -webkit-font-smoothing: antialiased !important; -webkit-text-size-adjust: none !important; width: 100% !important; height: 100% !important; line-height: 1.6 !important; }
					body { background-color: #f6f6f6 !important; }
				    </style>
				</body>
			    </html>');
	return $returnArr;
    } // getSearchConfirmation()

    function getResultsEmail($userEmail, $userID, $userSource, $userDestination)
    {
	$resultArr = array(
		'from'    => 'UCD Flight Tracker <ucd.flight.tracker@gmail.com>', 'to'      => '<'.$userEmail.'>',
		'subject' => 'We found a flight for you!  ',
		'html'    => '
		    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		    <html xmlns="http://www.w3.org/1999/xhtml" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
		    <head>
		    <meta name="viewport" content="width=device-width" />
		    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		    <title>UCD Flight Tracker</title>

		    </head>

		    <body itemscope itemtype="http://schema.org/EmailMessage" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6">

		    <table class="body-wrap" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6">
			<tr style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
			    <td style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top"></td>
			    <td class="container" width="600" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; width: 100% !important; margin: 0 auto;" valign="top">
				<div class="content" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 10px;">
				    <table class="main" width="100%" cellpadding="0" cellspacing="0" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;" bgcolor="#fff">
					<tr style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
					    <td class="alert alert-warning" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 30px; vertical-align: top; color: #fff; font-weight: 500; text-align: center; border-radius: 3px 3px 0 0; background-color: #f3a56f; margin: 0; padding: 20px;" align="center" bgcolor="#f3a56f" valign="top">
						It\'s Time to Fly!
					    </td>
					</tr>
					<tr style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
					    <td class="content-wrap" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 10px;" valign="top">
						<table width="100%" cellpadding="0" cellspacing="0" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
						    <tr style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
							<td class="image" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
							    <img src="https://download.unsplash.com/photo-1422464804701-7d8356b3a42f" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; max-width: 100%; margin: 0;" />
							    </td>
						    </tr>
							<td class="content-block" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
							    We found the perfect flight for you!                                                            </td>
						    </tr>
						    <tr style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
							<td class="content-block" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
							    Our search bot found the perfect flight for you from'.$userSource.' to ' .$userDestination. '. Please act on the following information quickly as we are not certain how long these deals will last for. Use your request ID and email to login to our page to view your flight results. As a reminder your request ID was' .$userID.'. We hope you enjoy your flight.
							</td>
						    </tr>
						    <tr style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
							<td class="content-block-button" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;" align="center" valign="top">
							    <a href="http://www.mailgun.com" class="btn-primary" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; color: #FFF; text-decoration: none; line-height: 2; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; text-transform: capitalize; background-color: #5e4763; margin: 0; border-color: #5e4763; border-style: solid; border-width: 10px 20px;">Pack Your Bags!</a>
							</td>
						    </tr>
						    <tr style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
							<td class="content-block-button" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;" align="center" valign="top">
							    Thanks for choosing UCD Flight Tracker!
							</td>
						    </tr>
						</table>
					    </td>
					</tr>
				    </table>
				    <div class="footer" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; clear: both; color: #999; margin: 0; padding: 20px;">
					<table width="100%" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
					    <tr style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
						<td class="aligncenter content-block" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 12px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;" align="center" valign="top"><a href="http://www.mailgun.com" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 12px; color: #999; text-decoration: underline; margin: 0;">Unsubscribe</a> from these alerts.</td>
					    </tr>
					</table>
				    </div></div>
			    </td>
			    <td style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"></td>
			</tr>
		    </table>


		    <style type="text/css">
		    img { max-width: 100% !important; }
		    body { -webkit-font-smoothing: antialiased !important; -webkit-text-size-adjust: none !important; width: 100% !important; height: 100% !important; line-height: 1.6 !important; }
		    body { background-color: #f6f6f6 !important; }
		    </style>
		    </body>
		    </html>'
			    );
	return $resultsArr;
    } // getResultsEmail()
?>

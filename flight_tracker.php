<?php
define('__ROOT__',dirname(__FILE__));
require_once(__ROOT__ . 
    '/google-api-php-client/src/Google/Service/QPXExpress.php');
require_once(__ROOT__ .
    '/google-api-php-client/src/Google/Client.php');

/*
 * this function
 * checks the value 
 * to see if the user 
 * requested a one-way
 * trip on index.php
 */
function isOneWay(&$val) 
{
    $rv = false;
    if ($val['one_way'] == true || 
	$val['one_way'] == 1) 
	$rv = true;
    return $rv;
} // isOneWay($val)

/*
 * this function generates
 * html code to display
 * the table of results in search.php
 * and results.php
 */
function printResults($trips, $post)
{
    /*
     * begin booking url
     * for booking flights 
     * from our search or results
     * pages
     */
    $urlString = "https://www.google.com/flights/#search;f={$post['source']};".
		 "t={$post['destination']}";
    $originalDepart = $post['depart_date'];
    $newDepart = date("Y-m-d", strtotime($originalDepart));
    $urlString .= ";d={$newDepart}";

    if(!isOneWay($post))
    {
	$originalReturn = $post['return_date'];
	$newReturn = date("Y-m-d", strtotime($originalReturn));
	$urlString .= ";r={$newReturn};";
    } else
	$urlString .= ";tt=o;";

    /*
     * if there are search results,
     * then begin making the results table
     */
    $rowCount = 0;
    $options = $trips->getTripOption();
    if (isset($options)) 
    {
	/*
	 * check if there
	 * were multiple passengers
	 * in the trip
	 */
	$multPass = false;
	if (($post['adults'] + $post['children'] + 
	$post['seniors'] + $post['seat_infants'] + 
	$post['lap_infants']) > 1)
	    $multPass = true;

	/*
	 * start printing the table
	 */
	echo <<<_HEADERS
	<table id="results" class="table table-hover" style="background-color: rgba(150, 150, 150, 0)" align="center">
	<tr id="headers">
	<th id="price">Total
_HEADERS;
	if ($multPass) echo " Group ";
	echo <<<_HEADERS2
	Price</th>
	<th id="it">Itinerary</th>
	<th id="info" colspan="2">More Info</th>
	</tr>
_HEADERS2;
	/*
	 * scan the array of trip options
	 * provided from the search request
	 */
	foreach ($options as $option) 
	{
	    /*
	     * parse the sale total amount
	     */
	    $sub = substr($option->getSaleTotal(),3);
	    echo <<<_STUFF
	    <tr>
	    <td>\${$sub}</td>
	    <td>
_STUFF;
	    /*
	     * print description
	     * based on whether or 
	     * not the user requested
	     * a one-way trip
	     */
	    if (!isOneWay($post))
	    {
		echo <<<_STUFF2
		<div class="on-the-way-there">
		<h5>On the way there...</h5>
_STUFF2;
	    } // if

	    /*
	     * scan the array of segments
	     * in the first slice of the 
	     * trip (there might only
	     * be 1 slice)
	     */
	    foreach ($option->getSlice()[0]->getSegment() as $segment)
	    {
		/*
		 * scan the array of legs
		 * within the current segment
		 */
		foreach ($segment->getLeg() as $leg)
		{
		    /*
		     * get the origin and destination
		     * and then parse the departure and
		     * arrival times
		     */
		    $orig = $leg->getOrigin();
		    $dest = $leg->getDestination();
		    $time1 = explode("-",explode("T",$leg->getDepartureTime())[1])[0];
		    $time2 = explode("-",explode("T",$leg->getArrivalTime())[1])[0];
		    echo <<<_STUFF3
		    <p>
			<strong>{$orig}</strong> {$time1} 
			&rarr; 
			<strong>{$dest}</strong> {$time2}
		    </p>
_STUFF3;
		} // foreach
	    } // foreach

	    /*
	     * if the trip
	     * is a round-trip,
	     * then also scan the 
	     * same things in the 
	     * second slice
	     */
	    if (!isOneWay($post)) 
	    {
		echo <<<_STUFF4
		</div>
		<div class="on-the-way-back">
		    <h5>On the way back...</h5>
_STUFF4;
		
		/*
		 * scan the array of segments within
		 * the second slice of the trip
		 */
		foreach ($option->getSlice()[1]->getSegment() as $segment)
		{
		    /*
		     * scan the array of legs within
		     * the second slice of the trip
		     */
		    foreach ($segment->getLeg() as $leg)
		    {
			/*
			 * get the origin and destination
			 * and then parse the departure and
			 * arrival times
			 */
			$orig = $leg->getOrigin();
			$dest = $leg->getDestination();
			$time1 = explode("-",explode("T",$leg->getDepartureTime())[1])[0];
			$time2 = explode("-",explode("T",$leg->getArrivalTime())[1])[0];
			echo <<<_STUFF5
			<p>
			    <strong>{$orig}</strong> {$time1} 
			    &rarr; 
			    <strong>{$dest}</strong> {$time2}
			</p>
_STUFF5;
		    } // foreach
		} // foreach
		echo "</div>";
	    } // end if

	    /*
	     * generate the dropdown table
	     * that is initially hidden and
	     * can be expanded to show
	     * additional information
	     */
	    echo <<<_STUFF6
	    <div class="dropdown" id="row{$rowCount}">
		<table class="dropdown-table">
		    <tr>
			<th>Leg</th>
			<th>Carrier</th>
			<th>Cabin</th>
			<th>Aircraft</th>
			<th>Meal For Purchase</th>
			<th>Mileage</th>
			<th>Duration</th>
			<th>Flight #</th>
		    </tr>

_STUFF6;
	    /* 
	     * again scan the slices, segments,
	     * and legs to display additional information
	     */
	    $urlEnd = $urlString . "sel=";
	    $first = true;
	    foreach ($option->getSlice() as $slice)
	    {
		if(!$first)
		{
		    $urlEnd = substr($urlEnd, 0, -1);   
		    $urlEnd .= ","; 
		} // if
		$first = false;

		foreach ($slice->getSegment() as $segment)
		{         
		    foreach ($segment->getLeg() as $leg)
		    {
			/*
			 * $orig and $dest are for the 
			 * particular leg of the flight 
			 * and $origin and $destination 
			 * are for the whole flight
			 */
			$orig = $leg->getOrigin();
			$dest = $leg->getDestination();
			$origin = $post['source'];
			$destination = $post['destination'];

			echo <<<_STUFF7
			<tr>
			    <td>
			    <strong>{$orig}</strong>
			     &rarr; 
			    <strong>{$dest}</strong>: 
			    </td>
			    <td>
_STUFF7;

			/*
			 * get airline info to be
			 * displayed in the dropdown table
			 */
			$carrier = $segment->getFlight()->getCarrier();
			$previous = NULL; 
			foreach ($trips->getData()->getCarrier() as $carrier)
			{
			    if ($carrier->getCode() == $segment->getFlight()->getCarrier())
			    {
				$name = explode(" ", $carrier->getName());
				if (isset($name[1]))
				    echo implode(" ", array($name[0], $name[1]));
				else 
				    echo $name[0];
				$site = $carrier->getCode();
				$oper = $leg->getOperatingDisclosure();
				if ((strpos($oper,'AMERICAN AIRLINES') !== false)) 
				{
				    if($site !== "AS") $site = "AA";
				} else if ((strpos($oper,'AMERICAN EAGLE') !== false)) 
				{
				    if($site !== "AS") $site = "AA";
				} else if ((strpos($oper,'US AIRWAYS') !== false)) 
				{
				    if($site !== "AS") $site = "US";
				} // else if
			    } // if
			} // foreach
   
			/*
			 * get the cabin and 
			 * aircraft information to be
			 * dispayed in the dropdown
			 */
			$cab = ucfirst(strtolower($segment->getCabin()));
			$lg = $leg->getAircraft();
                        $carrier = $segment->getFlight()->getCarrier();
                        $previous = NULL; 
                        foreach ($trips->getData()->getCarrier() as $carrier)
                        {
			    //GET THE AIRLINES      
			    if ($carrier->getCode() == $segment->getFlight()->getCarrier())
			    {
				echo $carrier->getName();
				$site = $carrier->getCode();
				$oper = $leg->getOperatingDisclosure();
				if ((strpos($oper,'AMERICAN AIRLINES') !== false)) 
				{
				    if($site !== "AS")
					$site = "AA";
				} else if ((strpos($oper,'AMERICAN EAGLE') !== false)) 
				{
				    if($site !== "AS")
					$site = "AA";
				} else if ((strpos($oper,'US AIRWAYS') !== false)) 
				{
				    if($site !== "AS")
					$site = "US";
				} // else if
			    } // if
			} // foreach code/carrier
                       
                        $cab = ucfirst(strtolower($segment->getCabin()));
                        $lg = $leg->getAircraft();

                        echo <<<_STUFF8
                        </td>
                        <td>{$cab}</td>
                        <td>{$lg}</td>
                        <td>
_STUFF8;
			/*
			 * get meal information
			 * to be displayed in
			 * the dropdown table,
			 * also, parse it to make
			 * it more compact
			 * in the table
			 */
			$meal = $leg->getMeal();
			if (isset($meal))
			{
			    if (strpos($meal, " for ") != FALSE)
			    {
				if(strpos($meal, " and ") != FALSE)
				{
				    $meal = explode(" and ", $meal);
				    $meal =  implode("+", array($meal[0], $meal[1]));
				    $meal = explode(" for ", $meal);
				    echo $meal[0];
				} else
				{
				    $meal = explode(" for ", $meal);
				    echo $meal[0];
				} // if else
			    } // if 
			} // if meal set
			else
			    echo "None";

			/*
			 * get mileage and duration
			 * to be displayed in the dropdown
			 * table
			 */
			$mlg = $leg->getMileage();
			$dur = $leg->getDuration();
			$segFlightNum = $segment->getFlight()->getNumber();
    
			/* continue making the booking url */
			$urlEnd .= "{$orig}{$dest}0{$site}{$segFlightNum}-";
			
			echo <<<_STUFF9
			    </td>
			    <td>{$mlg}</td>
			    <td>{$dur} mins</td>
			    <td>{$site}{$segFlightNum}</td>
			</tr>
_STUFF9;
		    } // foreach leg
		} // foeach segment
	    } // foreach slice

	    $urlEnd = substr($urlEnd, 0, -1);
	    if($site == "AS") $urlEnd = $urlString . "a=AS";

	    /*
	     * finish displaying the final column
	     * of the table with Hide/Show button
	     * and Book It button
	     */
	    echo <<<_STUFF10
		    </table>
		</div>  
	    </td>   
	    <td class="expandButton">
		<input type="button" id="btnExpCol{$rowCount}" class="btn btn-info search" 
		 onclick="Expand()" value="Show"/>
	    </td>
	    <td class="Book It">
		<input type="button" id="bookit" class="btn btn-info search" value="Book It"
		 onclick="window.open('{$urlEnd}')"/>
	    </td>
	    </tr>
_STUFF10;
	    $rowCount++;
	    $urlEnd = "";
	} // end foreach(Trips)
	echo "</table>";
    } else
    {
	/*
	 * if no flights were found
	 * then tell the user
	 */
	echo <<<_STUFF11
	    <h2>
		Sorry, we could not find any flights that match your
		preferences. We suggest broadening your search parameters
		to improve your chances at finding results.
	    </h2>
_STUFF11;
    } // if/else
    return ($rowCount);
} // printResults($post)

/*
 * this function performs a
 * query to the Google QPX API
 * and retrieves and returns an object
 * that contains the flight 
 * information for the parameters
 * that the user answered.
 */
function getResults(&$post,$num, $keyNum) 
{
    // create client 
    $client = new Google_Client();

    //$client->setApplicationName("Flight Tracker");
    $x = $keyNum % 5;
    switch ($x)
    {
	case 0:
	    // nick
	    $client->setDeveloperKey("AIzaSyAxaZBEiV9Lwr8tni1sx2V6WVj8LKnrCas");
	    break;
	case 1:
	    // rupali
	    $client->setDeveloperKey("AIzaSyAgWz2bB0YHTwCzWJcS-99pJnzjImluqyg");
	    break;
	case 2:
	    // kirsten
	    $client->setDeveloperKey("AIzaSyB-cjP2Pfmkq_50JqmB8TcRx5sVgAWW5_Y");
	    break;
	case 3:
	    // nina
	    $client->setDeveloperKey("AIzaSyDsAGm880MwQmxzceJPEfMLwEE9W84wl8s");
	    break;
	case 4:
	    // flight tracker
	    $client->setDeveloperKey("AIzaSyCCS0WHeRJDiRZmxfTmqA9jCbETtMIvAUg");
	    break;
	case 5:
	    // rupali's other
	    $client->setDeveloperKey("AIzaSyAlIaLcBQiyOpWVTPSJC-fOJz_2veF94Zw");
	    break;
    } // switch
    // create QPX service
    $service = new Google_Service_QPXExpress($client);

    // create slices: slice1 for one-way, slice2 for round trip
    $slice1 = new Google_Service_QPXExpress_SliceInput();
    $slice2 = new Google_Service_QPXExpress_SliceInput();

    // set origin information
    if (isset($post['source'])) 
    {
	$slice1->setOrigin($post['source']);
	if (!isOneWay($post)) // if round-trip 
	$slice2->setDestination($post['source']);
    } else {/*echo "source not set";*/}

    // set destination information
    if (isset($post['destination'])) 
    {
	$slice1->setDestination($post['destination']);
	if (!isOneWay($post)) // if round-trip
	    $slice2->setOrigin($post['destination']);
    } else {/*echo "destination not set";*/}

    // set/manage date information
    if (isset($post['depart_date']))
    {
	// parse departure date
	$dep = explode('/', $post['depart_date']); 
	// reformat date
	$dep_date = $dep[2] . "-" . $dep[0] . "-" . $dep[1];
	// set date in request message
	$slice1->setDate($dep_date);
	// if not one-way
	if (!isOneWay($post) && isset($post['return_date']))
	{
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
    if (isset($post['airline']))
    {
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
	} // foreach airline

	$slice1->setPermittedCarrier($temp1); 
	$slice2->setPermittedCarrier($temp2); 
    } else {/*echo 'airline is NOT set </br>';*/}

    // create request and initialize request
    $request = new Google_Service_QPXExpress_TripOptionsRequest();
    // set solutions
    $request->setSolutions($num);
    $request->setMaxPrice("USD".$post['price']);

    // set slices
    if (isOneWay($post)) {$request->setSlice(array($slice1));}
    else {$request->setSlice(array($slice1,$slice2));}

    // set passengers
    $request->setPassengers($passengers);

    // create and initialize search request
    $searchRequest = new Google_Service_QPXExpress_TripsSearchRequest();
    $searchRequest->setRequest($request);

    // search
    $trips = $service->trips;
    $result = $trips->search($searchRequest);

    return($result);
} // getResults()

/*
 * this function creates a new
 * database entry for a newly created
 * background search request
 */
function createNewSearch(&$post)
{
    require_once('login.php');

    /* connect to database */
    $connection = new mysqli ($db_hostname, $db_username);
    if($connection->connect_error) die($connection->connect_error);
    $connection->select_db("flight_tracker");

    /*
     * parse date information to
     * be entered into the DB
     */
    $d_date = explode("/",$post['depart_date']);
    $d_date = "'".implode("-",array($d_date[2],$d_date[0],$d_date[1]))."'";
    if ($post['return_date'] != "NULL")
    {
	$r_date = explode("/",$post['return_date']);
	$r_date = implode("-",array($r_date[2],$r_date[0],$r_date[1]));
	$r_date = "'".$r_date."'";
    } else {$r_date = "NULL";}

    /*
     * determine the point in time
     * that is the end of the search
     */
    $endTime = getEndTime($post['search_time']);

    /* perform insertion query */
    $query3 = <<<_QUERY3
	INSERT INTO searches (
	    email,
	    origin,
	    destination,
	    depart_date,
	    return_date,
	    adults,
	    children,
	    seniors,
	    seat_infant,
	    lap_infant,
	    price,
	    current,
	    end,
	    lowest_price,
	    one_way
	) VALUES (
	    '{$post['email']}',
	    '{$post['origin']}',
	    '{$post['destination']}',
	    {$d_date},
	    {$r_date},
	    {$post['adults']},
	    {$post['children']},
	    {$post['seniors']},
	    {$post['seat_infant']},
	    {$post['lap_infant']},
	    {$post['price']},
	    now(),
	    '{$endTime}',
	    {$post['price']},
	    {$post['one_way']}
	);
_QUERY3;

    /* execute query and check result */
    $result3 = $connection->query($query3);
    if (!$result3) die($connection->error);

    /*
     * also enter flight information
     * for this user into the Airlines 
     * table in DB
     */
    $last_id = $connection->insert_id;
    if (isset($post['airline']))
    {
	/* build query */
	$query4 = <<<_QUERY4
	    INSERT INTO airlines 
		(search_id,email,airline) 
	    VALUES 
_QUERY4;

	$last = end($post['airline']);
	$flag = true;
	foreach ($post['airline'] as $airline)
	{
	    if ($flag)
	    {
		$query4 .= "({$last_id},'{$post['email']}','{$airline}') ";
		$flag = false;
	    } else 
		$query4 .= ",({$last_id},'{$post['email']}','{$airline}')";
	} // foreach airline
	$query4 .= ";";

	/* 
	 * execute query 
	 * and check conection 
	 */
	$result4 = $connection->query($query4);
	if (!$result4) die($connection->error);
    } // if airline set

    $connection->close();
    return $last_id;
} // createNewSearch()

/*
 * the macro below is used in the functions to 
 * follow to return the user to the website with 
 * their search ID and email appended to the url
 * for when they return to the website
 */
define ('URL', "http://localhost:10088/signin.php");

/*
 * the three following functions construct
 * and return strings (emails) that will be
 * sent to the user that notify
 * them of their background search
 */
function getConfirmationEmail(&$post,$userSource,$userDestination,$userID)
{
    $returnArr = array(
	'from'    => 'SoFly <ucd.flight.tracker@gmail.com>',
	'to'      => '<'.$post['email'].'>',
	'subject' => 'SoFly Confirmation',
	'html'    => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		      <html xmlns="http://www.w3.org/1999/xhtml" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
			    <head>
				<meta name="viewport" content="width=device-width" />
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
				<title>SoFly</title>
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
						Your Search is About to Begin!
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
								Thank you for submitting a search to SoFly. 
							    </td>
							</tr>
							<tr style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
							    <td class="content-block" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
								Our search bot will continue searching for your flight from '.$userSource.' to ' .$userDestination. '. Your request ID is <b>#' .$userID. '</b>. Please make note of your ID so that you can check the progress of your search and check your email for a notification from us when we find you the perfect flight! 
							    </td>
							</tr>
							<tr style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
							    <td class="content-block-button" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;" align="center" valign="top">
								<a href="'.URL.'?id='.$userID.'&email='.$post['email'].'" class="btn-primary" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; color: #FFF; text-decoration: none; line-height: 2; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; text-transform: capitalize; background-color: #5e4763; margin: 0; border-color: #5e4763; border-style: solid; border-width: 10px 20px;">Pack Your Bags!</a>
							    </td>
							</tr>
							<tr style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
							    <td class="content-block-button" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;" align="center" valign="top">
								Thanks for choosing SoFly!
							    </td>
							</tr>
						    </table>
						</td>
					    </tr>
					</table>
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
} // getConfirmationEmail()

/* 
 * see note above getConfirmationEmail() for
 * information about this function
 */
function getResultsEmail($userEmail, $userID, $userSource, $userDestination, $incOrDecr)
{
    
    /*
     * incOrDecr indicates whether
     * prices have risen or dropped
     * so we can change the contents 
     * of the notification emails
     * accordingly
     */
    if($incOrDecr == 1)
	$state = 'It seems that prices are increasing! We suggest you purchase your tickets soon before they become too expensive.';
    else if($incOrDecr == 0)
	$state = 'It seems that prices are decreasing! Our search bot will continue to search and let you know when the prices begin to increase as well.';
    else // this else shouldn't really ever be reached (just in case)
	$state = 'Prices seem to be staying the same for the most part. We will be sure to let you know if we find any change in the flight prices.';

    $resultsArr = array(
	'from'    => 'SoFly <ucd.flight.tracker@gmail.com>', 
	'to'      => '<'.$userEmail.'>',
	'subject' => 'New flight prices have been found!  ',
	'html'    => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	    <html xmlns="http://www.w3.org/1999/xhtml" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
	    <head>
	    <meta name="viewport" content="width=device-width" />
	    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	    <title>SoFly</title>
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
					We have some results for you!
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
						    We have begun our search for the perfect flight for you!                                                            </td>
					    </tr>
					    <tr style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
						<td class="content-block" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
						   '.$state.' Our search bot has been searching for flight options for you from '.$userSource.' to ' .$userDestination. '. Please check the status of your search by clicking on the button below. We will continue to post the results we find for you on this page. Use your request ID and email to login to our page to view your flight results. As a reminder your request ID was <b>#'.$userID.'</b>. We hope you enjoy your flight.
						</td>
					    </tr>
					    <tr style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
						<td class="content-block-button" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;" align="center" valign="top">
						    <a href="'.URL.'?id='.$userID.'&email='.$userEmail.'" class="btn-primary" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; color: #FFF; text-decoration: none; line-height: 2; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; text-transform: capitalize; background-color: #5e4763; margin: 0; border-color: #5e4763; border-style: solid; border-width: 10px 20px;">Check Flight Status</a>
						</td>
					    </tr>
					    <tr style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
						<td class="content-block-button" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;" align="center" valign="top">
						    Thanks for choosing SoFly!
						</td>
					    </tr>
					</table>
				    </td>
				</tr>
			    </table>
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
    return $resultsArr;
} // getResultsEmail()


/* 
 * see note above getConfirmationEmail() for
 * information about this function
 */
function SearchOverEmail($userEmail, $userID, $userSource, $userDestination)
{
    $resultsArr = array(
	'from'    => 'SoFly <ucd.flight.tracker@gmail.com>', 
	'to'      => '<'.$userEmail.'>',
	'subject' => 'We found a flight for you!  ',
	'html'    => '
	    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	    <html xmlns="http://www.w3.org/1999/xhtml" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
	    <head>
	    <meta name="viewport" content="width=device-width" />
	    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	    <title>SoFly</title>
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
					It\'s Time to Fly! Your Search is Complete!
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
						    We have found the perfect flight for you!                                                            </td>
					    </tr>
					    <tr style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
						<td class="content-block" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
						    It looks like your search time is up. Our search bot found some flight results for you from '.$userSource.' to ' .$userDestination. '. Please check the final result of your search by clicking on the button below. Use your request ID and email to login to our page to view your flight results. As a reminder your request ID was <b>#'.$userID.'</b>. We hope you enjoy your flight! 
						</td>
					    </tr>
					    <tr style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
						<td class="content-block-button" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;" align="center" valign="top">
						    <a href="'.URL.'?id='.$userID.'&email='.$userEmail.'" class="btn-primary" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; color: #FFF; text-decoration: none; line-height: 2; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; text-transform: capitalize; background-color: #5e4763; margin: 0; border-color: #5e4763; border-style: solid; border-width: 10px 20px;">Pack Your Bags!</a>
						</td>
					    </tr>
					    <tr style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
						<td class="content-block-button" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;" align="center" valign="top">
						    Thanks for choosing SoFly!
						</td>
					    </tr>
					</table>
				    </td>
				</tr>
			    </table>
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
    return $resultsArr;
} // SearchOverEmail()

/*
 * this function returns
 * the point in time at which
 * the user's background search
 * will end
 */
function getEndTime($search_time)
{ return date('Y-m-d H:i:s', time() + round(($search_time * 60 * 60),0));} 
// getEndTime()

/*
 * this function returns the number
 * of remaining seconds in the user's
 * background search
 */
function getRemainingTime($id,$email)
{
    /*
     * connect to database
     */
    require_once 'login.php';
    $connection = new mysqli ('localhost', 'root');
    if ($connection->connect_error) die ($connection->connect_error);
    $connection->select_db("flight_tracker");

    /*
     * perform query to get the end time
     */
    $getTime = <<<_QUERY
	SELECT end
	FROM searches
	WHERE ID = {$id}
	    and email = '{$email}';
_QUERY;
    $result = $connection->query($getTime);
    if (!$result) die($connection->error);
    $result->data_seek(0);
    $remaining = 0;

    /*
     * determine the remaining time
     */
    try {
	$end = $result->fetch_assoc()['end'];
	$day_time = explode(" ",$end);
	$day = explode("-",$day_time[0]);
	$clock = explode(":",$day_time[1]);
	$remaining = (mktime($clock[0], $clock[1], $clock[2], $day[1], $day[2], $day[0]) - time());
    } catch (Exception $e)
    { }

    return ($remaining <= 0 ? 0 : $remaining);
} // getRemainingTime(); returns UNIX timestamp

/*
 * this function gets background search data
 * from the database, parses the data, and returns
 * it as an object to the results page to later
 * be displayed in a graph
 */
function getGraphData($id, $email)
{
    /* connect to database */
    require_once 'login.php';
    $connection = new mysqli ('localhost', 'root');
    if ($connection->connect_error) die ($connection->connect_error);
    $connection->select_db("flight_tracker");

    /*
     * build and execute
     * query to get the sale total
     * and time of query
     */
    $query = <<<_QUERY
	SELECT MIN(opt_saletotal), query_time
	FROM `{$id}`
	GROUP BY query_time;
_QUERY;
    $result = $connection->query($query);
    if (!$result) die($connection->connect_error);

    /*
     * scan the results of the query
     * and append them into an array
     * of data that will be returned 
     * to results page
     */
    $rv = array();
    $labels = array();
    $data = array();
    $numRows = $result->num_rows;
    for ($i = 0; $i < $numRows; ++$i)
    {
	$result->data_seek($i);
	$row = $result->fetch_array(MYSQLI_ASSOC);
	$date = explode("-",explode(" ", $row['query_time'])[0]);
	$time = explode(":",explode(" ", $row['query_time'])[1]);
	$fullTime = mktime($time[0], $time[1], $time[2], $date[1], $date[2], $date[0]);
	$labels[] = '"'.date("g:i:s A n/j", $fullTime).'"';
	$data[] = $row['MIN(opt_saletotal)'];
    } // for numRows
    
    $rv['labels'] = $labels;
    $rv['data'] = $data;
    return $rv;
} // getGraphData()

/* 
 * checks the user preferences
 * within the db to see if they
 * wanted one-way trip
 */
function checkIsOneWay($post)
{
    /* connect to DB */
    require_once 'login.php';
    $connection = new mysqli('localhost','root');
    if ($connection->connect_error) die ($connection->connect_error);
    $connection->select_db("flight_tracker");

    /* build and execute query */
    $query = <<<_BLAH
	SELECT one_way 
	FROM searches  
	WHERE 
	    id = {$post['id']} AND 
	    email = '{$post['email']}';
_BLAH;
    $result = $connection->query($query);
    if (!$result) die ($connection->error);

    /*
     * check if the user
     * wanted a one-way trip
     */
    $result->data_seek(0);
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $val = false;
    if ($row['one_way'])
	$val = true;

    return $val;
} // checkIsOneWay()

/*
 * returns the number
 * of seconds between each 
 * query of results 
 */
function getInterval($searchTime)
{
    $seconds = 20;
    if ($searchTime >= 1)
    {
	switch ($searchTime)
	{
	    case 1:
		$seconds = 10 * 60; // 10 minutes	
		break;
	    case 2:
		$seconds = 15 * 60; // 15 minutes
		break;
	    case 4:
		$seconds = 17 * 60; // 17 minutes	
		break;
	    case 8:
		$seconds = 20 * 60; // 20 minutes	
		break;
	    case 12:
		$seconds = 25 * 60; // 25 minutes	
		break;
	    case 24:
	    case 48:
	    case 72:
	    case 96:
		$seconds = 30 * 60; // 30 minutes
		break;
	} // switch
    } // if

    return ($seconds);
} // getInterval()
?>

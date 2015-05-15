<?php
/*
 * this file is the backbone to the background
 * search. it first creates a table for the unique 
 * search request ID and then starts a main loop to 
 * perform queries to the QPX API at increments that 
 * are specified by a function. it also sends emails 
 * to the user when the prices change
 */

 /* 
  * allow this php scrip to continue 
  * running even if user closes browser
  */
ignore_user_abort(true);
set_time_limit(0);

define('__ROOT3__',dirname(__FILE__));
require_once('flight_tracker.php');
require_once('login.php');
require_once(__ROOT3__ . '/vendor/autoload.php');

/* 
 * initialize variables and
 * also get the interval duration
 * between QPX queries using the
 * getInterval() function. the 
 * interval is passed as an argument
 * to the sleep() function
 */
$post = $_GET;
$userID = $post['id'];
$userSource = $post['source'];
$userDestination = $post['destination'];
$interval = getInterval($post['searchTime']); 

/* check if user specified a 
 * one-way trip or round-trip
 */
$oneWay = false;
if (checkIsOneWay($post))
    $oneWay = true;

/*
 * initialize Mailgun client and
 * domain for sending emails, the 
 * first of which is the confirmation 
 * email that is sent below using
 * the sendMessage() function
 */
use Mailgun\Mailgun;
$mgClient = new Mailgun('key-d76af0f266f20519801b8997210febfd');
$domain = "sandboxc740d3f374c749c391b5e8abfdee56b2.mailgun.org";
$result = $mgClient->sendMessage($domain, getConfirmationEmail($post,$userSource,$userDestination,$userID));

// connect to database
$connection = new mysqli ("localhost", "root");
if($connection->connect_error) die($connection->connect_error);
mysqli_select_db($connection,"flight_tracker");

/*
 * create table for unique search
 * request. the table name is the
 * same as the request ID. the table
 * attributes are mostly parts of the
 * Google QPX API that we store for
 * displaying the graph and remembering 
 * changes in the price
 */
$tableName = "`{$post['id']}`";
$userTable = <<<_TABLEQUERY
CREATE TABLE {$tableName} (
    opt_id			     VARCHAR(60) NOT NULL, 
    opt_saletotal		     FLOAT(10) NOT NULL, 
    opt_slice_num		     TINYINT NOT NULL, 
    opt_slice_seg_id		     VARCHAR(60) NOT NULL, 
    opt_slice_seg_duration	     INT NOT NULL, 
    opt_slice_seg_flight_carrier     VARCHAR(40) NOT NULL, 
    opt_slice_seg_flight_num	     VARCHAR(10) NOT NULL, 
    opt_slice_seg_cabin		     VARCHAR(20) NOT NULL, 
    opt_slice_seg_leg_id	     VARCHAR(60) NOT NULL, 
    opt_slice_seg_leg_aircraft	     VARCHAR(20) NOT NULL, 
    opt_slice_seg_leg_arrival_time   TIMESTAMP NOT NULL, 
    opt_slice_seg_leg_departure_time TIMESTAMP NOT NULL, 
    opt_slice_seg_leg_origin	     VARCHAR(10) NOT NULL, 
    opt_slice_seg_leg_destination    VARCHAR(10) NOT NULL, 
    opt_slice_seg_leg_duration	     INT NOT NULL, 
    opt_slice_seg_leg_mileage	     INT NOT NULL, 
    opt_slice_seg_leg_meal	     VARCHAR(20) NOT NULL, 
    query_time			     TIMESTAMP NOT NULL,
    PRIMARY KEY (
	opt_id, 
	opt_saletotal, 
	opt_slice_seg_id, 
	opt_slice_seg_leg_id
    )
);
_TABLEQUERY;
$resultTable = $connection->query($userTable);
if (!$resultTable) die ($connection->error);

/*
 * this is the start of the main loop.
 * the keyIndex directly below is used
 * for cycling through the API keys of 
 * our group members
 */
$keyIndex = 0;
$oneMoreIt = false;
do {	// begin search

    /*
     * get search parameters that the
     * user specified from the database
     */
    $query = <<<_QUERY
	SELECT * 
	FROM searches  
	WHERE ID = {$post['id']} and  
	    email = '{$post['email']}'; 
_QUERY;
    $result = $connection->query($query);
    if (!$result) die ($connection->error);

    /*
     * record information from the above
     * query into variables for later use
     */
    $result->data_seek(0);
    $rows = $result->fetch_array(MYSQLI_ASSOC);
    $lowestPrice = $rows['lowest_price'];   // lowest price so far
    $lastPrice = $rows['last_price'];	    // last price
    $end = $rows['end'];		    // timestamp of end of search
    $ymd = explode(" ", $end);
    $ymd2 = explode("-", $ymd[0]);
    $ymd3 = explode(":", $ymd[1]);
    $end_secs = mktime($ymd3[0], $ymd3[1], $ymd3[2], 
		       $ymd2[1], $ymd2[2], $ymd2[0]);
					// ^ end of search time in UNIX timestamp
    $current_sec = time();		// current time in UNIX timestamp

    /*
     * get airline parameters that 
     * user specified from database
     */
    $query2 = <<<_QUERY2
	SELECT airline 
	FROM airlines  
	WHERE search_id = {$post['id']} and  
	    email = '{$post['email']}'; 
_QUERY2;
    $result2 = $connection->query($query2);
    if (!$result2) die ($connection->error);

    /*
     * record airline preferences
     * into an array of airline codes
     */
    $airlines = array(); 
    $rows2 = $result2->num_rows;
    for($i=0; $i<$rows2; $i++)
    {
	$result2->data_seek($i);
	$rows3 = $result2->fetch_array(MYSQLI_ASSOC);
	$airlines[] = $rows3['airline']; // append each airline to array
    } // for

    /*
     * check if background search should
     * be continued if current time
     * is less than the end time
     */
    if(($current_sec < $end_secs) || $oneMoreIt)
    {
	/*
	 * parse the departure and return dates
	 * and reformat for QPX API, but only
	 * parse return date if not a one-way
	 * trip
	 */
	$d = explode("-",$rows['depart_date']);
	$d = implode("/", array($d[1], $d[2], $d[0]));
	if (!$oneWay)
	{
	    $r = explode("-",$rows['return_date']);
	    $r = implode("/", array($r[1], $r[2], $r[0]));
	} else
	    $r = "";

	$current_info = array( 
	    "id" => $rows['ID'],
	    "email" => $rows['email'],
	    "source" => $rows['origin'],
	    "destination" => $rows['destination'],
	    "depart_date" => $d, 
	    "return_date" => $r,
	    "adults" => $rows['adults'],
	    "children" => $rows['children'],
	    "seniors" => $rows['seniors'],
	    "seat_infants" => $rows['seat_infant'],
	    "lap_infants" => $rows['lap_infant'], 
	    "price" => $rows['price'], 
	    "airline" => $airlines,
	    "one_way" => $oneWay
	  );
    
	/*
	 * pass user preferences into the
	 * getResults() function (see flight_tracker.php),
	 * which packages the Google QPX request object
	 * and returns an object of results from the response
	 * object
	 */
	$searchResults = getResults($current_info, 5, $keyIndex++);
	$trips = $searchResults->getTrips();

	/*
	 * start insertion query that stores the 
	 * QPX results into the table for this search
	 * request
	 */
	$insertQuery = <<<_QUERY3
	    INSERT INTO {$tableName} 
	    (
		opt_id,
		opt_saletotal,
		opt_slice_num,
		opt_slice_seg_id,
		opt_slice_seg_duration,
		opt_slice_seg_flight_carrier,
		opt_slice_seg_flight_num,
		opt_slice_seg_cabin,
		opt_slice_seg_leg_id,
		opt_slice_seg_leg_aircraft,
		opt_slice_seg_leg_arrival_time,
		opt_slice_seg_leg_departure_time,
		opt_slice_seg_leg_origin,
		opt_slice_seg_leg_destination,
		opt_slice_seg_leg_duration,
		opt_slice_seg_leg_mileage,
		opt_slice_seg_leg_meal,
		query_time
	    ) VALUES 
_QUERY3;
	/*
	 * the code below parses
	 * the result of the response object
	 * recieved from Google QPX Express API,
	 * similar to the way the printResults() 
	 * function parses them (see flight_tracker.php)
	 * and appends these results to the insertQuery 
	 * that was started above. The parsing/packaging
	 * was made possible by the PHP client written for 
	 * QPX express, found within our files
	 */
	$flag = true;
	$prices = array();
	$query_time = date("Y-m-d H:i:s", time());
	/*
	 * the response object from QPX contains several trip
	 * options. a trip option specifies one option for the user
	 * to book if they so desire. So, each option contain one sale total
	 * and several smaller objects: slices, segments, legs
	 */
	foreach ($trips->getTripOption() as $option) {
	    $tripOptionId = $option->getId();
	    $tripOptionSaleTotal = substr($option->getSaleTotal(),3);
	    $prices[] = $tripOptionSaleTotal;
	    $sliceCount = 1;
	    /*
	     * if there were trip results, then each tripOption
	     * contains at least 1 slice.
	     * a slice refers to whether or not the itinerary 
	     * for the user's trip is one-way or not. If it's one-way, then
	     * there would only be 1 slice, if it's round trip, then there
	     * would be 2 slices
	     */
	    foreach ($option->getSlice() as $slice) {
		/*
		 * each slice contains at least 1 segment, which are
		 * portions of a slice.
		 * one segment contains at least 1 leg by the an airline. 
		 * if the segment contains multiple legs, then the legs
		 * are all of the same airline. So, in simpler terms, a 
		 * segment contains legs of only one airline
		 */
		foreach ($slice->getSegment() as $segment) {
		    $segmentId = $segment->getId();
		    $segmentDuration = $segment->getDuration();
		    $segmentFlightCarrier = $segment->getFlight()->getCarrier();
		    $segmentFlightNumber = $segment->getFlight()->getNumber();
		    $segmentCabin = $segment->getCabin();
		    
		    /* 
		     * each segment contains at least 1 leg
		     * and each leg contains all of the important 
		     * information regarding the flights:
		     * times, places, meals, mileage, duration, etc.
		     * This loop appends to the insertQuery for each leg
		     */
		    foreach ($segment->getLeg() as $leg) {
			$legId = $leg->getId();
			$legAircraft = $leg->getAircraft();
			$legArrivalTime = $leg->getArrivalTime();
			$legDepartureTime = $leg->getDepartureTime();
			$legOrigin = $leg->getOrigin();
			$legDestination = $leg->getDestination();
			$legDuration = $leg->getDuration();
			$legMileage = $leg->getMileage();
			$legMeal = $leg->getMeal();
			
			if (!$flag) $insertQuery .= ",";
			if ($flag) $flag = false;
			/* append to the insertQuery */
			$insertQuery .= <<<_QUERY4
			    ('{$tripOptionId}',
			     {$tripOptionSaleTotal},
			     {$sliceCount},
			    '{$segmentId}',
			     {$segmentDuration},
			    '{$segmentFlightCarrier}',
			    '{$segmentFlightNumber}',
			    '{$segmentCabin}',
			    '{$legId}',
			    '{$legAircraft}',
			    '{$legArrivalTime}',
			    '{$legDepartureTime}',
			    '{$legOrigin}',
			    '{$legDestination}',
			     {$legDuration},
			     {$legMileage},
			    '{$legMeal}',
			    '{$query_time}'
			    )
_QUERY4;
		    } // foreach leg
		} // foreach segment
		$sliceCount++;
	    } // foreach slice
	} // foreach option
	$insertQuery .= ";";
	$insertResult = $connection->query($insertQuery);
	if (!$insertResult) die ($connection->error);

	/*
	 * obtain the lowest price from the recent 
	 * set of results and compare it to the lowest 
	 * found price so far in our db. If the recent 
	 * minimum is the newest absolute minimum, then
	 * update the DB and send an email to the user
	 */
	$minFromSearch = min($prices);
	if ($minFromSearch < $lowestPrice)
	{
	    $updateLowestPrice = <<<_QUERY5
		UPDATE searches
		SET lowest_price = {$minFromSearch}
		WHERE ID = {$userID}
		    AND email = '{$post['email']}';
_QUERY5;
	    $updateResults = $connection->query($updateLowestPrice);
	    if (!$updateResults) die ($connection->error);
	    
	    // send email
	    $result = $mgClient->sendMessage($domain, getResultsEmail($post['email'],$post['id'],$rows['origin'],$rows['destination'],0)); 
	} else if ($minFromSearch > $lastPrice) // if price increased since last
	{
	    $result = $mgClient->sendMessage($domain, getResultsEmail($post['email'],$post['id'],$rows['origin'],$rows['destination'],1)); 
	}// if/else

	/*
	 * update the last_price in database
	 * to match the most recent mininum
	 */
	$updateLastPrice = <<<_QUERY6
	    UPDATE searches
	    SET last_price = {$minFromSearch}
	    WHERE ID = {$userID}
		AND email = '{$post['email']}';
_QUERY6;
	$updateResults = $connection->query($updateLastPrice);
	if (!$updateResults) die ($connection->error);

	/*
	 * delay execution of the loop
	 * if it still needs to be running
	 */
	sleep($interval);
    } // if search still needs to be running
    else 
    {
	/*
	 * oneMoreIt won't be set during the first
	 * time this else is reach, so it will perform
	 * one more final interation before terminating
	 * just to obtain one more set of results
	if ($oneMoreIt) {break;}
	$oneMoreIt = true;
	sleep(5);
	 */
	 break;
    }

} while(($current_sec < $end_secs) || $oneMoreIt);

/*
 * send final email
 */
$result = $mgClient->sendMessage($domain, SearchOverEmail($post['email'],$post['id'],$rows['origin'],$rows['destination'])); 
?>

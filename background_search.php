<?php
ignore_user_abort(true);

define('__ROOT3__',dirname(__FILE__));
require_once('flight_tracker.php');
require_once('login.php');
require_once(__ROOT3__ . '/vendor/autoload.php');

$post = $_GET;
$userID = $post['id'];
$userSource = $post['source'];
$userDestination = $post['destination'];
$interval = 20; //seconds for sleep function

$oneWay = false;
if (checkIsOneWay($post))
    $oneWay = true;

// send email to user
use Mailgun\Mailgun;
$mgClient = new Mailgun('key-d76af0f266f20519801b8997210febfd');
$domain = "sandboxc740d3f374c749c391b5e8abfdee56b2.mailgun.org";
$result = $mgClient->sendMessage($domain, getConfirmationEmail($post,$userSource,$userDestination,$userID));

// connect to database
$connection = new mysqli ("localhost", "root");
if($connection->connect_error) die($connection->connect_error);
mysqli_select_db($connection,"flight_tracker");

//Create table emailATemailDOTcomID and add attributes 
//$tableName = str_replace(".","DOT",str_replace("@","AT",$post['email'])) . $post['id'];
$tableName = "`{$post['id']}`";
$userTable = <<<_TABLEQUERY
CREATE TABLE {$tableName} (
    opt_id VARCHAR(60) NOT NULL, 
    opt_saletotal FLOAT(10) NOT NULL, 
    opt_slice_num TINYINT NOT NULL, 
    opt_slice_seg_id VARCHAR(60) NOT NULL, 
    opt_slice_seg_duration INT NOT NULL, 
    opt_slice_seg_flight_carrier VARCHAR(40) NOT NULL, 
    opt_slice_seg_flight_num VARCHAR(10) NOT NULL, 
    opt_slice_seg_cabin VARCHAR(20) NOT NULL, 
    opt_slice_seg_leg_id VARCHAR(60) NOT NULL, 
    opt_slice_seg_leg_aircraft VARCHAR(20) NOT NULL, 
    opt_slice_seg_leg_arrival_time TIMESTAMP NOT NULL, 
    opt_slice_seg_leg_departure_time TIMESTAMP NOT NULL, 
    opt_slice_seg_leg_origin VARCHAR(10) NOT NULL, 
    opt_slice_seg_leg_destination VARCHAR(10) NOT NULL, 
    opt_slice_seg_leg_duration INT NOT NULL, 
    opt_slice_seg_leg_mileage INT NOT NULL, 
    opt_slice_seg_leg_meal VARCHAR(20) NOT NULL, 
    query_time TIMESTAMP NOT NULL,
    PRIMARY KEY (
	opt_id, opt_saletotal, 
	opt_slice_seg_id, opt_slice_seg_leg_id
    )
);
_TABLEQUERY;
$test = $userTable;
$resultTable = $connection->query($userTable);
if (!$resultTable) die ($connection->error);

do {	// begin search

    // get search parameters 
    $query = <<<_QUERY
	SELECT * 
	FROM searches  
	WHERE ID = {$post['id']} and  
	    email = '{$post['email']}'; 
_QUERY;
    $result = $connection->query($query);
    if (!$result) die ($connection->error);

    // get timing information from search;
    $result->data_seek(0);
    $rows = $result->fetch_array(MYSQLI_ASSOC);
    $min_price = $rows['lowest_price']; // lowest price so far
    $end = $rows['end'];    // end of search time
    $ymd = explode(" ", $end);
    $ymd2 = explode("-", $ymd[0]);
    $ymd3 = explode(":", $ymd[1]);
    $end_secs = mktime($ymd3[0], $ymd3[1], $ymd3[2], $ymd2[1], $ymd2[2], $ymd2[0]);
    $current_sec = time();

    // get airline information
    $query2 = <<<_QUERY2
	SELECT airline 
	FROM airlines  
	WHERE search_id = {$post['id']} and  
	    email = '{$post['email']}'; 
_QUERY2;
    $result2 = $connection->query($query2);
    if (!$result2) die ($connection->error);

    // put airline info into an array
    $airlines = array(); 
    $rows2 = $result2->num_rows;
    for($i=0; $i<$rows2; $i++)
    {
	$result2->data_seek($i);
	$rows3 = $result2->fetch_array(MYSQLI_ASSOC);
	$airlines[] = $rows3['airline'];
    } // for

    // check if background search should be continued
    // if the current time isn't equal to the end time
    if($current_sec < $end_secs)
    {
	// organize user input into an array
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
    
	// get flight info from QPX API
	$searchResults = getResults($current_info, 5);
	$trips = $searchResults->getTrips();

	// start insertion query
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
	// parse results
	$flag = true;
	$query_time = date("Y-m-d H:i:s", time());
	foreach ($trips->getTripOption() as $option) {
	    $tripOptionId = $option->getId();
	    $tripOptionSaleTotal = substr($option->getSaleTotal(),3);
	    $sliceCount = 1;
	    foreach ($option->getSlice() as $slice) {
		foreach ($slice->getSegment() as $segment) {
		    $segmentId = $segment->getId();
		    $segmentDuration = $segment->getDuration();
		    $segmentFlightCarrier = $segment->getFlight()->getCarrier();
		    $segmentFlightNumber = $segment->getFlight()->getNumber();
		    $segmentCabin = $segment->getCabin();
		    
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

	//If sale total is less than current lowest price found, update table and send mail to user
/*	
	if($rowCount['opt_saletotal'] < $min_price)
	{
	    $min_price = $rowCount['opt_saletotal'];

	    define('__ROOT3__',dirname(__FILE__));
	    require_once(__ROOT3__ . '/vendor/autoload.php');
	    use Mailgun\Mailgun;
	    $mgClient = new Mailgun('key-d76af0f266f20519801b8997210febfd');
	    $domain = "sandboxc740d3f374c749c391b5e8abfdee56b2.mailgun.org";

	    // send email
	    $result = $mgClient->sendMessage($domain, getResultsEmail($post['email'],$post['id'],$rows['origin'],$rows['destination'])); 
	} // if lower price discovered
	*/
	
    } // if search still needs to be running
    else // search is over, and we need to email
    {
	//echo "SEARCH IS OVER (0 TIME LEFT), CHANGE TIME PARAMETER IF TESTING BACKGROUND SEARCH";
	break;
    } // else: search is over

    // delay execution
    sleep($interval);
} while($current_sec < $end_secs);


//Send email once search is over
/*

	define('__ROOT3__',dirname(__FILE__));
	require_once(__ROOT3__ . '/vendor/autoload.php');
	use Mailgun\Mailgun;
	$mgClient = new Mailgun('key-d76af0f266f20519801b8997210febfd');
	$domain = "sandboxc740d3f374c749c391b5e8abfdee56b2.mailgun.org";

	// send email
	$result = $mgClient->sendMessage($domain, SearchOverEmail($post['email'],$post['id'],$rows['origin'],$rows['destination'])); 
	*/

function checkIsOneWay($post)
{
    require_once 'login.php';
    $connection = new mysqli('localhost','root');
    if ($connection->connect_error) die ($connection->connect_error);
    $connection->select_db("flight_tracker");
    $query = <<<_BLAH
	SELECT one_way 
	FROM searches  
	WHERE 
	    id = {$post['id']} AND 
	    email = '{$post['email']}';
_BLAH;
    $result = $connection->query($query);
    if (!$result) die ($connection->error);
    $result->data_seek(0);
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $val = false;
    if ($row['one_way'])
	$val = true;

    return $val;
} // checkIsOneWay()
?>

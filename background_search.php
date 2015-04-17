<?php
ignore_user_abort(true);
//jset_time_limit(
define('__ROOT__',dirname(__FILE__));
require_once(__ROOT__ .'/flight_tracker.php');
require_once(__ROOT__ . 
'/google-api-php-client/src/Google/Service/QPXExpress.php');
require_once(__ROOT__ .
'/google-api-php-client/src/Google/Client.php');
require_once 'login.php';

// connect to database
$connection = new mysqli ($db_hostname, $db_username);
if($connection->connect_error) die($connection->connect_error);
mysqli_select_db($connection,"flight_tracker");

$post = $_GET;
$interval = 20; //seconds for sleep function

do {	// begin search
    // get search parameters 
    $query = "SELECT * ".
	     "FROM searches ". 
	     "WHERE ID={$post['ID']} and ". 
		"email= {$post['email']}"; 
    $result = $connection->query($query);
    if (!$result) die ($connection->error);

    // get timing information
    $result->data_seek(0);
    $rows = $result->fetch_array(MYSQLI_ASSOC);
    $end = $rows['end'];
    $ymd = explode(" ", $end);
    $ymd2 = explode("-", $ymd[0]);
    $ymd3 = explode(":", $ymd[1]);
    $end_time = mktime($ymd3[0], $ymd3[1], $ymd3[2], $ymd2[1], $ymd2[2], $ymd2[0]);
    $curent_sec = time();

    // get airline information
    // and put it into an array
    $query2 = "SELECT airline ".
	      "FROM airlines ". 
	      "WHERE ID = {$post['ID']} and ". 
		"email= {$post['email']}"; 
    $result2 = $connection->query($query2);
    if (!$result2) die ($connection->error);
    $airlines = array(); 
    $rows2 = $result2->num_rows;
    for($i=0; $i<$rows2; $i++)
    {
	$result2->data_seek($i);
	$rows3 = $result2->fetch_array(MYSQLI_ASSOC);
	$airlines[] = $rows3['airline'];
    } // for

    // if search is not over
    if($current_sec < $end_time)
    {
	// build object to be sent to getResults
	$current_info = array( 
	    "ID" => $rows['ID'],
	    "email" => $rows['email'],
	    "origin" => $rows['source'],
	    "destination" => $rows['destination'],
	    "depart_date" => $rows['depart_date'],
	    "return_date" => $rows['return_date'],
	    "adults" => $rows['adults'],
	    "children" => $rows['children'],
	    "seniors" => $rows['seniors'],
	    "seat_infant" => $rows['seat_infants'],
	    "lap_infant" => $rows['lap_infants'], 
	    "price" => $rows['price'], 
	    "airlines" => $airlines
	  );
	    
	// get results
	$finalresults = getResults($current_info);
	$trips = $finalresult->getTrips();

    } // if
    sleep($interval);
} while($current_sec < $end_time);


function checkIsOneWay($post)
{
    $query = "SELECT return_date ".
	      "FROM searches ". 
	      "WHERE return_date = NULL AND ".
		    "id = {$post['id']} AND ".
		    "email = {$post['email']};";

    $result = $connection->query($query);
    if (!$result) die ($connection->error);
    $result->data_seek(0);
    $ret_day->fetch_array(MYSQLI_ASSOC)['return_date'];

    $val = true;
    if(!isset($ret_day) || $ret_day === NULL || is_null($ret_day))
	$val = true;

    return $val;
} // checkIsOneWay()
?>

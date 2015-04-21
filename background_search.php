<?php
ignore_user_abort(true);
//jset_time_limit(
require_once('flight_tracker.php');
require_once('login.php');

// connect to database
$connection = new mysqli ($db_hostname, $db_username);
if($connection->connect_error) die($connection->connect_error);
mysqli_select_db($connection,"flight_tracker");

$post = $_GET;
$interval = 20; //seconds for sleep function

//Create table emailATemailDOTcomID and add attributes 
$tableName = str_replace(".","DOT",str_replace("@","AT",$post['email'])) . $post['id'];
//echo $tableName;
$userTable = "CREATE TABLE {$tableName} (".
		"opt_id VARCHAR(60) NOT NULL, ".
                "opt_saletotal FLOAT(10) NOT NULL, ".
		"opt_seg_id VARCHAR(60) NOT NULL, ".
		"opt_seg_duration INT NOT NULL, ".
                "opt_seg_flight_carrier VARCHAR(40) NOT NULL, ".
                "opt_seg_flight_num VARCHAR(10) NOT NULL, ".
                "opt_seg_cabin VARCHAR(20) NOT NULL, ".
		"opt_seg_leg_id VARCHAR(60) NOT NULL, ".
                "opt_seg_leg_aircraft VARCHAR(20) NOT NULL, ".
                "opt_seg_leg_arrival_time TIMESTAMP NOT NULL, ".
                "opt_seg_leg_departure_time TIMESTAMP NOT NULL, ".
                "opt_seg_leg_origin VARCHAR(10) NOT NULL, ".
                "opt_seg_leg_destination VARCHAR(10) NOT NULL, ".
                "opt_seg_leg_duration INT NOT NULL, ".
		"opt_seg_leg_mileage INT NOT NULL, ".
		"opt_seg_leg_meal VARCHAR(20) NOT NULL, ".
		"PRIMARY KEY (".
		    "opt_id, opt_saletotal, ".
		    "opt_seg_id, opt_seg_leg_id".
		")".
	      ");";
$test = $userTable;
$resultTable = $connection->query($userTable);
if (!$resultTable) die ($connection->error);

do {	// begin search

    // get search parameters 
    $query = "SELECT * ".
	     "FROM searches ". 
	     "WHERE ID = {$post['id']} and ". 
		   "email = '{$post['email']}'"; 
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
    $query2 = "SELECT airline ".
	      "FROM airlines ". 
	      "WHERE search_id = {$post['id']} and ". 
		    "email = '{$post['email']}'"; 
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
    $d = explode("-",$rows['depart_date']);
    $r = explode("-",$rows['return_date']);
    if($current_sec < $end_secs)
    {
	// organize user input into an array
	$current_info = array( 
	    "id" => $rows['ID'],
	    "email" => $rows['email'],
	    "source" => $rows['origin'],
	    "destination" => $rows['destination'],
	    "depart_date" => implode("/", array($d[1], $d[2], $d[0])),
	    "return_date" => implode("/", array($r[1], $r[2], $r[0])),
	    "adults" => $rows['adults'],
	    "children" => $rows['children'],
	    "seniors" => $rows['seniors'],
	    "seat_infants" => $rows['seat_infant'],
	    "lap_infants" => $rows['lap_infant'], 
	    "price" => $rows['price'], 
	    "airline" => $airlines
	  );
    
	// get flight info from QPX API
	$searchResults = getResults($current_info, 5);
	$trips = $searchResults->getTrips();
	//print_r($trips);

	// start insertion query
	$insertQuery = "INSERT INTO {$tableName} ".
		       "(".
			    "opt_id, opt_saletotal, ".
			    "opt_seg_id, opt_seg_duration, ".
			    "opt_seg_flight_carrier, opt_seg_flight_num, ".
			    "opt_seg_cabin, opt_seg_leg_id, ".
			    "opt_seg_leg_aircraft, opt_seg_leg_arrival_time, ".
			    "opt_seg_leg_departure_time, opt_seg_leg_origin, ".
			    "opt_seg_leg_destination, opt_seg_leg_duration, ".
			    "opt_seg_leg_mileage, opt_seg_leg_meal".
		       ") VALUES ";
	$flag = true;
	// parse results
	foreach ($trips->getTripOption() as $option) {
	    $tripOptionId = $option->getId();
	    $tripOptionSaleTotal = substr($option->getSaleTotal(),3);

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
			$insertQuery .= "('{$tripOptionId}',{$tripOptionSaleTotal},".
					"'{$segmentId}',{$segmentDuration},".
					"'{$segmentFlightCarrier}','{$segmentFlightNumber}',".
					"'{$segmentCabin}','{$legId}',".
					"'{$legAircraft}','{$legArrivalTime}',".
					"'{$legDepartureTime}','{$legOrigin}',".
					"'{$legDestination}',{$legDuration},".
					" {$legMileage}, '{$legMeal}'".
					")";
		    } // foreach leg
		} // foreach segment
	    } // foreach slice
	} // foreach option
	$insertQuery .= ";";
	//$test .= (" " . $insertQuery);
	//echo $insertQuery;
	$insertResult = $connection->query($insertQuery);
	if (!$insertResult) die ($connection->error);

	//If sale total is less than current lowest price found, update table and send mail to user
	/*
	if($rowCount['opt_saletotal'] < $min_price)
	{
	    $min_price = $rowCount['opt_saletotal'];

	    //*********************************EMAIL CODE ************************
	    define('__ROOT3__',dirname(__FILE__));
	    require_once(__ROOT3__ . '/vendor/autoload.php');
	    use Mailgun\Mailgun;
	    $mgClient = new Mailgun('key-d76af0f266f20519801b8997210febfd');
	    $domain = "sandboxc740d3f374c749c391b5e8abfdee56b2.mailgun.org";

	    # Make the call to the client.
	    $result = $mgClient->sendMessage($domain, array(
		'from'    => 'UCD Flight Tracker <ucd.flight.tracker@gmail.com>', 'to'      => '<'.$post['email'].'>',
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
			    ));
	    //************************* END OF EMAIL CODE *********************
	} // if lower price discovered
	*/
    } // if search still incomplete
    else // search is over, and we need to email
    {
	break;
    } // else: search is over

    // delay execution
//    sleep($interval);
} while($current_sec < $end_secs);

function checkIsOneWay($post)
{
    $query = "SELECT return_date ".
	      "FROM searches ". 
	      "WHERE return_date = NULL AND ".
		    "id = {$post['id']} AND ".
		    "email = '{$post['email']}';";

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

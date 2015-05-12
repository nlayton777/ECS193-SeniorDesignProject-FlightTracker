<?php
    define('__ROOT__',dirname(__FILE__));

    /*      NEED THESE FILES FOR QPX API        */  
    require_once(__ROOT__ . 
    '/google-api-php-client/src/Google/Service/QPXExpress.php');
    require_once(__ROOT__ .
    '/google-api-php-client/src/Google/Client.php');

    function isOneWay(&$val) {
	$rv = false;
	if ($val['one_way'] == true || $val['one_way'] == 1) 
	    $rv = true;
	return $rv;
    } // isOneWay($val)

    function printResults($trips, $post)
    {
    //Start making booking url
    $urlString = "https://www.google.com/flights/#search;f={$post['source']};t={$post['destination']}";
    $originalDepart = $post['depart_date'];
    $newDepart = date("Y-m-d", strtotime($originalDepart));
    $urlString .= ";d={$newDepart}";
    if(!isOneWay($post)){
    	$originalReturn = $post['return_date'];
    	$newReturn = date("Y-m-d", strtotime($originalReturn));
    	$urlString .= ";r={$newReturn};";
    }else{
    	$urlString .= ";tt=o;";
    }
    
    $rowCount = 0;
    
    $options = $trips->getTripOption();
    if (isset($options)) 
    {
        $multPass = false;
        if ($post['adults'] > 1 || $post['children'] > 1 || $post['seniors'] > 1 || 
        $post['seat_infants'] > 1 || $post['lap_infants'] > 1)
        $multPass = true;

        // print headers
        echo <<<_HEADERS
        <table id="results" class="table table-hover" style="background-color: rgba(150, 150, 150, 0)" align="center">
        <tr>
            <th id="price">Total
_HEADERS;
            if ($multPass)
            echo "Group ";
        echo <<<_HEADERS2
            Price</th>
            <th id="it">Itinerary</th>
            <th id="info" colspan="2" >More Info</th>
        </tr>
_HEADERS2;
	    foreach ($options as $option) 
	    {
	    $sub = substr($option->getSaleTotal(),3);
	    echo <<<_STUFF
	    <tr>
	       <td>\${$sub}</td>
		<td>
_STUFF;
		// print if one way
		if (!isOneWay($post))
		{
		    echo <<<_STUFF2
		    <div id="on-the-way-there">
		    <h5>On the way there...</h5>
_STUFF2;
		}

		foreach ($option->getSlice()[0]->getSegment() as $segment)
		{
		    foreach ($segment->getLeg() as $leg)
		    {
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
		    } // end for
		} // end for

		if (!isOneWay($post)) {
		    echo <<<_STUFF4
		    </div>
		    <div id="on-the-way-back">
		    <h5>On the way back...</h5>
_STUFF4;

		    foreach ($option->getSlice()[1]->getSegment() as $segment)
		    {
		    foreach ($segment->getLeg() as $leg)
		    {
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
		    } // end for
		    } // end for
		    echo "</div>";
		} // end if

		echo <<<_STUFF6
		<div class="dropdown" id="row{$rowCount}">

		    <table id="dropdown-table">
		    <tr>
		    <th>Leg</th>
		    <th>Carrier</th>
		    <th>Cabin</th>
		    <th>Aircraft</th>
		    <th>Meal</th>
		    <th>Mileage</th>
		    <th>Duration</th>
		    <th>Flight #</th>
		    </tr>

_STUFF6;
				$urlEnd = $urlString . "sel=";
				$first = true;
                foreach ($option->getSlice() as $slice)
                {
                	if(!$first){
                		$urlEnd = substr($urlEnd, 0, -1);   
						$urlEnd .= ","; 
					}
					$first = false;
                foreach ($slice->getSegment() as $segment)
                {         
                    foreach ($segment->getLeg() as $leg)
                    {
                    //$orig and $dest are for the particular part of the flight
                    //$origin and $destination are for the whole flight
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


                        $carrier = $segment->getFlight()->getCarrier();
                        $previous = NULL; 
                        foreach ($trips->getData()->getCarrier() as $carrier)
                        {
                        //GET THE AIRLINES      
                        if ($carrier->getCode() == $segment->getFlight()->getCarrier()){
                            echo $carrier->getName();
                            $site = $carrier->getCode();
                            $oper = $leg->getOperatingDisclosure();
                            if ((strpos($oper,'AMERICAN AIRLINES') !== false)) {
                            	if($site !== "AS")
                            		$site = "AA";
                            } else if ((strpos($oper,'AMERICAN EAGLE') !== false)) {
                            		if($site !== "AS")
                            		$site = "AA";
                            } else if ((strpos($oper,'US AIRWAYS') !== false)) {
                            	if($site !== "AS")
                            		$site = "US";
                            }
                        }
                    }
                       
                        $cab = ucfirst(strtolower($segment->getCabin()));
                        $lg = $leg->getAircraft();

                        echo <<<_STUFF8
                        </td>
                        <td>{$cab}</td>
                        <td>{$lg}</td>
                        <td>
_STUFF8;
                        $meal = $leg->getMeal();
                        if (isset($meal))
                        echo $meal;
                        else
                        echo "None";

                        $mlg = $leg->getMileage();
                        $dur = $leg->getDuration();
                        $segFlightNum = $segment->getFlight()->getNumber();
                        
                        //continue making booking url
                        $urlEnd .= "{$orig}{$dest}0{$site}{$segFlightNum}-";
                        
                        echo <<<_STUFF9
                        </td>
                        <td>{$mlg} miles</td>
                        <td>{$dur} minutes</td>
                        <td>{$site}{$segFlightNum}</td>
                    </tr>
_STUFF9;
                    } // end for
                } // end for
                } // end for
                
				$urlEnd = substr($urlEnd, 0, -1);
				if($site == "AS")
                	$urlEnd = $urlString . "a=AS";
                echo <<<_STUFF10
                </table>
            </div>  
            </td>   
            <td class="expandButton">
            <input type="button" id="btnExpCol{$rowCount}" class="btn btn-info search" 
                onclick="Expand()" value=" Expand "/>
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
        echo <<<_STUFF11
        <h2>
        Sorry, we could not find any flights that match your
        preferences. We suggest broadening your search parameters
        to improve your chances at finding results.
        </h2>
_STUFF11;
	} // end if/else
    return ($rowCount);
    } // printResults($post)

    function getResults(&$post,$num) {
	// create client 
	$client = new Google_Client();
	//$client->setApplicationName("Flight Tracker");
	// nick
	//$client->setDeveloperKey("AIzaSyAxaZBEiV9Lwr8tni1sx2V6WVj8LKnrCas");
	// rupali
	//$client->setDeveloperKey("AIzaSyAgWz2bB0YHTwCzWJcS-99pJnzjImluqyg");
	// kirsten
	//$client->setDeveloperKey("AIzaSyB-cjP2Pfmkq_50JqmB8TcRx5sVgAWW5_Y");
	// nina
	//$client->setDeveloperKey("AIzaSyDsAGm880MwQmxzceJPEfMLwEE9W84wl8s");
	// flight tracker
	//$client->setDeveloperKey("AIzaSyCCS0WHeRJDiRZmxfTmqA9jCbETtMIvAUg");
	// rupali's other
	$client->setDeveloperKey("AIzaSyAlIaLcBQiyOpWVTPSJC-fOJz_2veF94Zw");
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
	} else {/*echo "source not set";*/}

	// set destination information
	if (isset($post['destination'])) {
	    $slice1->setDestination($post['destination']);
	    if (!isOneWay($post)) // if round-trip
		$slice2->setOrigin($post['destination']);
	} else {/*echo "destination not set";*/}

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
	/*      NEED THIS FILE FOR DATABASE     */
	require_once('login.php');

	// connect to database
	$connection = new mysqli ($db_hostname, $db_username);
	if($connection->connect_error) die($connection->connect_error);
	$connection->select_db("flight_tracker");

	// add user info to db
	$d_date = explode("/",$post['depart_date']);
	$d_date = "'".implode("-",array($d_date[2],$d_date[0],$d_date[1]))."'";
	if ($post['return_date'] != "NULL")
	{
	    $r_date = explode("/",$post['return_date']);
	    $r_date = implode("-",array($r_date[2],$r_date[0],$r_date[1]));
	    $r_date = "'".$r_date."'";
	} else
	    $r_date = "NULL";
	
	$endTime = getEndTime($post['search_time']);
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

	$result3 = $connection->query($query3);
	if (!$result3) die($connection->error);
	$last_id = $connection->insert_id;
	if (isset($post['airline']))
	{
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
		}
		else
		    $query4 .= ",({$last_id},'{$post['email']}','{$airline}')";
	    } // foreach airline
	    $query4 .= ";";

	    $result4 = $connection->query($query4);
	    if (!$result4) die($connection->error);
	} // is airline set

	$connection->close();
	return $last_id;
    } // createNewSearch($post)


    define ('URL', "http://localhost:10088/signin.php");
    function getConfirmationEmail(&$post,$userSource,$userDestination,$userID)
    {
	$returnArr = array(
		    'from'    => 'UCD Flight Tracker <ucd.flight.tracker@gmail.com>',
		    'to'      => '<'.$post['email'].'>',
		    'subject' => 'UCD Flight Tracker Confirmation',
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
									    Our search bot will continue searching for your flight from '.$userSource.' to ' .$userDestination. '. Your request ID is #' .$userID. '. Please make note of your ID so that you can check the progress of your search and check your email for a notification from us when we find you the perfect flight! 
									</td>
								    </tr>
								    <tr style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
									<td class="content-block-button" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;" align="center" valign="top">
									    <a href="'.URL.'" class="btn-primary" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; color: #FFF; text-decoration: none; line-height: 2; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; text-transform: capitalize; background-color: #5e4763; margin: 0; border-color: #5e4763; border-style: solid; border-width: 10px 20px;">Pack Your Bags!</a>
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

	$resultsArr = array(
		'from'    => 'UCD Flight Tracker <ucd.flight.tracker@gmail.com>', 
		'to'      => '<'.$userEmail.'>',
		'subject' => 'New flight prices have been found!  ',
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
							    We have begun our search for the perfect flight for you!                                                            </td>
						    </tr>
						    <tr style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
							<td class="content-block" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
							    Our search bot found some great flight options for you from '.$userSource.' to ' .$userDestination. '. Please check the status of your search by clicking on the button below. We will continue to post the results we find for you on this page. Use your request ID and email to login to our page to view your flight results. As a reminder your request ID was #'.$userID.'. We hope you enjoy your flight.
							</td>
						    </tr>
						    <tr style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
							<td class="content-block-button" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;" align="center" valign="top">
							    <a href="'.URL.'" class="btn-primary" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; color: #FFF; text-decoration: none; line-height: 2; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; text-transform: capitalize; background-color: #5e4763; margin: 0; border-color: #5e4763; border-style: solid; border-width: 10px 20px;">Check Flight Status</a>
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


function SearchOverEmail($userEmail, $userID, $userSource, $userDestination)
    {

	$resultsArr = array(
		'from'    => 'UCD Flight Tracker <ucd.flight.tracker@gmail.com>', 
		'to'      => '<'.$userEmail.'>',
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
							    We have found the perfect flight for you!                                                            </td>
						    </tr>
						    <tr style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
							<td class="content-block" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
							    Our search bot found some flight results for you from '.$userSource.' to ' .$userDestination. '. Please check the final result of your search by clicking on the button below. Use your request ID and email to login to our page to view your flight results. As a reminder your request ID was #'.$userID.'. We hope you enjoy your flight! 
							</td>
						    </tr>
						    <tr style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
							<td class="content-block-button" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;" align="center" valign="top">
							    <a href="'.URL.'" class="btn-primary" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; color: #FFF; text-decoration: none; line-height: 2; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; text-transform: capitalize; background-color: #5e4763; margin: 0; border-color: #5e4763; border-style: solid; border-width: 10px 20px;">Pack Your Bags!</a>
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

    function getEndTime($search_time)
    //{ return date('Y-m-d H:i:s', time() + ($search_time * 60 * 60));} 
    { return date('Y-m-d H:i:s',(time() + 60)); }
    // getEndTime($search_time)

    function getRemainingTime($id,$email)
    {
	require_once 'login.php';
	$connection = new mysqli ('localhost', 'root');
	if ($connection->connect_error) die ($connection->connect_error);
	$connection->select_db("flight_tracker");

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

    function checkOneWay($id, $email)
    {
	require_once 'login.php';
	$connection = new mysqli ('localhost', 'root');
	if ($connection->connect_error) die ($connection->connect_error);
	$connection->select_db("flight_tracker");
	
	/*
	$getSearch <<<_QUERY
	    SELECT return_date
	    FROM   searches
	    WHERE  ID    = {$id} and
		   email = {$email};
_QUERY;
	$result = $connection->query($getSearch);
*/
    } // checkOneWay()

    function getGraphData($id, $email)
    {
	require_once 'login.php';
	$connection = new mysqli ('localhost', 'root');
	if ($connection->connect_error) die ($connection->connect_error);
	$connection->select_db("flight_tracker");

	$query = <<<_QUERY
	    SELECT MIN(opt_saletotal), query_time
	    FROM `{$id}`
	    GROUP BY query_time;
_QUERY;
	$result = $connection->query($query);
	if (!$result) die($connection->connect_error);
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
	} // for each row
	
	$rv['labels'] = $labels;
	$rv['data'] = $data;
	return $rv;
    } // getGraphData()
?>

<!DOCTYPE html>
<html>
    <head>
	<title>UCD Flight Tracker</title>

	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="stylesheet" href="bootstrap.css"/>
	<script src="jquery-2.1.3.js"/></script>
	<script src="bootstrap.js"></script>
	<link rel="stylesheet" href="styles.css"/>
	<link rel="stylesheet" href="flipclock.css"/>
	<script src="flipclock.min.js"></script>
	<script src="flight_tracker.js"></script>
	<script>
	    function sendMessage() {
		setInterval(function () {doStuff();}, 1000);
	    }

	    function doStuff() {
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
		    if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
		    {
			document.getElementById("test").innerHTML = xmlhttp.responseText;
		    }
		}
		xmlhttp.open("POST","test.php",true);
		xmlhttp.send();
	    }
	</script>
    </head>

    <body>
	<nav class="navbar navbar-inverse" style="visibility: hidden;"></nav>
	<nav class="navbar navbar-inverse navbar-fixed-top">
	    <div id="main" class="container-fluid">
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
			<li class="active"><a href="index.php">Search</a></li>
			<li><a href="about.php">About</a></li>
		    </ul>
		    <ul class="nav navbar-nav navbar-right">
			<li><a href="contact.php">Contact</a></li>
		    </ul>
		</div>
	    </div>
	</nav>

	
	<div class="containter">
	    <div class="jumbotron countdown">
		<h1>Search Time Remaining</h1>
		<div class="clock" ></div>
		<p>We have begun your background search and will notify you once
		   we have either found your results or reached the end of your 
		   search time. We have provided a summary of your search 
		   parameters below. Please stay near your phone or computer 
		   since we will contact you via email. Be sure to have your 
		   Request ID and Email ready when you return for the updated 
		   search results.
		</p>
		<h3>Summary of Itinerary</h3>

		<?php
		    define('__ROOT4__',dirname(__FILE__));
		    require_once(__ROOT4__ . '/flight_tracker.php');
		  
		    /*
		    echo "<pre>";
		    print_r($_POST);
		    echo "</pre>";
		    echo "<br>";
		    */
		    $post = $_POST;

		    $last_id = createNewSearch($post);

		  
			$userID = $last_id;
			$userSource = $post['origin'];
			$userDestination = $post['destination'];

			// $userID = 'blah';
			// $userSource = 'blah';
			// $userDestination = 'blah';			



			define('__ROOT3__',dirname(__FILE__));
			require_once(__ROOT3__ . '/vendor/autoload.php');
			use Mailgun\Mailgun;
			// sql query
			// put query data in variables
			# Instantiate the client.
			$mgClient = new Mailgun('key-d76af0f266f20519801b8997210febfd');
			$domain = "sandboxc740d3f374c749c391b5e8abfdee56b2.mailgun.org";

			# Make the call to the client.
			$result = $mgClient->sendMessage($domain, array(
	    		'from'    => 'UCD Flight Tracker <ucd.flight.tracker@gmail.com>',
	    		'to'      => '<'.$post['email'].'>',
	    		'subject' => 'Thank you for using UCD Flight Tracker ',
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

	echo "<pre>";
	print_r($result);
	echo "</pre>";


		    echo "<script>CountdownClock({$post['search_time']})</script>";
		    echo "<div class=\"row\">";
			echo "<div class=\"col-md-3\"></div>";
			echo "<div class=\"col-md-3\">";
			    echo "<ul>";
				echo "<li>Request ID: {$last_id}</li>";
				echo "<li>Email: {$post['email']}</li>";
				echo "<li>Search Time: {$post['search_time']} hours</li>";
				echo "<li>Origin: {$post['origin']}</li>";
				echo "<li>Destination: {$post['destination']}</li>";
			    echo "</ul>";
			echo "</div>";

			echo "<div class=\"col-md-3\">";
			    echo "<ul>";
				echo "<li>Date of Departure: {$post['depart_date']}</li>";
				echo "<li>Date of Return: {$post['return_date']}</li>";

				$type = array(1 => 'Adults', 2 => 'Children', 3 => 'Seniors', 4 => 'Seat Infants', 5 => 'Lap Infants');
				foreach ($type as $t)
				    if (isset($post[$t]) && $post[$t] > 0)
					echo "<li>Number of {$t}: {$post[$t]}</li>";

				$i = 1;
				foreach ($post['airline'] as $airline)
				{
				    if (count($post['airline']) > 1)
					echo "<li>Airline Preference {$i}: {$airline}</li>";
				    else
					echo "<li>Airline Preference: {$airline}</li>";
				    $i++;
				}

				echo "<li>Maximum Price Limit: \${$post['price']}</li>";
			    echo "</ul>";
			echo "</div>";
			echo "<div class=\"col-md-3\"></div>";
		    echo "</div>";
		?>
	    </div>
	</div>
</html>

<?php
/*
 * this page displays the results of a live search for
 * the user's specified parameters. the user can expand
 * the rows of each result to see more information about 
 * the flight option. the user can also click the "Book It"
 * button to book their flight. they can also elect to 
 * perform a background search by entering their email 
 * address and the time period during which they want to search
 */

 /*
  * start session and check if user is logged in by
  * checking if session variables are checked, and 
  * record this result in a flag
  */
session_start();
ini_set('session.gc_maxlifetime', 60 * 60 * 1);
$seshFlag = false;
if (isset($_SESSION['id']) && isset($_SESSION['email']))
{
    $seshFlag = true;
}
?>
<!DOCTYPE html>
<html>
    <head>
	<title>SoFly | Search Results</title>

	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="stylesheet" href="bootstrap.css"/>
	<script src="jquery-2.1.3.js"/></script>
	<script src="bootstrap.js"></script>
	<link rel="stylesheet" href="styles.css"/>
	
	<!--this is for alerts-->
	<script src="bootbox.js"></script>

	<script>
	    /*
	     * validate the email address and search time of the user
	     * Note: the user cannot select a search time that passes 
	     * the departure date of their itinerary
	     */
	    function check(mail) 
	    {
		var currentSecs = new Date().getTime()/1000; // get current time in seconds
		var searchTime = document.getElementById("numHours").value;
		var searchSecs = searchTime * 60 * 60; // search window time in seconds 
		var CurrentSearchSecs = currentSecs + searchSecs;

		// get Departure date and convert to seconds 
		<?php 
		    $post = $_POST;
		    require_once('./flight_tracker.php');
		    $departureDate = strtotime($post['depart_date']);
		    echo "var departSecs = \"{$departureDate}\";";
		?>  

		if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(searchwindow.email.value)) {
		    if((CurrentSearchSecs) > departSecs) {
			alert("Please choose a search time that will complete before your departure date.")
			return false;
		    }
		    return(true);
		} else {
		    alert("You have entered an invalid email address!")
		    return (false)
		}
	    }// check()

	    /*
	     * submit hidden form to logout page
	     * for handling sessions 
	     */
	    function submitForm()
	    { document.getElementById("hiddenForm").submit(); }
	</script>
    </head>

    <body>
	<nav class="navbar navbar-inverse">
	    <div id="main" class="container-fluid">
		<div class="navbar-header">
		    <button type="button" class="navbar-toggle" 
			data-toggle="collapse" data-target="#mynavbar">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		    </button>
		    <a class="navbar-brand" href="index.php">SoFly</a>
		</div>

		<div class="collapse navbar-collapse" id="mynavbar">
		    <ul class="nav navbar-nav">

			<li class="active"><a href="index.php">Find a Flight</a></li>
			<?php
			    /* 
			     * if user is logged in then they can
			     * go directly to the results.php page,
			     * otherwise, force them to log in
			     */
			    if ($seshFlag)
				echo "<li><a href=\"results.php\">My Search</a></li>";
			    else
				echo "<li><a href=\"signin.php\">My Search</a></li>";
			?>
		    </ul>
		    <ul class="nav navbar-nav navbar-right">
			<li><a href="about.php">About</a></li>
			<li><a href="contact.php">Contact</a></li>
			<?php
			    /*
			     * if user logged in, then display "Log Out",
			     * otherwise, display "Log In"
			     */
			    if ($seshFlag)
				echo "<li><a href=\"javascript:;\" onclick=\"submitForm()\">Log Out</a></li>";
			    else
				echo "<li><a href=\"signin.php\">Log In</a></li>";
			?>
		    </ul>
		</div>
	    </div>
	</nav>

	<div class="container-fluid" id="searchheader">
	    <div class="row">
		<div class="col-xs-4 col-md-1"></div>
		<div class="col-xs-10 col-md-10">
		    <div class="row">
			<div class="col-md-6" id="search-title">
			    <h1>Search Results</h1>

			    <?php
				echo "<h3 id=\"trip-title\">{$post['depart_date']} <strong>". 
				     "{$post['source']}</strong> " . (isOneWay($post) ? "&rarr; " : "&harr; ").
				     " <strong>{$post['destination']}</strong>";
				$isRoundTrip = false;
				if (!isOneWay($post))
				{
					 echo " ".$post['return_date'];
					 $isRoundTrip = true;	
				}
				echo <<<_STUFF
				</h3>
			    <div>
			    	<h3 style="color:red">Price Analysis Hints:</h3>
_STUFF;
				$currentDate = date('m/d/Y');
				$departDate = $post['depart_date'];
				$returnDate = $post['return_date'];
				$timeNow = strtotime($currentDate);
				$timeDepart = strtotime($departDate);
				$timeReturn = strtotime($returnDate);
				$departDay = date("N", $timeDepart);
				$returnDay = date("N", $timeReturn);
				$diff1 = abs($timeDepart - $timeNow);
				$diff = $diff1 /(60 * 60 * 24); 
				// OPTIMAL SITUATION 
				if((($departDay == 2 || $departDay == 3) && 
				    ($returnDay == 2 || $returnDay == 3)) && $diff == 47)
				{
						if($departDay == 2) {
						    $dow = "Tuesday";
						} else if ($departDay == 3) {
						    $dow = "Wednesday";
						}
						
						if($returnDay == 2) {
						    $dow2 = "Tuesday";
						} else if ($returnDay == 3) {
						    $dow2 = "Wednesday";
						}
					echo "You are planning on travelling on the best ".
					     "priced days of the week, and according to historic ".
					     "trends today is the prime day to book your tickets ".
					     "for the lowest price. We recommend you book your tickets now!";
				}
				else//check for individual cases
				{
					//ANALYSIS ON DAY OF WEEK (TUES AND WED ARE BEST DAYS TO TRAVEL ON)
					if(($departDay == 2 || $departDay == 3) && 
					    ($returnDay == 2 || $returnDay == 3))
					{
						if($departDay == 2) {
						    $dow = "Tuesday";
						} else if ($departDay == 3) {
						    $dow = "Wednesday";
						}
						
						if($returnDay == 2) {
						    $dow2 = "Tuesday";
						} else if ($returnDay == 3) {
						    $dow2 = "Wednesday";
						}
						echo "You are currently planning on departing on a ".$dow. 
						     " and planning to return on " . $dow2. 
						     ". These are historically the best priced days of the ".
						     "week to travel. \n";
					} else if($departDay == 2 || $departDay == 3) {
						//case if departDay is on a Tuesday or Wednesday but returnDay is not
						if($departDay == 2) {
						    $dow = "Tuesday";
						} else if ($departDay == 3) {
						    $dow = "Wednesday";
						}
						if($returnDay == 1) {
						    $dow2 = "Monday";
						} else if ($returnDay == 2) {
						    $dow2 = "Tuesday";
						} else if ($returnDay == 3) {
						    $dow2 = "Wednesday";
						} else if ($returnDay == 4) {
						    $dow2 = "Thursday";
						} else if ($returnDay == 5) {
						    $dow2 = "Friday";
						} else if ($returnDay == 6) {
						    $dow2 = "Saturday";
						} else if ($returnDay == 7) {
						    $dow2 = "Sunday";
						} else if ($returnDay == 0) {
						    $dow2 = "Sunday";
						} 
						echo "You are currently planning on departing on a ".$dow. 
						     ". This is historically the best priced day of the ".
						     "week to travel on. You are returning on a ".$dow2. 
						     ", which historically does not have the best price ".
						     "for travel. If your days of travel are flexible, we ".
						     "recommend changing the day of your return flight.\n";
					} else if($returnDay == 2 || $returnDay == 3) {
						//case if returnDay is on a Tuesday or Wednesday but departDay is not
						if($departDay == 1) {
						    $dow2 = "Monday";
						} else if ($departDay == 2) {
						    $dow2 = "Tuesday";
						} else if ($departDay == 3) {
						    $dow2 = "Wednesday";
						} else if ($departDay == 4) {
						    $dow2 = "Thursday";
						} else if ($departDay == 5) {
						    $dow2 = "Friday";
						} else if ($departDay == 6) {
						    $dow2 = "Saturday";
						} else if ($departDay == 7) {
						    $dow2 = "Sunday";
						} else if ($departDay == 0) {
						    $dow2 = "Sunday";
						} 
						if($returnDay == 1)
						{
						    $dow = "Tuesday";
						} else if ($returnDay == 3) {
						    $dow = "Wednesday";
						}
						echo "You are currently planning to depart on a ".$dow2. 
						     ", which historically does not have the best price ".
						     "for travel. If your days of travel are flexible, we ".
						     "recommend changing the day of your departing flight. ". 
						     "You are returning on a ".$dow. ". This is historically ".
						     "the best priced day of the week to purchase a flight for. \n";
					} else {
						echo "Historically Flying on a Tuesday or a Wednesday". 
						     "yield the lowest fares. If your flight dates are".
						     "flexible we recommend choosing flights for one of those days. \n";


					}
					//ANALYSIS ON DAYS BEFORE FLIGHT (47 DAYS BEFORE FLIGHT = BEST DAY TO PURCHASE FLIGHT)
					if($diff == 47) {
						echo "Today is 47 days before your flight. According ".
						     "to past trends, we highly recommend purchasing ".
						     "your flight ticket today as it is the prime day.".
						     "for you to book your flight!\n";	
						echo "\n";		   						
					} else if($diff > 47 && $diff < 114) {
						//user has time to watch prices between 47 and 114 days before the flight
						$dayLeft = $diff - 47;
						echo "Today is ".$diff." days before your flight. According ".
						     "to past trends, we highly recommend checking fares ".
						     "often for your flight in the next ".$dayLeft." days as ".
						     "historically prices drop during this time. Our background ".
						     "search tool is a good way to keep track of any drop in price!\n";
						     
						echo "\n";	
					} else if($diff < 47 && $diff > 14) {
						//user needs to start booking because flights will not be getting much cheaper
						$days = $diff - 14;
						echo "You are reaching the end of the prime booking window ".
						     "for this flight. We recommend that you book your ".
						     "flight as soon as possible, and definitely within the ".
						     " next " .$days. " days, as historically, prices do not drop ".
						     " further.\n";
						echo "\n";	
					} else if($diff > 114) {
						//user is trying to book too early. It is hightly likely a better price will come about
						$daysRemain = $diff - 114;
						echo "It seems to be a little too early to book this flight. ".
						     "We would recommend waiting at least " .$daysRemain. 
						     " days until booking your flight that way you can book ".
						     "within the prime booking window.\n";
						echo "\n";	
					} else {
						//coming up on the flight in the next 2 weeks should book immediately
						if ($diff != 1){
						echo "Below are the current flight choices our search bot has found for you. We recommend booking as soon as possible since your flight is coming up very soon, and historically prices will not drop within the next " .$diff. " days.\n";
						echo "\n";
						}else {
							echo "Below are the current flight choices our search bot has found for you. We recommend booking as soon as possible since your flight is coming up very soon, and historically prices will not drop within the next " .$diff. " day.\n";
						echo "\n";
						}
					}
				}
			    ?>

			    </div>
			</div>
			<!--
			    provide a description for the user to 
			    understand how the background search works
			-->
			<div class="col-md-6" id="background-info">
			    <img class="exclamation" src="Pictures/exclamation.png" alt="Important" height="8%" width="8%" />
			    <p id="background-description">
				Our search engine can work in the background for you to find 
				deals on flights whose prices might change in the near future. 
				Provide us with your information below, and we will begin
				searching. Your current search parameters have not yet been
				entered into our system. By continuing your search, you and 
				the information you provide will be logged into our system 
				for future contact and log-in.
			    </p>

			    <!-- 
				this form submits the user email and search
				time to countdown.php, which initiates the 
				background search
			    -->
			    <form name="searchwindow" onsubmit="return check(email);" method="post" action="countdown.php">
				<div class="form-group form-inline">
				    <label for="email">Email
					<input id="email" type="email" name="email">
				    </label>

				    <label for="search_time">Search Time
					<select name="search_time" id="numHours"> 
					    <option value="0.01667">1 Min</option>
					    <option value="1">1 Hour</option>
					    <option value="2">2 Hours</option>
					    <option value="4">4 Hours</option>
					    <option value="8">8 Hours</option>
					    <option value="12">12 Hours</option>
					    <option value="24">24 Hours</option>
					    <option value="48">48 Hours</option>
					    <option value="72">72 Hours</option>
					    <option value="96">96 Hours</option>
					</select>
				    </label>

				    <?php
					/*
					 * hidden input values for the form
					 * that is sent to countdown
					 * for parameters in background
					 * search
					 */
					echo <<<_SECTION1
					<input type="hidden" name="origin" value="{$post['source']}"/>
					<input type="hidden" name="destination" value="{$post['destination']}"/>
					<input type="hidden" name="depart_date" value="{$post['depart_date']}"/>
_SECTION1;
					if (isset($post['return_date'])) echo "<input type=\"hidden\" name=\"return_date\" value=\"".$post['return_date']."\"/>";
					else echo "<input type=\"hidden\" name=\"return_date\" value=\"NULL\"/>";
					
					$oneWay = false;
					if (isOneWay($post))
					    $oneWay = true;
					echo <<<_SECTION2
					<input type="hidden" name="adults" value="{$post['adults']}"/>
					<input type="hidden" name="children" value="{$post['children']}"/>
					<input type="hidden" name="seniors" value="{$post['seniors']}"/>
					<input type="hidden" name="seat_infant" value="{$post['seat_infants']}"/>
					<input type="hidden" name="lap_infant" value="{$post['lap_infants']}"/>
					<input type="hidden" name="one_way" value="{$post['one_way']}"/>
_SECTION2;

					if (isset($post['airline']))
					{
					    foreach ($post['airline'] as $air)
						echo "<input type=\"hidden\" name=\"airline[]\" value=\"".$air."\"/>";
					} else
					    echo "<input type=\"hidden\" name=\"airline[]\" value=\"none\"/>";

					echo "<input type=\"hidden\" name=\"price\" value=\"".$post['price']."\"/>";
				    ?>

				    <input id="search-submit-button" class="btn btn-info btn-md" 
					 type="submit" value="Keep Searching"/>
				    
				</div>
			    </form>
			</div><!--end col-->
		    </div><!--end row-->
		    <hr>
		    
		    <?php
			/*
			 * the following section
			 * of code displays the 
			 * suggestions made by Hopper.com
			 * that provides analytic 
			 * suggestions for best times and places 
			 * to book flights
			 */
			$to = $post['source'];
			$from = $post['destination'];
			$java = 'java sample/Main ' . $to . ' ' . $from;
			$output = shell_exec($java);
			$myarray = array();

			if (!(strpos($output,'ERROR') !== false))
			{
			    $myArray = explode(', ', $output);

			    if (($myArray[2] != '') or ($myArray[3] != '') or 
				($myArray[4] != '') or ($myArray[5] != 0) or 
				($myArray[5+$myArray[5]+1] != '') or ($myArray[5+$myArray[5]+2] != ''))
			    {		
				echo <<<_TABLE1
				<h2>To Find the Best Price, Hopper.com suggests:</h2>
				<table class="table">
				    <tbody>
_TABLE1;

				if($myArray[2] != '')
				{
				    echo <<<_Row1
					<tr>
					    <td>A <b>Good Price</b> would be</td>
					    <td>{$myArray[2]} (per passenger)</td>
					</tr>
_Row1;
				} // if

				if($myArray[3] != '')
				{
				    echo <<<_Row2
					<tr>
					    <td>Try <b>Flying Out</b> on a</td>
					    <td>{$myArray[3]}</td>
					</tr>
_Row2;
				} // if

				if($myArray[4] != '') 
				{
				    echo <<<_Row3
					<tr>
					    <td>Try <b>Flying Back</b> on a</td>
					    <td>{$myArray[4]}</td>
					</tr>
_Row3;
				} // if

				if($myArray[5] != 0)
				{
				    echo <<<_Row4
					<tr>
					    <td>Try these <b>Airlines</b></td>
					    <td>
_Row4;
				    for ($x = 0; $x < $myArray[5]; $x++) 
				    {
					echo $myArray[6+$x];
					if($x+1 < $myArray[5])
					    echo ", ";
				    } // for

				    echo ('</td></tr>');	
				} // if

				if($myArray[5+$myArray[5]+1] != '')
				{
				    echo <<<_Row5
					<tr>
					    <td>Also look at flights <b>Departing From</b></td>
					    <td>{$myArray[5+$myArray[5]+1]}</td>
					</tr>
_Row5;
				} // if

				if($myArray[5+$myArray[5]+2] != '')
				{
				    echo <<<_Row6
					<tr>
					    <td>Also look at flights <b>Arriving Into</b></td>
					    <td>{$myArray[5+$myArray[5]+2]}</td>
					</tr>
_Row6;
				} // if

				echo <<<_TABLE2
				</tbody>
				</table>
				<p><a href="http://www.hopper.com/flights/from-{$myArray[0]}/to-{$myArray[1]}/guide" target="_blank" >See for Yourself!</a></p>
_TABLE2;
			    } // if for hopper table(has at least one row)
			} // endif

			/* 
			 * use getResults() and printResults()
			 * functions to perform Google QPX Express
			 * request and to display the response object 
			 * in  a table on the page
			 * (see flight_tracker.php)
			 * if no results were found, then
			 * print a message to notify user
			 */
			$result = getResults($post, 50, time());
			$trips = $result->getTrips();
			$rowCount = -1;
			if (count($trips->getTripOption()) <= 0)
			    echo "<h2>No Results Found</h2>";
			else
			    $rowCount = printResults($trips, $post);
		    ?>
		    <h4>
			If you were hoping to find
			more search results, then
			we recommend broadening your
			search parameters, particularly
			your maximum price range or
			your preferred airline.
		    </h4>

		</div>
		<div class="col-xs-4 col-md-1"></div>
	    </div>
	</div>

	<form id="hiddenForm" method="post" action="logout.php">
	    <input type="hidden" name="webpage" value="index.php" />
	</form>
    </body>

    <script>
	/*
	 * hide the hidden dropdown tables
	 * within the main table when the window loads
	 */
	window.onload=function(){$('.dropdown').hide();};
	<?php
	    /*
	     * generate javascript
	     * to handle the expansion and
	     * collapse of the dropdown tables 
	     * that are within the main table
	     * for each row in the table
	     */
	    if ($rowCount != -1) 
	    {
		for ($i = 0; $i < $rowCount; $i++)
		{
		    echo <<<_SECTION3
		    $(document).ready(function () {
			$('#btnExpCol{$i}').click(function () {
			    if ($(this).val() == 'Hide') {
				$('#row{$i}').stop().slideUp('3000');
				$(this).val('Show');
			    } else {
				$('#row{$i}').stop().slideDown('3000');
				$(this).val('Hide');
			    }
			});
		    });
_SECTION3;
		} // for
	    } // if
	?>

    </script>
</html>

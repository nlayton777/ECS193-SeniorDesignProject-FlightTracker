<!DOCTYPE html>
<html>
    <head>
	<title>UCD Flight Tracker | Search</title>

	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="stylesheet" href="bootstrap.css"/>
	<script src="jquery-2.1.3.js"/></script>
	<script src="bootstrap.js"></script>
	<link rel="stylesheet" href="styles.css"/>


	<script>

	    //  VALIDATION

function check(mail) 
{

	var currentSecs = new Date().getTime()/1000; //get time right now in seconds 
	    
    var searchTime = document.getElementById("numHours").value;
    var searchSecs = searchTime * 60 * 60; //search window time in seconds 

    var CurrentSearchSecs = currentSecs + searchSecs;

    
   //get Departure date and convert to seconds 
	 <?php 
	 	require_once('./flight_tracker.php');

		$post = $_POST;
		$departureDate = $post['depart_date'];
		$departureDate = strtotime($departureDate);
	 	echo "var departSecs = \"{$departureDate}\";";
	 ?>  

 if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(searchwindow.email.value))
  {
		if((CurrentSearchSecs) > departSecs)
		{
			alert("Please choose a search time that will complete before your departure date.")
			return false;
		}
		return(true);
  }
  else
  {
  	//alert(departSecs);
  	alert("You have entered an invalid email address!")
    return (false)
  }
    
}//end  check() --  validation for email and search time

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

			<li class="active"><a href="index.php">Find a Flight</a></li>
			<?php
			    // if session is set
				//echo "<li><a href=\"results.php\">My Search</a></li>";
			    // else
				echo "<li><a href=\"signin.php\">My Search</a></li>";
			?>
			<li><a href="about.php">About</a></li>
		    </ul>
		    <ul class="nav navbar-nav navbar-right">
			<li><a href="contact.php">Contact</a></li>
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
				require_once('./flight_tracker.php');

				$post = $_POST;
				echo "<h3 id=\"trip-title\">" . $post['depart_date'] . "  <strong>" . 
				    $post['source'] . "</strong> " . (isOneWay($post) ? "&rarr; " : "&harr; ") .
				    "<strong>" . $post['destination'] . "</strong>  ";
				if (!isOneWay($post))
				    echo $post['return_date'];
				echo "</h3>";
			    ?>

			    <div>
			    	<h3 style="color:red">Price Analysis Hints:</h3>
			    	<?php
			    		$currentDate = date('m/d/Y');

			    		$departDate = $post['depart_date'];
			    		$returnDate = $post['return_date'];

			    		$timeNow = strtotime($currentDate);
			    		$timeDepart = strtotime($departDate);
			    		$timeReturn = strtotime($returnDate);

			    		$departDay = date("N", $timeDepart);
			    		$returnDay = date("N", $timeReturn);

			    		$diff = abs($timeDepart - $timeNow);
			    		
			    		$diff /= 60 * 60 * 24; 

			    		// MOST OPTIMAL SITUATION 
			    		if((($departDay == 2 || $departDay == 3) && ($returnDay == 2 || $returnDay == 3)) && $diff == 47)
			    		{
			    			echo "You are planning on travelling on the best priced days of the week, and according to historic trends today is the prime day to book your tickets for the lowest price. We recommend you book your tickets now!";
			    		}

			    		//check for individual cases
			    		else
			    		{
				    		//ANALYSIS ON DAY OF WEEK (TUES AND WED ARE BEST DAYS TO TRAVEL ON)
				    		if(($departDay == 2 || $departDay == 3 || $departDay == 6) && ($returnDay == 2 || $returnDay == 3 || $returnDay == 6))
				    		{
				    			if($departDay == 2)
								{
									$dow = "Tuesday";
								}
								else if ($departDay == 3)
								{
									$dow = "Wednesday";
								}
								else if ($departDay == 6)
								{
									$dow = "Saturday";
								}
								if($returnDay == 2)
								{
									$dow2 = "Tuesday";
								}
								else if ($returnDay == 3)
								{
									$dow2 = "Wednesday";
								}
								else if ($returnDay == 6)
								{
									$dow2 = "Saturday";
								}
				    			echo "You are currently planning on departing on a " .$dow. " and are planning to return on " . $dow2. ". These are historically the best priced days of the week to purchase a flight for. \n";
				    		}

				    		else if($departDay == 2 || $departDay == 3 || $departDay == 6)
				    		{
				    			if($departDay == 2)
								{
									$dow = "Tuesday";
								}
								else if ($departDay == 3)
								{
									$dow = "Wednesday";
								}
								else if ($departDay == 6)
								{
									$dow = "Saturday";
								}
				    			echo "You are currently planning on departing on a " .$dow. ". This is historically the best priced day of the week to purchase a flight for. \n";
				    		}

				    		else if($returnDay == 2 || $returnDay == 3|| $returnDay == 6)
				    		{
				    			if($returnDay == 2)
								{
									$dow = "Tuesday";
								}
								else if ($returnDay == 3)
								{
									$dow = "Wednesday";
								}
								else if ($returnDay == 6)
								{
									$dow = "Saturday";
								}
				    			echo "You are currently planning on returning on a " .$dow. ". This is historically the best priced day of the week to purchase a flight for. \n";
				    		} 
				    		else
				    		{
				    			echo "If you are able to be flexible with your dates and would like to find the lowest priced flight option possible, we recommend flying on a Saturday, Tuesday, or Wednesday.";
				    		}

				    		//ANALYSIS ON DAYS BEFORE FLIGHT (47 DAYS BEFORE FLIGHT = BEST DAY TO PURCHASE FLIGHT)
				    		if($diff == 47)
				    		{
				    			echo "According to past trends, we recommend purchasing your flight ticket today because it is the prime day for you to book your flight!\n";	
				    			echo "\n";		   						
				    		}

				    		else if($diff > 47 && $diff < 114)
				    		{
				    			$dayLeft = 114 - $diff;
				    			echo "This is the prime booking window. We recommend that you shold book your flight in the next ", $dayLeft," days before prices begin to rise.\n";
				    			echo "\n";	
				    		}

				    		else if($diff < 47 && $diff > 14)
				    		{
				    			$days = $diff - 14;
				    			echo "You are reaching the end of the prime booking window for this flight. We recommend that you should book your flight within the next " .$days. " days before prices begin to rise.\n";
				    			echo "\n";	
				    		}
				    		else if($diff > 114)
				    		{
				    			$daysRemain = $diff - 114;
				    			echo "It seems to be a little too early to book this flight. We would recommend waiting at least " .$daysRemain. " days until booking your flight that way you can book within the prime booking window.\n";
				    			echo "\n";	
				    		}
				    		else
				    		{
				    			echo "Below are the current flight choices our search bot has found for you. We recommend booking soon since your flight is coming up very soon.\n";
				    			echo "\n";	
				    		}
				    	}

			    	?>


			    </div>
			</div>

			<div class="col-md-6" id="background-info">
			    <img id="exclamation" src="exclamation.png" alt="Important" height="8%" width="8%" />

			    <p id="background-description">
				Our search engine can work in the background for you to find 
				deals on flights whose prices might change in the near future. 
				Provide us with the following information, and we will begin
				searching.
			    </p>

			    <form name="searchwindow" onsubmit="return check(email);" method="post" action="countdown.php">
				<div class="form-group form-inline">
				    <label for="email">Email
					<input id="email" type="email" name="email">
				    </label>

				    <label for="search-time">Search Time
					<select name="search_time" id="numHours"> 
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
					echo <<<_SECTION1
					<input type="hidden" name="origin" value="{$post['source']}"/>
					<input type="hidden" name="destination" value="{$post['destination']}"/>
					<input type="hidden" name="depart_date" value="{$post['depart_date']}"/>
_SECTION1;
					if (isset($post['return_date']))
					    echo "<input type=\"hidden\" name=\"return_date\" value=\"".$post['return_date']."\"/>";
					else
					    echo "<input type=\"hidden\" name=\"return_date\" value=\"NULL\"/>";
					echo <<<_SECTION2
					<input type="hidden" name="adults" value="{$post['adults']}"/>
					<input type="hidden" name="children" value="{$post['children']}"/>
					<input type="hidden" name="seniors" value="{$post['seniors']}"/>
					<input type="hidden" name="seat_infant" value="{$post['seat_infants']}"/>
					<input type="hidden" name="lap_infant" value="{$post['lap_infants']}"/>
_SECTION2;

					foreach ($post['airline'] as $air)
					    echo "<input type=\"hidden\" name=\"airline[]\" value=\"".$air."\"/>";

					echo "<input type=\"hidden\" name=\"price\" value=\"".$post['price']."\"/>";
				    ?>

				    <input id="search-submit-button" class="btn btn-info btn-md" 
					 type="submit" value="Keep Searching"/>
				    
				</div>
			    </form>
			</div><!--end col-->
		    </div><!--end row-->

		    <?php
			$result = getResults($post, 50);
			$trips = $result->getTrips();

			if (count($trips->getTripOption()) <= 0)
			    echo "<h2>No Results Found</h2>";
			else
			    $rowCount = printResults($trips, $post);
		    ?>
		    <h4>
			<strong>*NOTE:</strong> 
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
    </body>

    <script>
	window.onload=function(){$('.dropdown').hide();};

	<?php
	    for ($i = 0; $i < $rowCount; $i++)
	    {
		echo <<<_SECTION3
		$(document).ready(function () {
		    $('#btnExpCol{$i}').click(function () {
			if ($(this).val() == 'Collapse') {
			    $('#row{$i}').stop().slideUp('3000');
			    $(this).val(' Expand ');
			} else {
			    $('#row{$i}').stop().slideDown('3000');
			    $(this).val('Collapse');
			}
		    });
		});
_SECTION3;
	    } // for
	?>

    </script>
</html>

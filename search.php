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
    </head>

    <body>
	<nav class="navbar navbar-inverse ">
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
			<li class="active">
			    <a href="index.php">Search</a>
			</li>
			<li>
			    <a href="about.php">About</a>
			</li>
		    </ul>
		    <ul class="nav navbar-nav navbar-right">
			<li>
			    <a href="contact.php">Contact</a>
			</li>
		    </ul>
		</div>
	    </div>
	</nav>

	<div class="container-fluid" id="searchheader">
	    <div class="row">
		<div class="col-xs-4 col-md-1"></div>
		<div class="col-xs-10 col-md-10">
		    <h1>Search Results</h1>
		    <h3>Our search bot found these travel options just for you!</h3>	
		    <table id="results" class="table table-hover" style="background-color: rgba(200, 200, 200, 0.3)">
			<tr>
			    <th>Price</th>
			    <th colspan='6'>Itinerary</th>
			    <th></th>
			</tr>

<?php
			    define('__ROOT3__',dirname(__FILE__));
			    require_once(__ROOT3__ .
				'/google-api-php-client/src/Google/Service/QPXExpress.php');
			    require_once(__ROOT3__ .
				'/google-api-php-client/src/Google/Client.php');
			    require_once(__ROOT3__ . '/FlightTracker.php');

			    // post request from index
			    $post = $_POST;
			    $result = getResults($post);
			    $trips = $result->getTrips();

			    foreach ($trips->getTripOption() as $option) 
			    {
				echo "<tr>";
				    echo "<td>";
					echo "\$" . $option->getSaleTotal();
				    echo "</td>";
				    echo "<td>";
					echo $option->getSlice()[0]->getSegment()[0]->getLeg()[0]->getDepartureTime() . " ";
					echo "<strong>" . $option->getSlice()[0]->getSegment()[0]->getLeg()[0]->getOrigin() . "</strong>";
					echo "&rarr;";
					$temp = $option->getSlice()[0]->getSegment();
					$temp2 = $temp[count($temp) - 1]->getLeg();
					echo $temp2[count($temp2) - 1]->getArrivalTime() . " ";
					echo "<strong>" . $temp2[count($temp2) - 1]->getDestination() . "</strong>";

					echo "<div id=\"dropdown\">"
					    echo "<h3>On the way there:</h3>";
					    echo "<table cellspacing=\"10\" cellpadding=\"0\" id=\"expandedtable\" class=\"droptable\">";
						echo "<tr>";
						    echo "<th>Stop Number</th>";
						    echo "<th>Point A</th>";
						    echo "<th>Departure Time</th>";
						    echo "<th>Point B</th>";
						    echo "<th>Arrival Time</th>";
						    echo "<th></th>";
						echo "</tr>";
						echo "<tr>";
						
						foreach ($option-getSlice()[0]->getSegment() as $segment)
						{
						    foreach ($segment->getLeg() as $leg)
						    {

						    }
						}

						echo "</tr>";
					    echo "</table>"
					    echo "<h3>On the way back:</h3>";
					    echo "<table cellspacing=\"10\" cellpadding=\"0\" id=\"expandedtable\" class=\"droptable\">";
						echo "<tr>";
						    echo "<th>Stop Number</th>";
						    echo "<th>Point A</th>";
						    echo "<th>Departure Time</th>";
						    echo "<th>Point B</th>";
						    echo "<th>Arrival Time</th>";
						echo "</tr>";
						echo "<tr>";

						echo "</tr>";
					    echo "</table>"
					echo "</div>";
				    echo "</td>";
				echo "</tr>";
			    }
?>
		    </table>
		</div>
		<div class="col-xs-4 col-md-1"></div>
	    </div>
	</div>
    </body>
</html>

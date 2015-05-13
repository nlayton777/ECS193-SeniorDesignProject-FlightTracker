<?php
session_start();
ini_set('session.gc_maxlifetime', 60 * 60 * 1);

// if already logged in
if (isset($_SESSION['id']) && isset($_SESSION['email']))
{
    $id = $_SESSION['id'];
    $email = $_SESSION['email'];
} // else if not logged in....
else if (isset($_POST['id']) && isset($_POST['email']))
{
    session_unset();
    $_SESSION['id'] = $_POST['id'];
    $_SESSION['email'] = $_POST['email'];
    $id = $_POST['id'];
    $email = $_POST['email'];
}
?>

<!DOCTYPE html>
<html>
    <head>
	<title>UCD Flight Tracker | Search Results</title>
	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="stylesheet" href="bootstrap.css"/>
	<script src="jquery-2.1.3.js"/></script>
	<script src="bootstrap.js"></script>
	<link rel="stylesheet" href="flipclock.css"/>
	<script src="flipclock.min.js"></script>
	<link rel="stylesheet" href="styles.css"/>
	<script src="countdownClock.js"></script>
	<script src="Chart.js"></script>
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
			<li><a href="index.php">Find a Flight</a></li>
			<li class="active"><a href="results.php">My Search</a></li>
			<li><a href="about.php">About</a></li>
		    </ul>
		    <ul class="nav navbar-nav navbar-right">
			<li><a href="contact.php">Contact</a></li>
			<li><a href="logout.php">Log Out</a></li>
		    </ul>
		</div>
	    </div>
	</nav>

	<div class="container-fluid" id="searchheader">
	    <div class="row">
		<div class="col-xs-4 col-md-1"></div><!--end col-->
		<div class="col-xs-10 col-md-10">
		    <div class="row">
			<div class="col-md-6" id="search-title">
			<?php echo "<h1>Request #{$id}<h1>"; ?>
			</div><!--end col-->

			<div class="col-md-6" id="background-info">
			    <img class="exclamation" src="exclamation.png" alt="Important" height="8%" width="8%" />

			    <?php
				require_once './flight_tracker.php';
				$connection = new mysqli ("localhost", "root");
				if ($connection->connect_error) die($connection->connect_error);
				$connection->select_db("flight_tracker");

				// GET SEARCH PARAMETERS
				$query = <<<_QUERY
				    SELECT *
				    FROM searches
				    WHERE
					id = {$id} AND
					email = '{$email}';
_QUERY;
				$searchResults = $connection->query($query);
				if (!$searchResults) die ($searchResults->connect_error);

				// GET AIRLINES
				$query = <<<_AIRLINES
				    SELECT airline
				    FROM airlines
				    WHERE search_id = {$id} AND
					email = '{$email}';
_AIRLINES;
				$airlineResults = $connection->query($query);
				if (!$airlineResults) die ($airlineResults->connect_error);
				$airlines = array();
				$n = $airlineResults->num_rows;
				for ($i = 0; $i < $n; ++$i)
				{
				    $airlineResults->data_seek($i);
				    $row = $airlineResults->fetch_array(MYSQLI_ASSOC);
				    $airlines[] = $row['airline'];
				} // for each airline

				$searchResults->data_seek(0);
				$row = $searchResults->fetch_array(MYSQLI_ASSOC);
				$d = explode("-", $row['depart_date']);
				$d = implode("/", array($d[1], $d[2], $d[0]));
				if (!isOneWay($row))
				{
				    $r = explode("-", $row['return_date']);
				    $r = implode("/", array($r[1], $r[2], $r[0]));
				} else
				    $r = "";
				$obj = array("id"	    => $id,
					     "email"	    => $email,
					     "source"	    => $row['origin'],
					     "destination"  => $row['destination'],
					     "depart_date"  => $d,
					     "return_date"  => $r,
					     "adults"	    => $row['adults'],
					     "children"	    => $row['children'],
					     "seniors"	    => $row['seniors'],
					     "seat_infants" => $row['seat_infant'],
					     "lap_infants"  => $row['lap_infant'],
					     "airline"	    => $airlines,
					     "price"	    => $row['price'],
					     "one_way"	    => $row['one_way'],
					    );

				$result = getResults($obj, 50, time());
	
				$remaining = getRemainingTime($id,$email);
				if ($remaining > 0){
				    echo <<<_DES
					<p id="background-description">
					    Your search is still in progress. The graph below shows
					    the progression of flight prices since the start of your 
					    search. If you think prices might continue to change and 
					    would like to wait for potentially better results, 
					    then revisit your search at a later time. If any of the
					    search results below appeal to you, then click the Book
					    It button, and we stop your background search and take you
					    to a booking page. If you are completely dissatisfied, then 
					    we encourage you to begin a new search by entering new
					    parameters at our <a href="index.php">Search Page</a>.
					</p>
_DES;
				} else {
				    echo <<<_DES2
					<p id="background-description">
					    Your search is complete! The graph below shows
					    the progression of flight prices since the start of your 
					    search. If any of the search results below appeal to you, 
					    then click the Book It! button, and we stop your background 
					    search and take you to a booking page. If you are completely 
					    dissatisfied with the results, then we encourage you to begin 
					    a new search by entering new parameters at our 
					    <a href="index.php">Search Page</a>.
					</p>
_DES2;
				}
		    ?>
			</div><!--end col-->
		    </div><!--end row-->


		    <h2>Search Time Remaining</h2>
		    <div class="clock"></div>
		    <?php
			echo <<<_SCRIPT
			    <script>
				CountdownClock({$remaining})
				var remaining = {$remaining};
			    </script>
_SCRIPT;
			$data = getGraphData($id, $email);
		    ?>

		    <h2>Graph of Prices</h2>
		    <canvas id="buyers" width="400" height="400"></canvas>
		    <script>
			var canv = document.getElementById('buyers')
			var parentWidth = canv.parentNode.offsetWidth;
			canv.setAttribute('width', parentWidth);

			var buyers = document.getElementById('buyers').getContext('2d');
			var buyerData = {
			    labels : ["Start", <?php echo implode(",", $data['labels']); ?>],
			    datasets : [
				{
				    fillColor : "rgba(94, 71, 99, 0.4)",
				    strokeColor : "#5e4763",
				    pointColor : "#fff",
				    pointStrokeColor : "#413145",
				    data : [0, <?php echo implode(",", $data['data']); ?>]
				}
			    ]
			};
			var options = {
			    scaleShowGridLines: true,
			    bezierCurve: false
			};
			var lastRender = Math.floor(Date.now() / 1000);
			var myChart = new Chart(buyers).Line(buyerData, options);
		    </script>

		    <h2>Search Results</h2>
		    <?php
			$trips = $result->getTrips();
			$rowCount = -1;
			if (count($trips->getTripOption()) <= 0)
			    echo "<h2>No Results Founds</h2>";
			else
			    $rowCount = printResults($trips, $obj);

			$end = $row['end'];
			$end = explode(" ", $end);
			$endDate = explode("-", $end[0]);
			$endTime = explode(":", $end[1]);
			$end_secs = mktime($endTime[0], $endTime[1], $endTime[2], 
					   $endDate[1], $endDate[2], $endDate[0]);
			echo "<script>var end = {$end_secs};</script>";
		    ?>
		</div><!--end col-->
		<div class="col-xs-4 col-md-1"></div><!--end col-->
	    </div><!--end row-->
	</div><!--end div container-->
    </body>

    <script>
	var id = <?php echo $id; ?>;
	var email = "<?php echo $email; ?>";
	var seconds = 3;
	var str = "id=" + id + "&email=" + email + "&time=" + lastRender;
	var xmlhttp;
	var description = "Your search is complete! " +
	    "The graph below shows the progression of flight prices " + 
	    "since the start of your search. If any of the search " + 
	    "results below appeal to you, then click the Book It! " +
	    "button, and we stop your background search and take you " +
	    "to a booking page. If you are completely dissatisfied with " +
	    "the results, then we encourage you to begin a new search " + 
	    "by entering new parameters at our <a href=\"index.php\">" +
	    "Search Page</a>.";
	    /*
	if (window.XMLHttpRequest)
	{ xmlhttp = new XMLHttpRequest(); }
	else
	{ xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); }

	xmlhttp.onreadystatechange = function() {
	    if (xmlhttp.readyState == 4 && xmlhttp.status == 200 && remaining > 0)
	    {
		var dat = xmlhttp.responseText;
		var newData = dat.split(",");
		var price = newData[1].split("+");
		myChart.addData([price[0]], newData[0])
		document.getElementById("test").innerHTML = xmlhttp.responseText;
		lastRender = Math.floor(Date.now() / 1000);
	    }
	} // onreadysatechange

	*/
	window.setInterval(function () {
	    if (Math.floor(Date.now() / 1000) >= end) {
		document.getElementById("background-description").innerHTML = description;
	    } // if search over

/*
	    str = "id=" + id + "&email=" + email + "&lastQuery=" + lastRender;
	    xmlhttp.open("GET","retrieve.php?" + str,true);
	    xmlhttp.send();
	    */
	}, seconds * 1000); // get AJAX

	window.onload=function(){$('.dropdown').hide();};
	<?php
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

<?php
session_start();

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
/*
$id = 399;
$email = 'nllayton@ucdavis.edu';
*/
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
			<li><a href="signin.php">Log Out</a></li>
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
			    <img id="exclamation" src="exclamation.png" alt="Important" height="8%" width="8%" />

			    <?php
				require_once './flight_tracker.php';
				$remaining = getRemainingTime($id,$email);
				if ($remaining > 0){
				    echo <<<_DES
					<p id="background-description">
						You can choose to either continue your search if 
						you would like for us to keep searching or 
						terminate the search by selecting one of the options 
						below.
					</p>
_DES;
				} else {
				    echo <<<_DES2
					<p id="background-description">
					    Your search is complete! You can either choose one of the options
					    below, or start a new search from our <a href="index.php">
					    Search Page</a>.
					</p>
_DES2;
				}
		    ?>
			</div><!--end col-->
		    </div><!--end row-->

		    <h2>Search Time Remaining</h2>
		    <div class="clock"></div>
		    <?php
			echo "<script>CountdownClock({$remaining})</script>";
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
			    labels : [<?php echo implode(",", $data['labels']); ?>],
			    datasets : [
				{
				    fillColor : "rgba(94, 71, 99, 0.4)",
				    strokeColor : "#5e4763",
				    pointColor : "#fff",
				    pointStrokeColor : "#413145",
				    data : [<?php echo implode(",", $data['data']); ?>]
				}
			    ]
			};
			var options = {
			    scaleShowGridLines: true
			};
			new Chart(buyers).Line(buyerData, options);
		    </script>

		    <h2>Search Results</h2>
		    <?php
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

			$result = getResults($obj, 50);
			$trips = $result->getTrips();
			if (count($trips->getTripOption()) <= 0)
			    echo "<h2>No Results Founds</h2>";
			else
			    $rowCount = printResults($trips, $obj);
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
	var count = 0;

/*
	window.setInterval(function () {
	    if (remaining <= 0)
	    {
		document.getElementById("background-description").innerHTML = "Your search is complete! You can either choose one of the options below, or start a new search from our <a href=\"index.php\">Search Page.</a>";
	    }

	    var xmlhttp;
	    if (window.XMLHttpRequest)
	    { xmlhttp = new XMLHttpRequest(); }
	    else
	    { xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); }
	    xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
		{
		    //document.getElementById("test").innerHTML = xmlhttp.responseText;
		    xmldoc = xmlhttp.responseXML;
		    var options = xmldoc.getElementsByTagName("OPTION"); 
		    count = options.length;
		    var table = document.getElementById("results");
		    var i = 1;
		    var option;

		    for (option in options)
		    {
			var row = table.insertRow(i);
			var cell1 = row.insertCell(0);
			var cell2 = row.insertCell(1);
			var cell3 = row.insertCell(2);

			cell1.innerHtml = "NEW CELL 1";
			cell1.innerHtml = "NEW CELL 1";
			cell1.innerHtml = "NEW CELL 1";

			i++;
		    } // for
		}
	    }
	    var str = "id=" + id + "&email=" + email + "&count=" + count;
	    xmlhttp.open("GET","retrieve.php?" + str,true);
	    xmlhttp.send();
	},seconds * 1000); // get AJAX

	function displayResults(xml)
	{
	} // displayResults()
	*/

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

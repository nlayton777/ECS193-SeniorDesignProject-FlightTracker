<?php
//session_start();
$_SESSION['id'] = 278;
$_SESSION['email'] = "nllayton@ucdavis.edu";

$sesh = $_SESSION;
$session_flag = false;
if (isset($sesh['id']) && isset($sesh['email']))
{
    $id = $sesh['id'];
    $email = $sesh['email'];
    $session_flag = true;
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
	<script src="flight_tracker.js"></script>

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
			<li><a href="index.php">Search</a></li>
			<li class="active"><a href="results.php">Search Status</a></li>
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
		<div class="col-xs-4 col-md-1"></div><!--end col-->
		<div class="col-xs-10 col-md-10">
		    <div class="row">
			<div class="col-md-6" id="search-title">
			<?php echo "<h1>Search Results For Request #{$id}<h1>"; ?>
			</div><!--end col-->

			<div class="col-md-6" id="background-info">
			    <img id="exclamation" src="exclamation.png" alt="Important" height="8%" width="8%" />

			    <p id="background-description">
				Our search engine can work in the background for you to find 
				deals on flights whose prices might change in the near future. 
				Provide us with the following information, and we will begin
				searching.
			    </p>

			</div><!--end col-->
		    </div><!--end row-->

		    <h3>Search Time Remaining</h3>
		    <div class="clock"></div>
		    <?php
			require_once 'login.php';

			$connection = new mysqli ($db_hostname, $db_username);
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
			$end = $result->fetch_assoc()['end'];
			$day_time = explode(" ",$end);
			$day = explode("-",$day_time[0]);
			$clock = explode(":",$day_time[1]);
			$remaining = (mktime($clock[0], $clock[1], $clock[2], $day[1], $day[2], $day[0]) - time()) / 60;

			if ($remaining > 0)
			{
			    echo "<script>CountdownClock({$remaining})</script>";
			    echo <<<_STUFF
				<h5>
				    You can choose to either continue your search if 
				    you would like for us to keep searching or 
				    terminate the search by selecting one of the options 
				    below.
				<h5>
_STUFF;
			} else {
			    echo "<script>CountdownClock(0)</script>";
			    echo <<<_STUFF2
				<h5>
				    Your search is complete! You can either choose 
				    one of the options shown below, or you can start another 
				    search at the <a href="index.php">Search</a> page.
				<h5>";
_STUFF2;
			}
		    ?>
		</div><!--end col-->
		<div class="col-xs-4 col-md-1"></div><!--end col-->
	    </div><!--end row-->
	</div><!--end div container-->
    </body>

    <script>
	window.onload=function(){$('.dropdown').hide();};

	<?php
	    for ($i = 0; $i < $rowCount; $i++)
	    {
		echo <<<_SECTION1
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
_SECTION1;
	    } // for
	?>

    </script>
</html>

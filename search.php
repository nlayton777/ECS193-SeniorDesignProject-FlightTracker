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

	<div class="container-fluid" id="searchheader">
	    <div class="row">
		<div class="col-xs-4 col-md-1"></div>
		<div class="col-xs-10 col-md-10">
		    <h1>Search Results</h1>

			<?php
			    define('__ROOT3__',dirname(__FILE__));
			    require_once(__ROOT3__ .
				'/google-api-php-client/src/Google/Service/QPXExpress.php');
			    require_once(__ROOT3__ .
				'/google-api-php-client/src/Google/Client.php');
			    require_once(__ROOT3__ . '/flight_tracker.php');

			    // post request from index
			    $post = $_POST;
			    $result = getResults($post);
			    $trips = $result->getTrips();
			    $rCount = printResults($trips, $post);
			?>

		    </table>
		</div>
		<div class="col-xs-4 col-md-1"></div>
	    </div>
	</div>
    </body>
    <script>
	window.onload=function(){$('.dropdown').hide();};
	<?php
	for ($i = 0; $i < $rCount; $i++)
	{
	    echo "$(document).ready(function () {";
		echo "$('#btnExpCol$i').click(function () {";
		    echo "if ($(this).val() == 'Collapse') {";
			echo "$('#row$i').stop().slideUp('3000');";
			echo "$(this).val(' Expand ');";
		    echo "} else {";
			echo "$('#row$i').stop().slideDown('3000');";
			echo "$(this).val('Collapse');";
		    echo "}";
		echo "});";
	    echo "});";
	}
	?>
    </script>
</html>

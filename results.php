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
			<?php
				echo "<h1>Search Results For Request #----</h1>";
			?>
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
			$time = 0;
			echo "<script>CountdownClock({$time})</script>";
			if ($time > 0)
			{
			    echo "<h5>You can choose to either continue your search if ".
				 "you would like for us to keep searching or ".
				 "terminate the search by selecting one of the options ".
				 "below.".
				 "<h5>";
			} else {
			    echo "<h5>Your search is complete! You can either choose ".
				 "one of the options shown below, or you can start another ".
				 "search at the <a href=\"index.php\">Search</a> page.".
				 "<h5>";
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

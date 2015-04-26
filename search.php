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
function check()
{
	//check email


    
    if (document.layers||document.getElementById||document.all)
    {

		var str=document.searchwindow.email.value
    	var filter=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
    	if (filter.test(str))
			var testresults=true
    	else{
			alert("Please input a valid email address!")
			return false;
			}
	}
    	
	//validation search time is after depart date
 //    var currentSecs = new Date().getTime()/1000; //get time right now in seconds 
	    
 //    var searchTime = document.getElementById("numHours").value;
 //    var searchSecs = searchTime * 60 * 60; //search window time in seconds 

 //    var CurrentSearchSecs = currentSecs + searchSecs;

    
 //   //get Departure date and convert to seconds 
	 <?php 
	 	require_once('./flight_tracker.php');

		$post = $_POST;
	// 	echo "var departureDate = \"{$post['depart_date']}\";";
	 ?>   


	// var departSecs = getDateFromFormat(departureDate, "MM/DD/YYYY");

	// departSecs = departSecs/1000;

	// if((currentSecs + searchSecs) > departSecs)
	// {
	// 	alert("Please choose a search time that will complete before your departure date.")
		
	// }

}
	//end  validation
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
			<li><a href="results.php">Search Status</a></li>
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
			
				
				echo "<h3 id=\"trip-title\">" . $post['depart_date'] . "  <strong>" . 
				    $post['source'] . "</strong> " . (isOneWay($post) ? "&rarr; " : "&harr; ") .
				    "<strong>" . $post['destination'] . "</strong>  ";
				if (!isOneWay($post))
				    echo $post['return_date'];
				echo "</h3>";
			    ?>
			</div>

			<div class="col-md-6" id="background-info">
			    <img id="exclamation" src="exclamation.png" alt="Important" height="8%" width="8%" />

			    <p id="background-description">
				Our search engine can work in the background for you to find 
				deals on flights whose prices might change in the near future. 
				Provide us with the following information, and we will begin
				searching.
			    </p>

			    <form name="searchwindow" onsubmit="return check();" method="post" action="countdown.php">
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

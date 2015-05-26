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
				echo "</h3>";
			    ?>
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

<?php
/*
 * this file is the homepage of the website.
 * it provides fields for the user to submit
 * their travel preferences and submit this info
 * to search.php
 */

/*
 * start a session,
 * set its lifetime to an hour,
 * set a flag for later use that
 * indicates whether the user is 
 * logged into a session
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
	<title>SoFly | Search</title>

	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="stylesheet" href="bootstrap.css"/>
	<script src="jquery-2.1.3.js"/></script>
	<script src="bootstrap.js"></script>

	<!--this is for the datepicker()-->
	<link rel="stylesheet" href="jquery-ui.css"/>
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/ui-darkness/jquery-ui.css">
  	<script src="jquery-ui.js"></script>

	<!--this is for checkbox list-->
	<script type="text/javascript" src="bootstrap-multiselect.js"></script>
	<link rel="stylesheet" href="bootstrap-multiselect.css" type="text/css"/>

	<!--this is for our slider-->	
	<link href="jquery.nouislider.css" rel="stylesheet">
	<script src="jquery.nouislider.js"></script>
	<script src="jquery.liblink.js"></script>
	
	<!--this is for alerts-->
	<script src="bootbox.js"></script>

	<!--this is our js and css file-->
	<script type="text/javascript" src="flight_tracker.js"></script>
	<link rel="stylesheet" href="styles.css"/>	 	
    </head>

    <body>
	<!-- 
	    the nav element acts as the 
	    navigation bar at the top of the screen
	-->
	<nav class="navbar navbar-inverse">
	    <div class="container-fluid">
		<div class="navbar-header">
		    <button type="button" class="navbar-toggle" 
		     data-toggle="collapse" data-target="#mynavbar">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		    </button>
		    <a class="navbar-brand" href="index.php">SoFly</a>
		</div>

		<!--
		    the collapse allows the navbar
		    to expand and collapse when the 
		    screen width is small enough
		-->
		<div class="collapse navbar-collapse" id="mynavbar">
		    <ul class="nav navbar-nav">
			<li class="active">
			    <a href="index.php">Find a Flight</a>
			</li>
			<?php
			    /*
			     * if session was started, then the user
			     * is already logged in, so take them straight
			     * to the results page.
			     * 
			     * if session not started, then take user
			     * to login page
			     */
			    if ($seshFlag)
			    {
				echo "<li>
				          <a href=\"results.php\">My Search</a>
				      </li>";
			    } else 
			    {
				echo "<li>
				          <a href=\"signin.php\">My Search</a>
				      </li>";
			    }
			?>
			<li><a href="about.php">About</a></li>
		    </ul>
		    <ul class="nav navbar-nav navbar-right">
			<li><a href="contact.php">Contact</a></li>

			<?php
			    /*
			     * if user is logged in, then
			     * print "Log Out" rather than 
			     * "Log In", else print "Log In"
			     */
			    if ($seshFlag)
			    {
				echo "<li>
				          <a href=\"javascript:;\" onclick=\"submitForm();\">
					      Log Out
					  </a>
				      </li>";
			    } else 
			    {
				echo "<li>
				          <a href=\"signin.php\">Log In</a>
				      </li>";
			    }
			?>

		    </ul>
		</div>
	    </div>
	</nav>

	<!-- 
	    the header div element contains all 
	    of the search fields
	-->
	<div class="container-fluid" id="header">
	    <header class="jumbotron" id="home">
		<h1>SoFly Travel</h1>
		<h3>Customize your travel needs!</h3>

		<!--
		    this form submits a post
		    request to the search.php
		    page to be processed for 
		    displaying search results
		-->
		<form id="search_form" class="form-vertical" 
		      method="post"    action="search.php" >
		    <!-- 
			the div below contains
			a checkbox that indicates 
			whether the user is planning
			a one-way trip or round trip
		    -->
		    <div class="form-group">
			<label class="no-indent" for="oneway">
			    <input type="checkbox" value="1" 
			     onclick="OneWay()" id="oneway" name="one_way" 
			     form="search_form"/>

			    <input class="form-control" type="hidden" value="0" 
			     id="onewayHidden" name="one_way"
			     form="search_form" checked/>

			    One Way 
			</label>
		    </div>

		    <!--
			the div below contains 
			the fields for origin, 
			destination, departure date, 
			and return date (if the user
			is planning a round trip)
		    -->
		    <div class="row">
			<div class="form-group form-inline">
			    <!--ORIGIN FIELD-->
			    <label for="source" class="sr-only" required >
				Departure Location
			    </label>
			    <input type="text" class="form-control text-field" 
			     id="source" name="source" placeholder="Origin"/>	
				
			    <!--DESTINATION FIELD-->
			    <label for="destination" class="sr-only">
				Arrival Location
			    </label>
			    <input type="text" class="form-control text-field" 
			     id="destination" name="destination" placeholder="Destination"/>	
			    
			    <!--DEPART DATE FIELD-->
			    <label for="depart-date" class="sr-only">
				Date of Departure
			    </label>
			    <input type="text" class="form-control text-field" 
			     id="datepickerD" name="depart_date" placeholder="Depart"/ required>

			    <!--RETURN DATE FIELD-->
			    <label for="return-date" class="sr-only">
				Date of Return
			    </label>
			    <input type="text" class="form-control text-field" 
			     id="datepickerR" name="return_date" placeholder="Return"/>
			</div>
		    </div>
		    
		    <!--
			the div below contains
			the fields for passenger
			counts. the input buttons
			increment the values
			within the input text-boxes
		    -->
		    <div class="row">
			<div class="form-group form-inline">
			    <!--ADULTS FIELD-->
			    <label class="pass" for="adult">
				Adults
			    </label>
			    <input type='button' value='-' 
			     class='btn btn-info qtyminus' field='adults' />
			    <input type='text' name='adults' value='0' 
			     class='qty' id='adult' />
			    <input type='button' value='+' 
			     class='btn btn-info qtyplus' field='adults' />

			    <!--CHILDREN FIELD-->
			    <label class="pass" for="child">
				Children
			    </label>
			    <input type='button' value='-' 
			     class='btn btn-info qtyminus' field='children' />
			    <input type='text' name='children' value='0' 
			     class='qty' id='child'  />
			    <input type='button' value='+' 
			     class='btn btn-info qtyplus' field='children' />

			    <!--SENIOR FIELD-->
			    <label class="pass" for="senior">
				Seniors
			    </label>
			    <input type='button' value='-' 
			     class='btn btn-info qtyminus' field='seniors' />
			    <input type='text' name='seniors' value='0' 
			     class='qty'id='senior'/>
			    <input type='button' value='+' 
			     class='btn btn-info qtyplus' field='seniors' />

			    <!--SEATINFANT FIELD-->
			    <label class="pass" for="seatinfant">
				Seat Infant
			    </label>   
			    <input type='button' value='-' 
			     class='btn btn-info qtyminus' field='seat_infants' />
			    <input type='text' name='seat_infants' value='0' 
			     class='qty' id='seatinfant' />
			    <input type='button' value='+' 
			     class='btn btn-info qtyplus' field='seat_infants' />

			    <!--LAPINFANT FIELD-->
			    <label class="pass" for="lapinfant">
				Lap Infant
			    </label>
			    <input type='button' value='-' 
			     class='btn btn-info qtyminus' field='lap_infants' />
			    <input type='text' name='lap_infants' value='0' 
			     class='qty' id='lapinfant' />
			    <input type='button' value='+' 
			     class='btn btn-info qtyplus' field='lap_infants' />
			</div>
		    </div>

		    <!--
			the div below contains
			the field for airlines
			and maximum price range
		    -->
		    <div class="row">
			<div class="form-group form-inline"> 		
			    <!--AIRLINE FIELD-->
			    <label for="airline">Preferred Airline</label>
			    <select class="form-control text-field" id="airline" name="airline[]" form="search_form" multiple="multiple">
				<option value="none" selected>
				    No Preference
				</option>
				<?php
				    /*
				     * this code scans the airlines.txt
				     * file to read a list of the airlines
				     * and their valid IATA airline codes
				     * that are acceptable by our website
				     */
				    if (file_exists("airlines.txt")){
					$codes = fopen("airlines.txt",'r');				
					while ($line = fgets($codes)){
					    $line_code = explode("(", $line);
					    $code = substr($line_code[1], 0, 2);
					    echo "<option value=\"{$code}\">
						      {$line_code[0]}
						  </option>";
					} // end while
				    } // end if
				?>
			    </select>

			    <!--PRICE FIELD-->	
			    <label for="price">Max Price: 
				<input type="text" class="form-control text-field" 
				 name="price" id="price"></input>
			    </label>
			    <section id="slider"></section>
			    <script>
				/*
				 * this code initializes the slider's
				 * minimum and maximum values
				 * and specifies its starting position
				 */
				$("#slider").noUiSlider({
				    start: 5000, connect: 'lower', step: 10,
				    range: {'min': 0,'75%': 1000,'max': 5000}
				});
				$("#slider").Link('lower').to($('#price'));
			    </script>
			</div>
		    </div>

		    <!--
			the div below contains 
			the submit button
		    -->
		    <div class='row'>
			<input id="submit-button" class="btn btn-info btn-lg" 
			 type="submit" onclick="return validate();" 
			 value="Find Your Flight!"/>
		    </div>
		</form>
	    </header>
	</div>

	<!--
	    the form below is 
	    a hidden form that is
	    submitted to the logout
	    page for managing
	    sessions
	-->
	<form id="hiddenForm" method="post" action="logout.php">
	    <input type="hidden" name="webpage" value="index.php" />
	</form>
    </body>
</html>

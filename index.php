<?php
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
	<title>UCD Flight Tracker | Search</title>

	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="stylesheet" href="bootstrap.css"/>
	<script src="jquery-2.1.3.js"/></script>
	<script src="bootstrap.js"></script>

	<!--this is for the datepicker()-->
	<link rel="stylesheet" href="jquery-ui.css"/>
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/eggplant/jquery-ui.css">
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
	<nav class="navbar navbar-inverse" style="visibility: hidden;"></nav>
	<nav class="navbar navbar-inverse navbar-fixed-top">
	    <div class="container-fluid">
		<div class="navbar-header">
		    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#mynavbar">
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
			    if ($seshFlag)
				echo "<li><a href=\"results.php\">My Search</a></li>";
			     else 
				echo "<li><a href=\"signin.php\">My Search</a></li>";
			?>
			<li><a href="about.php">About</a></li>
		    </ul>
		    <ul class="nav navbar-nav navbar-right">
			<li><a href="contact.php">Contact</a></li>
			<?php
			    if ($seshFlag)
				echo "<li><a href=\"signin.php\">Log Out</a></li>";
			    else
				echo "<li><a href=\"signin.php\">Log In</a></li>";
			?>
		    </ul>
		</div>
	    </div>
	</nav>

	<!-- HERE WE WILL HAVE OUR SEARCH BAR -->
	<div class="container-fluid" id="header">
	    <header class="jumbotron" id="home">
		<h1>UCD Flight Tracker</h1>
		<h3>Customize your travel needs!</h3>

		<form id="search_form" class="form-vertical" method="post" action="search.php" >
		
		    <!--ONE-WAY CHECKBOX-->
		    <div class="form-group">
			<label class="no-indent" for="oneway">
			<input type="checkbox" value="1" 
			    onclick="OneWay()" id="oneway" name="one_way" 
			    form="search_form"/>
			<input class="form-control" type="hidden" value="0" 
			    id="onewayHidden"  name="one_way"
			    form="search_form" checked/>
			One Way 
			</label>
		    </div>

		    <div class="row">
			<div class="form-group form-inline">
			    <!--SOURCE FIELD-->
			    <label for="source" class="sr-only" required >Departure Location</label>
			    <input class="textbox"  id="source" name="source" placeholder=" ---Select an Origin---"/>	
				
			    <!--DESTINATION FIELD-->
			    <label for="destination" class="sr-only">Arrival Location</label>
			    <input class="textbox"  id="destination" name="destination" placeholder=" ---Select a Destination---"/>	
			    
			    <!--DEPART DATE FIELD-->
			    <label for="depart-date" class="sr-only">Date of Departure</label>
				<input type="text" class="form-control" id="datepickerD" 
				name="depart_date" placeholder="Depart"/ required>

			    <!--RETURN DATE FIELD-->
			    <label for="return-date" class="sr-only">Date of Return</label>
				<input type="text" class="form-control" id="datepickerR" 
				name="return_date" placeholder="Return"/>
				</div>

		    <div class="form-group form-inline">
			<!--PASSENGERS FIELD-->
			<label for="adult">
				&nbsp;Adults
				<input type='button' value='-' class='btn btn-info qtyminus' field='adults' />
				<input type='text' name='adults' value='0' class='qty' id='adult' />
				<input type='button' value='+' class='btn btn-info qtyplus' field='adults' />
			</label>
			<label for="child">
				&nbsp;Children
				<input type='button' value='-' class='btn btn-info qtyminus' field='children' />
				<input type='text' name='children' value='0' class='qty' id='child'  />
				<input type='button' value='+' class='btn btn-info qtyplus' field='children' />
			</label>
			<label for="senior">
				&nbsp;Seniors
				<input type='button' value='-' class='btn btn-info qtyminus' field='seniors' />
				<input type='text' name='seniors' value='0' class='qty'id='senior'/>
				<input type='button' value='+' class='btn btn-info qtyplus' field='seniors' />
			</label>
			<label for="seatinfant">
				&nbsp;Seat Infant
				<input type='button' value='-' class='btn btn-info qtyminus' field='seat_infants' />
				<input type='text' name='seat_infants' value='0' class='qty' id='seatinfant' />
				<input type='button' value='+' class='btn btn-info qtyplus' field='seat_infants' />
			</label>   
			<label for="lapinfant">
				&nbsp;Lap Infant
				<input type='button' value='-' class='btn btn-info qtyminus' field='lap_infants' />
				<input type='text' name='lap_infants' value='0' class='qty' id='lapinfant' />
				<input type='button' value='+' class='btn btn-info qtyplus' field='lap_infants' />
			</label>
			
			<!--AIRLINE FIELD-->
			<label for="airline">Preferred Airline</label>
			<select class="form-control" id="airline" name="airline[]"
				    form="search_form" multiple="multiple">
			<option value="none" id="none" selected>No Preference</option>
			<?php
			    if (file_exists("airlines.txt")){
				$codes = fopen("airlines.txt",'r');				
				while ($line = fgets($codes)){
				    $line_code = explode("(", $line);
				    $code = substr($line_code[1], 0, 2);
				    echo "<option value=\"{$code}\" id=\"". $code . "\"\>{$line_code[0]}</option>";
				}
			    } 
			?>

			</select>
		    </div>

		    <!--PRICE FIELD-->	
		    <div class="form-group form-inline" id= "priceSlider"> 		
		    <label for="price">Max Price: </label>
			    <input class="textboxPrice" name="price" id="priceInput"></input>
			    <section id="slider"></section>
			    <script>
				    $("#slider").noUiSlider({
					start: 5000, connect: 'lower', step: 10,
					range: {'min': 0,'75%': 1000,'max': 5000}
				    });
				$("#slider").Link('lower').to($('#priceInput'));
			    </script>
		    </div>
			
		    <input id="submit-button" class="btn btn-info btn-lg" type="submit" onclick=" return validate();" value="Find your flight!"/>
		</form>
	    </header>
	</div>
    </body>
</html>

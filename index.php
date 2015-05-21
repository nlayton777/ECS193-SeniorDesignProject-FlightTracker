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
	<title>SoFly | Search</title>

	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="stylesheet" href="bootstrap.css"/>
	<script src="jquery-2.1.3.js"/></script>
	<script src="bootstrap.js"></script>

	<!--this is for the datepicker()-->
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
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
	<link rel="stylesheet" href="styles2.css"/>	 	
	<script>
	    function changeFunction()
	    {
		document.getElementById("airline").options.namedItem("noPref").selected = false;
		return false;
	    }
	</script>
    </head>

    <body>
	<nav class="navbar navbar-inverse">
	    <div class="container-fluid">
		<div class="navbar-header">
		    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#mynavbar">
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
			    if ($seshFlag)
				echo "<li><a href=\"javascript:;\" onclick=\"submitForm();\">Log Out</a></li>";
			    else
				echo "<li><a href=\"signin.php\">Log In</a></li>";
			?>
		    </ul>
		</div>
	    </div>
	</nav>

	<!-- HERE WE WILL HAVE OUR SEARCH BAR -->
	<div class="container-fluid" id="whole">
	<div id="myCarousel" class="carousel slide" data-ride="carousel">
	    <!-- Wrapper for slides -->
	    <div class="carousel-inner" role="listbox">
		<div class="item active">
		    <img class="img-responsive" src="Pictures/GoldenGate2.jpg" alt="Golden Gate">
		</div>
		<div class="item">
		    <img class="img-responsive" src="Pictures/Arches.jpg" alt="Arches">
		</div>
		<div class="item">
		    <img class="img-responsive" src="Pictures/Street.jpg" alt="Street">
		</div>
		<div class="item">
		    <img class="img-responsive" src="Pictures/Yosemite.jpg" alt="Yosemite">
		</div>
		<div class="item">
		    <img class="img-responsive" src="Pictures/Beach.jpg" alt="Beach">
		</div>
	    </div>
	</div>
	 
	<div class="container-fluid" id="home">
	    <div class="jumbotron" id="home">
		<h1>SoFly. </h1>
		<p>Customize your travel needs!</p>
	    </div>

	    <br>

	    <div class="panel panel-default">
		<div class="panel-body" id="home">
		    <form id="search_form" class="form-vertical" method="post" action="search.php" >
			<div class="form-group">
			    <!--One Way Checkbox-->
			    <label for="oneway">One Way:
				<input type="checkbox" value="1" 
				 onclick="OneWay()" id="oneway" name="one_way" 
				 form="search_form"/>
				<input class="form-control" type="hidden" value="0" 
				 id="onewayHidden" name="one_way"
				 form="search_form" checked/>
			    </label>
			</div>	
			
			<div class="form-group">
			    <!--Source Field-->
			    <label for="source">
				<input type="text" class="form-control" id="source" 
				 name = "source" size="30" placeholder="Origin" required/>
			    </label>

			    <!--Destination Field-->
			    <label for="destination">
				<input type="text" class="form-control" id="destination" name="destination" 
				 size="30" placeholder="Destination" required/>
			    </label>
			</div>

			<div class="form-group">
			    <!--DEPART DATE FIELD-->
			    <label for="depart-date">
				<input type="text" class="form-control" id="datepickerD" 
				 name="depart_date" size="30" placeholder="Departure Date" required/>
			    </label>

			    <!--RETURN DATE FIELD-->
			    <label for="return-date">
				<input type="text" class="form-control" id="datepickerR" 
				 name="return_date" size="30" placeholder="Return Date"/>
			    </label>
			</div>
					
			<div class="form-group">
			    <!--PASSENGERS FIELD-->
			    <label for="adult">Adults:
				<input type='button' value='-' class='btn btn-info qtyminus' field='adults' />
				<input type='text' name='adults' value='0' class='qty' id='adult' />
				<input type='button' value='+' class='btn btn-info qtyplus' field='adults' />
			    </label>
			    &nbsp;

			    <label for="child">Children:
				<input type='button' value='-' class='btn btn-info qtyminus' field='children' />
				<input type='text' name='children' value='0' class='qty' id='child'  />
				<input type='button' value='+' class='btn btn-info qtyplus' field='children' />
			    </label>
			    &nbsp;

			    <label for="senior">Seniors:
				<input type='button' value='-' class='btn btn-info qtyminus' field='seniors' />
				<input type='text' name='seniors' value='0' class='qty'id='senior'/>
				<input type='button' value='+' class='btn btn-info qtyplus' field='seniors' />
			    </label>
			</div>

			<div class="form-group">
			    <label for="seatinfant">Seat Infant:
				    <input type='button' value='-' class='btn btn-info qtyminus' field='seat_infants' />
				    <input type='text' name='seat_infants' value='0' class='qty' id='seatinfant' />
				    <input type='button' value='+' class='btn btn-info qtyplus' field='seat_infants' />
			    </label>   
			    &nbsp;

			    <label for="lapinfant">Lap Infant:
				    <input type='button' value='-' class='btn btn-info qtyminus' field='lap_infants' />
				    <input type='text' name='lap_infants' value='0' class='qty' id='lapinfant' />
				    <input type='button' value='+' class='btn btn-info qtyplus' field='lap_infants' />
			    </label>
			</div>
					
			<div class="form-group"> 		
			    <!--AIRLINE FIELD-->
			    <label for="airline">Preferred Airline(s):</label>
			    <select class="form-control" id="airline" name="airline[]"
			     form="search_form" multiple="multiple">
				<option value="none" selected>No Preference</option>
				<?php
				    if (file_exists("airlines.txt")){
					$codes = fopen("airlines.txt",'r');				
					while ($line = fgets($codes)){
					    $line_code = explode("(", $line);
					    $code = substr($line_code[1], 0, 2);
					    echo "<option value=\"{$code}\">{$line_code[0]}</option>";
					}
				    } 
				?>
			    </select>
			</div>

			<div class="form-group">
			    <!--PRICE FIELD-->	
			    <label for="price" id="priceLabel">Maximum Price:</label>
			    <input type="text" class="form-control" name="price" id="price" size="25"></input>
			</div>

			<div class="form-group">
			    <section id="slider"></section>	
			    <script>
				$("#slider").noUiSlider({
				    start: 5000, connect: 'lower', step: 10,
				    range: {'min': 0,'75%': 5000,'max': 10000}
				});
				$("#slider").Link('lower').to($('#price'));
			    </script>
			</div>

			<br>
			<div id="btn-container">
			    <input id="submit-button" class="btn btn-info btn-lg" 
			     type="submit" onclick=" return validate();" value="Find Your Flight!"/>
			</div>
			</form>
		    </div>
		</div>
	    </div>
	</div>
	
	<form id="hiddenForm" method="post" action="logout.php">
	    <input type="hidden" name="webpage" value="index.php" />
	</form>
    </body>
</html>

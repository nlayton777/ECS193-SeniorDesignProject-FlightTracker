<!DOCTYPE html>
<html>
    <head>
	<title>UCD Flight Tracker</title>
	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="stylesheet" href="bootstrap.css"/>
	<script src="jquery-2.1.3.js"/></script>
	<script src="bootstrap.js"></script>

	<script>

		
		function OneWay(oneway)
		{
			var oneway = document.getElementById('oneway');
			if(oneway.checked)
			{
				document.getElementById('return-date').disabled = true;	
			}
		}
	</script>

    </head>

    <body>
	<nav class="navbar navbar-inverse ">
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
			<li class="active">
			    <a href="index.php">Search</a>
			</li>
			<li>
			    <a href="about.php">About</a>
			</li>
		    </ul>
		    <ul class="nav navbar-nav navbar-right">
			<li>
			    <a href="contact.php">Contact</a>
			</li>
		    </ul>
		</div>
	    </div>
	</nav>

	<!-- HERE WE WILL HAVE OUR SEARCH BAR -->
	<div class="container">
	    <header class="jumbotron" id="home">
		<h1>UCD Flight Tracker</h1>
		<p>Customize your travel needs!<p>

		<div class= "radio-inline">
      			<input type="radio" name="radionbutton" onclick=OneWay() id="oneway">One Way</>
		</div>
		
		<form id="search_form" class="form-vertical" method="post" action="search.php">
		    <div class="form-group form-inline">
			<!--SOURCE FIELD-->
			<label for="source" class="sr-only">Departure Location</label>
			<select class="form-control" id="source" name="source" 
				    form="search_form">
			<?php
			    if (file_exists("AirportCodes.txt"))
			    {
				$codes = fopen("AirportCodes.txt",'r');				
				while (!feof($codes))
				{
	 			    $line = fgets($codes);
				    $sub = substr($line, -5, 3);
				    echo "<option value=\"".$sub."\"> 
					$line</option>";
				}
			    }
			?>
			</select>
			    
			<!--DESTINATION FIELD-->
			<label for="destination" class="sr-only">Arrival Location</label>
			<select class="form-control" id="destination" name="destination" 
				    form="search_form">
			<?php
			    if (file_exists("AirportCodes.txt"))
			    {
				$codes = fopen("AirportCodes.txt",'r');				
				while (!feof($codes))
				{
	 			    $line = fgets($codes);
				    $sub = substr($line, -5, 3);
				    echo "<option value=\"".$sub."\"> 
					$line</option>";
				}
			    }
			?>
			</select>

			<!--need to find way to have calendar pop-up-->
			<label for="depart-date" class="sr-only">Date of Departure</label>
			<input type="text" class="form-control" id="depart-date" 
			    name="depart_date" placeholder="Depart"/>
			    
			<!--need to find way to have calendar pop-up-->
			<label for="return-date" class="sr-only">Date of Return</label>
			<input type="text" class="form-control" id="return-date" 
			    name="return_date" placeholder="Return"/>
		    </div>

		    <div class="form-group form-inline">
			<!--PASSENGERS FIELD-->
			<label for="passengers" class="sr-only">Number of Passengers</label>
			<select class="form-control" id="passengers" name="passengers"
				    form="search_form">
			<?php
			    $pass = array("Adult", "Senior", "Youth", 
				"Child", "Seat Infant", "Lap Infant");
			    foreach ($pass as $pass_type)
				for ($j = 1; $j <= 7; $j++)
				    echo "<option value=\"$j $pass_type\">" .
					"$j $pass_type Economy </option>";
			?>
			</select>

			<!--PRICE FIELD-->
			<label for="price" class="sr-only">Price</label>
			<input type="text" class="form-control" id="price" 
			    name="price" placeholder="Name Your Price!"/>

			<!--AIRLINE FIELD-->
			<label for="airline" class="sr-only">Preferred Airline</label>
			<select class="form-control" id="airline" name="airline"
				    form="search_form">
			<?php
			    if (file_exists("Airlines.txt"))
			    {
				$codes = fopen("Airlines.txt",'r');				
				while (!feof($codes))
				{
	 			    $line = fgets($codes);
				    echo "<option value=\"" . $line .
					"\">" . $line . "</option>";
				}
			    }
			?>
			</select>
			    
			<!--SEARCH WINDOW FIELD-->
			<label for="window" class="sr-only">Search Window</label>
			<input type="text" class="form-control" id="window" 
			    name="window" placeholder="Search Window (in hours)"/>

		    </div>
		    <div class="form-group form-inline">
			<!--EMAIL FIELD-->
			<label for="email" class="sr-only">Email</label>
			<input type="text" class="form-control" id="email" 
			    name="email" placeholder="john.smith@website.com"/>
			    
			<!--PHONE NUMBER FIELD-->
			<label for="phone" class="sr-only">Phone Number</label>
			<input type="text" class="form-control" id="phone" 
			    name="phone" placeholder="(123) 456 - 7890"/>
		    </div>
		    <input type="submit" class="btn btn-default" value="Find your flight!"/>
		</form>
	    </header>
	</div>
    </body>
</html>

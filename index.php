<!DOCTYPE html>
<html>
    <head>
	<title>UCD Flight Tracker</title>
	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="stylesheet" href="bootstrap.css"/>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css"/>
	<script src="jquery-2.1.3.js"/></script>
	<script src="bootstrap.js"></script>
  	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
  	<script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
  	<link rel="stylesheet" href="/resources/demos/style.css">
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
	<div class="container-fluid">
	    <header class="jumbotron" id="home">
		<h1>UCD Flight Tracker</h1>
		<p>Customize your travel needs!<p>

		<div class= "checkbox-inline">
      			<input type="checkbox" value="" onclick=OneWay() id="oneway">One Way</>
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
			<script>
  				$(function() {
    				$( "#datepickerD" ).datepicker();
  				});
				    
  				$(function() {
    				$( "#datepickerR" ).datepicker();
  				});
	
		function OneWay(oneway)
		{
			var oneway = document.getElementById('oneway');
			if(oneway.checked)
			{
					$("#datepickerR" ).datepicker('disable');	
			} 
			else
			{
			    $("#datepickerR" ).datepicker('enable');
			}
		}
	</script>
  			
			<label for="depart-date" class="sr-only">Date of Departure</label>
			    <input type="text" class="form-control" id="datepickerD" 
			    name="depart_date" placeholder="Depart"/>
			    
			<label for="return-date" class="sr-only">Date of Return</label>
			    <input type="text" class="form-control" id="datepickerR" 
			    name="return_date" placeholder="Return"/>
		    </div>

		    <div class="form-group form-inline">
			<!--PASSENGERS FIELD-->
			<label for="passengers" class="sr-only">Number of Passengers</label>
			<select class="form-control" id="passengers" name="passengers"
				    form="search_form">
			<?php
			    $pass = array("Adult", "Kid");
			    foreach ($pass as $pass_type)
				for ($j = 1; $j <= 10; $j++)
				    echo "<option value=\"$j $pass_type\">$j ".
					($j > 1 ? ($pass_type . "s") : $pass_type).
					" Economy </option>";
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
			    name="window" placeholder="Search Window (hours)"/>

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
	<div class="container-fluid">
	    <section id="description">
		<div class="row">
		    <div class="col-md-6">
			<h2>Let us do the work for you</h2>
			<p>
			    We perform background searches for your flight, 
			    so that you don't have to worry about refreshing your 
			    search pages.
			    Just tell us how long you want to search, then kick back,
			    relax, and wait for us to notify you when 
			    we've found your flight. 
			    If your not the waiting type, then  you don't have to.
			    Just leave out a waiting time, and 
			    we will provide immediate results.
			</p>
		    </div>
		    <div class="col-md-6">
			<h2>Stay updated</h2>
			<p>
			    We notify you when prices are to your liking. 
			    Stay informed through email or text, the choice is yours.
			    Once you've obtained your tickets, don't forget to share
			    your travel excitement with your friends on Facebook or
			    Twitter.
			</p>
		    </div>
		</div>
	    </section>
	</div>
    </body>
</html>

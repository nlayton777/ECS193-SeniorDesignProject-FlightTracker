<!DOCTYPE html>
<html>
    <head>
	<title>UCD Flight Tracker</title>
	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="stylesheet" href="bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Bad+Script" />
	<script src="jquery-2.1.3.js"/></script>
	<script src="bootstrap.js"></script>
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
			<li><a href="index.php">Search</a></li>
			<li class="active"><a href="about.php">About</a></li>
		    </ul>
		    <ul class="nav navbar-nav navbar-right">
			<li><a href="contact.php">Contact</a></li>
		    </ul>
		</div>
	    </div>
	</nav>

	<div class="container">
	    <header class="jumbotron" id="about">
		<h1>Find your destination</h1>
		<h3>Let us help you find the flights that you need.</h3>
	    </header>
	</div>

	<div class="container">
		<div class="row">
		    <div class="col-md-4">
			<h2>Customize your travel needs!</h2>
			<p>
			    Our search engine allows you to specify the airline with whom you want to travel. 
			    No more getting stuck with the companies that you don't prefer.
			</p>
		    </div>

		    <div class="col-md-4">
			<h2>Kick back, relax, and let us find the deals for you!</h2>
			<p>
			    Don't miss out on price reductions. 
			    Provide us with a search window, and we search for your flight
			    throughout that time. Feel free to leave your computer,
			    and we will notify you once your price has been found. 
			</p>
		    </div>

		    <div class="col-md-4">
			<h2>Share your travel plans with friends!</h2>
			<p>
			    Show all your friends how excited you are for your trip. 
			    No more having to take pictures of your flight itinerary.
			    Just click the share link, and the work is done for you.
			</p>
		    </div>
		</div>
	</div>
    </body>
</html>

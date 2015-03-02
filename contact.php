<!DOCTYPE html>
<html>
    <head>
	<title>UCD Flight Tracker</title>
	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="stylesheet" href="bootstrap.css"/>
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
			<li><a href="about.php">About</a></li>
		    </ul>
		    <ul class="nav navbar-nav navbar-right">
			<li class="active"><a href="contact.php">Contact</a></li>
		    </ul>
		</div>
	    </div>
	</nav>

	<div class="container">
	<header class="jumbotron" id="contact">
	    <h1>Leave us a message...</h1>
	    <p>We are interested to hear both your questions and/or your feedback regarding our website and services.</p>
	    <div class="row">
		<div class="col-md-4">
		    <form role="form form-inline"  class="form-vertical" method="post" action="contactsubmission.php">
			<div class="form-group">
			    <label for="name">
				Name: 
				<input type="text" class="form-control" id="name" 
				    name = "name" size="10" placeholder="John Smith"/>
			    </label>

			    <label for="email">
				Email:
				<input type="email" class="form-control" id="email" name="email" 
				    pattern="*@-.-" placeholder="john.smith@website.com" required/>
			    </label>
			</div>
			
			<div class="form-group form-horizontal">
			    <label for="comments">
				Message: 
			    	<textarea rows="8" cols="60" id="comments" placeholder="Let us know what you think!" ></textarea>
			    </label>
			</div>
			<input type="submit" class="btn btn-default" value="Submit"/>
		    </form>
		</div>
	    </div>
	</header>
    </body>
</html>

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
			<li>
			    <a href="index.php">Search</a>
			</li>
			<li>
			    <a href="about.php">About</a>
			</li>
		    </ul>
		    <ul class="nav navbar-nav navbar-right">
			<li class="active">
			    <a href="contact.php">Contact</a>
			</li>
		    </ul>
		</div>
	    </div>
	</nav>

	<div class="container">
	    <h1>Contact us...</h1>
	    <p>We are interested to hear both your questions and/or your feedback regarding our website and services.</p>
	    <div class="row">
		<div class="col-md-8">
		    <form role="form form-horizontal">
			<div class="form-group">
			    <label for="name">
				Name: 
			    </label>
			    <input type="text" class="form-control" id="name" 
				size="10" placeholder="John Smith"/>
			</div>

			<div class="form-group">
			    <label for="email">
				Email: 
			    </label>
			    <input type="text" class="form-control" id="email" 
				size="10" placeholder="johnsmith@sitename.com"/>
			</div>

			<div class="form-group">
			    <label for="comments">
				Questions/Comments/Concerns: 
			    </label>
			    <input type="text-area" class="form-control" id="comments" placeholder="Let us know what you think!"/>
			</div>
		    </form>
		</div>

		<div class="col-md-2">
		    <h2>Email</h2>
		    <p>people@something.com</p>
		    <h2>Telephone</h2>
		    <p>+1.916.899.4624</p>
		</div>
	    </div>
	</div>
    </body>
</html>

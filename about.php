<?php
/*
 * this file provides simple and brief 
 * description of our website
 */

/*
 * start session,
 * check if user is 
 * already logged in by
 * checking session variables,
 * set session flag if logged in
 */
session_start();
$seshFlag = false;
if (isset($_SESSION['id']) && isset($_SESSION['email']))
    $seshFlag = true;
?>
<!DOCTYPE html>
<html>
    <head>
	<title>SoFly | About</title>
	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="stylesheet" href="bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Bad+Script" />
	<script src="jquery-2.1.3.js"/></script>
	<script src="bootstrap.js"></script>
	<script src="flight_tracker.js"></script>
	<link rel="stylesheet" type="text/css" href="styles.css"/>
    </head>

    <body>
	<!--
	    element below is navbar
	    at top of screen; has a dropdown
	    functionality when screen width 
	    is small
	-->
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
			<li><a href="index.php">Find a Flight</a></li>

			<?php
			    /*
			     * if logged in, then send user straight
			     * to results page, otherwise, force
			     * them to log in
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

		    </ul>
		    <ul class="nav navbar-nav navbar-right">
			<li class="active"><a href="about.php">About</a></li>
			<li><a href="contact.php">Contact</a></li>
			<?php
			    /*
			     * if logged in, then print "Log Out"
			     * on button, else print "Log In"
			     */
			    if ($seshFlag)
			    {
				echo "<li>
				          <a href=\"javascript:;\" onclick=\"submitForm()\">
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
	    the div below contains 
	    header information with
	    picture
	-->
	<div class="container">
	    <header class="jumbotron" id="about">
		<div class='row'>
		    <div class='col-lg-12 text-center v-center'>
			<div class='transbox'>
			    <h1>We are SoFly!</h1>
			</div>
		    </div>
		</div>
		<br><br>
		<h3>...and we find the perfect<br>
		    flight arrangements for <br>
		    your travel needs!</h3>
	    </header>
	</div>

	<!--
	    the div below contains
	    two columns of information
	    that describe our search engin
	-->
	<div class="container">
	    <div class="row">
		<!--COLUMN 1-->
		<div class="col-md-6">
		    <h2>Customize your travel needs!</h2>
		    <p>
			Many flight fare search enginers don't provide the user the ability
			to select their desired airlines or price range. Our search engine 
			allows you to specify all the typical flight parameters, in addition
			to your preferred airlines and max price. No more getting stuck with 
			the companies that you don't prefer or viewing the results that cost
			more than your price range. Keep in mind, however, that the more 
			narrow that your search filer is, the less results you might find. .
		    </p>
		</div>

		<!--COLUMN 2-->
		<div class="col-md-6">
		    <h2>Kick back, relax, and let us find the deals for you!</h2>
		    <p>
			Don't miss out on price reductions. 
			Provide us with a search window, and we background search 
			for your flight results while you relax. Feel free to leave 
			your computer, and we notify you via email when price 
			reductions are occuring or when it might be a good time
			to book your flight.
		    </p>
		</div>
	    </div>
	</div>
	
	<!--
	    form below is a hidden form
	    that is submitted to the
	    log out page for managing 
	    sessions
	-->
	<form id="hiddenForm" method="post" action="logout.php">
	    <input type="hidden" name="webpage" value="about.php" />
	</form>
    </body>
</html>

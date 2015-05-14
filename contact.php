<?php
/*
 * start session,
 * set session flag if user 
 * information is stored in 
 * session variables
 */
session_start();
$seshFlag = false;
if (isset($_SESSION['id']) && isset($_SESSION['email']))
    $seshFlag = true;
?>
<!DOCTYPE html>
<html>
    <head>
	<title>SoFly | Contact</title>
	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="stylesheet" href="bootstrap.css"/>
	<script src="jquery-2.1.3.js"/></script>
	<script src="bootstrap.js"></script>
	<script src="flight_tracker.js"></script>
	<link rel="stylesheet" href="styles.css"/>
	<style>
	   html,body {
		 height:100%;
	   }
	</style>
    </head>

    <body>
	<!--
	    nav element below is for 
	    navbar at the top of window;
	    collapses when window width is 
	    small enough
	_-->
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
			     * if user is logged in,
			     * then send them straight to
			     * results page, otherwise, send
			     * them to login page
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
			<li class="active"><a href="contact.php">Contact</a></li>

			<?php
			    if ($seshFlag)
				echo "<li>
				          <a href=\"javascript:;\" onclick=\"submitForm()\">
					      Log Out
					  </a>
				      </li>";
			    else
				echo "<li><a href=\"signin.php\">Log In</a></li>";
			?>

		    </ul>
		</div>
	    </div>
	</nav>

	<!--
	    container below holds a banner with image
	-->
	<div class="container" id="banner-stuff">
	    <div class="row">
		<div class="col-lg-12 text-center v-center">
		    <div class="transbox">
			<h1>Comments?</h1>
		    </div>
		</div> 
	    </div> 

	    <div class="row">
		<div class="col-lg-12 text-center v-center" style="font-size:39pt;">
		    <a href="#"><i class="icon-google-plus"></i></a> <a href="#"><i class="icon-facebook"></i></a>  <a href="#"><i class="icon-twitter"></i></a> <a href="#"><i class="icon-github"></i></a> <a href="#"><i class="icon-pinterest"></i></a>
		</div>
	    </div>

	    <br><br><br><br><br>
	</div> 

	<!--
	    container below contains
	    a form that submits comments 
	    to our email address
	-->
	<div class="container" id="form-stuff">
	    <hr>
	    <div class="row">
		<div class="col-md-3"></div>
		<div class="col-md-6">
		    <div class="panel panel-default">
			<div class="panel-heading">
			    <h3>We'd Love to Hear Them.</h3>
			</div>
			<div class="panel-body">
			    <form role="form form-inline"  class="form-vertical" 
			     method="post" action="contactsubmission.php">
				<div class="form-group">
				    <label for="name">
					Name: 
					<input type="text" class="form-control" 
					 id="name" name = "name" size="20" 
					 placeholder="John Smith" required/>
				    </label>

				    <label for="email">
					Email:
					<input type="email" class="form-control" 
					 id="email" name="email" pattern="*@-.-" 
					 size="30" placeholder="john.smith@website.com" required/>
				    </label>
				</div>

				<label for="comments">
				    Message: 
				    <textarea class="form-control" rows="8" cols="60" id="comments" name="comments" placeholder="Let us know what you think!" required></textarea>
				</label>
			    
				<input type="submit" class="btn btn-default" value="Submit"/>
			    </form>
			</div> <!--end div.panel-body-->
		    </div><!-- end div.panel.panel-default-->
		</div><!-- end div.col-md-6-->
		<div class="col-md-3"></div>
	    </div><!--end div.row-->
	</div><!--end div.container-->

	<!--
	    the form below is a hidden
	    form that submits a post request
	    to the log out page for managing
	    sessions
	-->
	<form id="hiddenForm" method="post" action="logout.php">
	    <input type="hidden" name="webpage" value="contact.php" />
	</form>
    </body>
</html>

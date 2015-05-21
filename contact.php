<?php
session_start();
$seshFlag = false;
if (isset($_SESSION['id']) && isset($_SESSION['email']))
    $seshFlag = true;
?>
<!DOCTYPE html>
<html>
    <head>
	<title>UCD Flight Tracker | Contact</title>
	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="stylesheet" href="bootstrap.css"/>
	<script src="jquery-2.1.3.js"/></script>
	<script src="bootstrap.js"></script>
	<script src="flight_tracker.js"></script>
	<style>
		textarea {
    		resize: none;
		}
		.jumbotron#contact {
    		color: #ffffff;
    		background: #ffffff url('GoldenGate3.jpg') no-repeat center center;
    		-webkit-background-size: cover;
       		-moz-background-size: cover;
         	-o-background-size: cover;
            background-size: cover;
            height: 200px;
		}
	</style>
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
			<li><a href="index.php">Find a Flight</a></li>
			<?php
			    if ($seshFlag)
				echo "<li><a href=\"results.php\">My Search</a></li>";
			    else
				echo "<li><a href=\"signin.php\">My Search</a></li>";
			?>
			<li><a href="about.php">About</a></li>
		    </ul>
		    <ul class="nav navbar-nav navbar-right">
			<li class="active"><a href="contact.php">Contact</a></li>
			<?php
			    if ($seshFlag)
				echo "<li><a href=\"javascript:;\" onclick=\"submitForm()\">Log Out</a></li>";
			    else
				echo "<li><a href=\"signin.php\">Log In</a></li>";
			?>
		    </ul>
		</div>
	    </div>
	</nav>

<div class="container">
		<header class="jumbotron" id="contact">
		<h1>Comments?</h1>
		<h3>We'd Love to Hear Them.</h3>
	    </header>
	</div>
<div class="container">
  	<div class="row">
        <div class="col-md-3">
          
        </div>
      	<div class="col-md-6">
        	<div class="panel panel-default">
		    <div class="panel-body">
		    <form role="form form-inline"  class="form-vertical" method="post" action="contactsubmission.php">
			<div class="form-group">
			   			<label for="name">
						Name: 
						<input type="text" class="form-control" id="name" 
				    	name = "name" size="25" placeholder="John Smith" required/>
			    		</label>

			    		<label for="email">
							Email:
							<input type="email" class="form-control" id="email" name="email" 
				    		pattern="*@-.-" size="30" placeholder="john.smith@website.com" required/>
			    		</label>
					</div>
					<label for="comments">
						Message: 
			    		<textarea class="form-control" rows="4" cols="60" id="comments" name="comments" placeholder="Let us know what you think!" required></textarea>
			    	</label>
			    
			    <input type="submit" class="btn btn-default" value="Submit"/>
				</form>
            </div>
          </div>
        </div>
      	<div class="col-md-3"></div>
    	</div>
    </div>
</div>
	</header>
	<form id="hiddenForm" method="post" action="logout.php">
	    <input type="hidden" name="webpage" value="contact.php" />
	</form>
    </body>
</html>

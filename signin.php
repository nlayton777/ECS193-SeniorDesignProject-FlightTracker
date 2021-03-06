<!--
    this file displays a form for the
    user to enter their info and attempt
    to login. 
    the page displays errors if the user
    info that was submitted was incorrect.
    the page forwards the user to results.php
    when valid info is submitted
-->
<!DOCTYPE html>
<html>
    <head>
	<title>SoFly | Log In</title>

	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
  	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<link rel="stylesheet" href="bootstrap.css"/>
	<script src="jquery-2.1.3.js"/></script>
	<script src="bootstrap.js"></script>

	<!--this is for alerts-->
	<script src="bootbox.js"></script>

	<!--this is our js and css file-->
	<script type="text/javascript" src="flight_tracker.js"></script>
	<link rel="stylesheet" href="styles.css"/>	 

	<?php
	    /*
	     * the GET array would be set if the user
	     * is returning from an email message. the code
	     * below checks if the GET variables are set 
	     * and autofills the fields with their information
	     */
	    $flag = false;
	    if(isset($_GET['id']) && isset($_GET['email']))
	    {
		echo <<<_SCRIPT
		<script>
		    function autoSubmit() {
			document.getElementById("email").value = "{$_GET['email']}";
			document.getElementById("id").value = "{$_GET['id']}";
			doStuff(email)
		    }
		</script>
	    </head>
	<body onload="autoSubmit()">
_SCRIPT;
		$flag = true;
	    } else // if GET array not set
		echo "<body>";
	?>


	<!-- 
	    nav is for navigation 
	    bar at top of page
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
			<li><a href="signin.php">My Search</a></li>
		    </ul>
		    <ul class="nav navbar-nav navbar-right">
			<li><a href="about.php">About</a></li>
			<li><a href="contact.php">Contact</a></li>
			<li class="active"><a href="signin.php">Log In</a></li>
		    </ul>
		</div>
	    </div>
	</nav>

	<div class="container-fluid"> 
	    <div class="row">
		<div class="col-md-4"></div>
		<div class="col-md-4">
		    <h3 class="sign-up-title">Welcome Back!</h3>
		    <?php
			/* 
			 * flag indicates whether or not user 
			 * visited from an email. the contents
			 * of the paragraph depends on whether or
			 * not user came from email
			 */
			if ($flag)
			{
			    echo <<<_STUFF
			    <p>Your search results will be shown momentarily!
			    First, please verify that the Email address and request ID
			    are correctly entered below before submitting the form. 
			    </p>
_STUFF;
			} else
			{
			    echo <<<_STUFF2
			    <p>Provide the Email address and request ID that we sent with your
			       confirmation message for the particular search in which you 
			       are interested in viewing results.
			    </p>
_STUFF2;
			} // else

		    ?>
		    <hr>

		    <!--
			div below contains panel on which 
			the form will be displayed
		    -->
		    <div class="panel panel-default">
			<div class="panel-heading">
			    <h3>
			    <?php 
				if ($flag)
				    echo "We are signing you in...";
				else
				    echo "Log In";
			    ?>
			    </h3>
			</div>
			<div class="panel-body">
			    <!--
				on submission, the form calls function
				to validate the user input, and if user
				information is valid it's passed to 
				results.php
			    -->
			    <form id="claimFlight" class="sign-up" onsubmit="doStuff(email)">
				<div class="form-group">
				    <label for="email">Email:
					<input type="text" class="form-control sign-up-input" 
					 name="email" id="email" placeholder="john.smith@website.com" autofocus>
				    </label>
				</div>

				<div class="form-group">
				    <label for="id">Request ID:
					<input type="text" class="form-control sign-up-input" 
					 name="id"  id="id" placeholder="1234">
				    </label>
				</div>
				<?php
				    if (!$flag)
				    {
					echo <<<_STUFF
				<input type="submit" class="btn btn-info" value="Submit" onclick="doStuff(email)" 
				    onKeyDown="javascript:return submitOnEnter(event)">
_STUFF;
				    } 
				?>
			    </form>
			</div>
		    </div>

		    <!--
			this hidden form contains the info that is
			passed to results.php: request ID and email
		    -->
		    <form id="hiddenForm" method="post" action="results.php">
			<input type="hidden" name="email" id="hidden_email">
			<input type="hidden" name="id"  id="hidden_id">
		    </form>
		    <script>
  				var qstr = window.location.search;
  				if(qstr != ""){
				   var qstr_dec = decodeURIComponent(qstr);
				   qstr_dec = qstr_dec.substring(1);
				   var params = {}, queries, temp, i, l;
 
				   // Split into key/value pairs
				   queries = qstr_dec.split("&");
				
				   // Convert the array of strings into an object
				   for ( i = 0, l = queries.length; i < l; i++ ) {
					   temp = queries[i].split('=');
					   if(temp[0] == "email"){
						   document.getElementById("email").value = temp[1];
					   }
					   if(temp[0] == "id")
						   document.getElementById("id").value = temp[1];
				   }
				
				   doStuff(email);
				}
 
		</script> 
		</div>
		<div class="col-md-4"></div>
	    </div>
	</div>
    </body>
</html>

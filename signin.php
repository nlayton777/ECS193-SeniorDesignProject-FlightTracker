<!DOCTYPE html>
<html>
    <head>
	<title>UCD Flight Tracker</title>

	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="stylesheet" href="bootstrap.css"/>
	<script src="jquery-2.1.3.js"/></script>
	<script src="bootstrap.js"></script>

	<!--this is for the increment button
	<script src="jquery.min.js"></script>-->

	<!--this is for alerts-->
	<script src="bootbox.js"></script>

	<!--this is our js and css file-->
	<script type="text/javascript" src="flight_tracker.js"></script>
	<link rel="stylesheet" href="styles.css"/>	 

	<meta charset="utf-8">
  	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  	<!--<link rel="stylesheet" href="signup.css"/>-->

  	<!--**************** AJAX STUFF ********************* -->
	<script>
	    function doStuff() {
		var xmlhttp;
		var id_val = document.getElementById("id").value;
		var email_val = document.getElementById("email").value;
		if (window.XMLHttpRequest)
		{ xmlhttp  = new XMLHttpRequest(); }
		else
		{ xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); }

		xmlhttp.onreadystatechange = function() {
		    if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
		    {
			if(xmlhttp.responseText == "false")
			{
				alert("bad auth");
			}
			else
			{
				//window.open("results.php","_self");
				var obj = {id : id_val, email : email_val};
				document.getElementById("test").innerHTML = JSON.stringify(obj);
				post(obj);
			}
		    }
		}
		var str = "email=" + email_val + "&id=" + id_val;
		//document.getElementById("test").innerHTML = str;
		xmlhttp.open("GET","authenticate.php?"+str,true);
		xmlhttp.send();
	    } // doStuff

	    function checkEnter(e)
	    {
		if(e.keyCode == 13)
			doStuff();
	    } // checkEnter

	    function post(params) 
	    {
		document.getElementById("hidden_id").value = params["id"];
		document.getElementById("hidden_email").value = params["email"];
		document.getElementById("hiddenForm").submit();
	    } //  post
	</script>
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
			    // if session is set
				//echo "<li class=\"active\"><a href=\"results.php\">Find a Flight</a></li>";
			    // else
				echo "<li class=\"active\"><a href=\"signin.php\">My Search</a></li>";
			?>
			<li><a href="about.php">About</a></li>
		    </ul>
		    <ul class="nav navbar-nav navbar-right">
			<li><a href="contact.php">Contact</a></li>
		    </ul>
		</div>
	    </div>
	</nav>

	<div class="container-fluid"> 
	    <form id="claimFlight" class="sign-up" >
		<h3 class="sign-up-title">Check Flight Results!</h3>
		<input type="text" class="sign-up-input" name="email" id="email" placeholder="Email" autofocus>
		<input type="text" class="sign-up-input" name="id"  id="id" placeholder="Request ID">
		<input type="button" value="Submit" onclick="doStuff()" onkeypress="checkEnter(event)" class="sign-up-button">
	    </form>

	    <form id="hiddenForm" method="post" action="results.php">
		<input type="hidden" name="email" id="hidden_email">
		<input type="hidden" name="id"  id="hidden_id">
	    </form>
	</div>
	
	<div id="test">
	    blah
	</div>
    </body>
</html>

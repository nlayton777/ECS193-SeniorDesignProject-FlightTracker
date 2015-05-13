<?php
if (isset($_SESSION['id']) && isset($_SESSION['email']))
{
    unset($_SESSION['id']);
    unset($_SESSION['email']);
    session_unset();
    $_SESSION = array();
    unset($_SESSION);
    session_destroy();
    if (ini_get("session.use_cookies")) 
    {
	$params = session_get_cookie_params();
	setcookie(session_name(), '', time() - 42000,
		 $params["path"], $params["domain"],
		 $params["secure"], $params["httponly"]);
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
	<title>UCD Flight Tracker | Log In</title>

	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="stylesheet" href="bootstrap.css"/>
	<script src="jquery-2.1.3.js"/></script>
	<script src="bootstrap.js"></script>

	<!--this is for alerts-->
	<script src="bootbox.js"></script>

	<!--this is our js and css file-->
	<script type="text/javascript" src="flight_tracker.js"></script>
	<link rel="stylesheet" href="styles.css"/>	 

	<meta charset="utf-8">
  	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  	<!--**************** AJAX STUFF ********************* -->
	<script>
	    function doStuff(mail) {
	    	//check for email validation
		  	if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(claimFlight.email.value))
		    {
					 var id_val = document.getElementById("id").value;
					 idExist = true;
					 if (id_val == null || id_val == ""){
					 	idExist = false;
					 }
					 var isNum = isNaN(id_val); //returns true if ID input is not a number
					 if(!isNum && idExist) //if false continue
					 {	
						var xmlhttp;
						var email_val = document.getElementById("email").value;
						if (window.XMLHttpRequest)
						{ xmlhttp  = new XMLHttpRequest(); }
						else
						{ xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); }
						xmlhttp.onreadystatechange = function() 
						{
						    if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
						    {
								if(xmlhttp.responseText == "false")
								{
									bootbox.dialog({
  										title: "Sorry!",
  										message: "We don't have a record of that Email and ID"
									});
								}
								else
								{
									//window.open("results.php","_self");
									var obj = {id : id_val, email : email_val};
									//document.getElementById("test").innerHTML = JSON.stringify(obj);
									post(obj);
								}
						    }
						}
						var str = "email=" + email_val + "&id=" + id_val;
						//document.getElementById("test").innerHTML = str;
						xmlhttp.open("GET","authenticate.php?"+str,true);
						xmlhttp.send();
					} //end if with AJAX stuff
					else
					{
						bootbox.dialog({
  							title: "Whoops!",
  							message: "You have input an incorrect ID"
						});
						return(false);
					}
			} // end main if 
			else
			{
				bootbox.dialog({
  					title: "Whoops!",
  					message: "You have invalid email input"
				});
			  	return(false);
			}
	  }//do Stuff
	    
	   function submitonEnter(evt)
		{ 
			var charCode = (evt.which) ? evt.which : event.keyCode 
			if(charCode == "13")
			{ 
				document.getElementById("hiddenForm").submit(); 
			} 
		} 

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
			<li><a href="signin.php">My Search</a></li>
			<li><a href="about.php">About</a></li>
		    </ul>
		    <ul class="nav navbar-nav navbar-right">
			<li><a href="contact.php">Contact</a></li>
			<?php
			    echo "<li class=\"active\"><a href=\"signin.php\">Log In</a></li>";
			?>
		    </ul>
		</div>
	    </div>
	</nav>

	<div class="container-fluid"> 
	    <div class="row">
		<div class="col-md-4"></div>
		<div class="col-md-4">
		    <h3 class="sign-up-title">Welcome Back!</h3>
		    <p>Provide the Email address and request ID that we sent with your
		       confirmation message for the particular search in which you 
		       are interested in viewing results.
		    </p>
		    <div class="panel panel-default">
			<div class="panel-heading"><h3>Log In!</h3></div>
			<div class="panel-body">
			    <form id="claimFlight" class="sign-up" >
				<div class="form-group">
				    <label for="email">Email:
					<input type="text" class="form-control sign-up-input" name="email" id="email" placeholder="john.smith@website.com" autofocus>
				    </label>
				</div>
				<div class="form-group">
				    <label for="id">Request ID:
					<input type="text" class="form-control sign-up-input" name="id"  id="id" placeholder="1234">
				    </label>
				</div>
				<input type="button" value="Submit" onclick="doStuff(email)" onKeyDown="javascript:return submitonEnter(event)" class="sign-up-button">
			    </form>
			</div>
		    </div>

		    <form id="hiddenForm" method="post" action="results.php">
			<input type="hidden" name="email" id="hidden_email">
			<input type="hidden" name="id"  id="hidden_id">
		    </form>
		</div>
		<div class="col-md-4"></div>
	    </div>
	</div>

	<div id="test">

	</div>
	
    </body>
</html>

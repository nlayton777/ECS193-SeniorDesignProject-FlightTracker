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

<div class="container-fluid" id="searchheader">
    <div class="row">
	<div class="col-xs-4 col-md-2"></div>
	<div class="col-xs-10 col-md-8">
	    <h1>Search Results</h1>
	    <h3>Our search bot found these travel options just for you!</h3>	

	    <table class="table table-hover background-color:#CCF5F6">
		<tr>
		    <th>Carrier</th>
		    <th>Price</th>
		    <th colspan='4'>Flight Info</th>
		</tr>

		<tr>
		    <td>Southwest</td>
		    <td>$500.00</td>
		    <td> 10:00AM <strong>SFO</strong> &rarr; 1:00PM <strong>JFK</strong>    6h0m    (1 stop PHX)</td>
		</tr>

		<tr>
		    <td>US Airways</td>
		    <td>$550.00</td>
		    <td> 10:30AM <strong>SFO</strong> &rarr; 1:45PM <strong>JFK</strong>    6h15m    (1 stop SEA)</td>
		</tr>	
	</div>


	    </table>
	<div class="col-xs-4 col-md-2"></div>
    </div>

</div>

</body>

</html>

<?php
//session_start();
$_SESSION['id'] = 296;
$_SESSION['email'] = "nllayton@ucdavis.edu";

$post = $_POST;
$sesh = $_SESSION;

$id = $post['id'];
$email = $post['email'];
$sesh['id'] = $post['id'];
$sesh['email'] = $post['email'];

$session_flag = true;
?>
<!DOCTYPE html>
<html>
    <head>
	<title>UCD Flight Tracker | Search Results</title>

	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="stylesheet" href="bootstrap.css"/>
	<script src="jquery-2.1.3.js"/></script>
	<script src="bootstrap.js"></script>
	<link rel="stylesheet" href="flipclock.css"/>
	<script src="flipclock.min.js"></script>
	<link rel="stylesheet" href="styles.css"/>
	<script src="flight_tracker.js"></script>
    </head>

    <body>
	<nav class="navbar navbar-inverse" style="visibility: hidden;"></nav>
	<nav class="navbar navbar-inverse navbar-fixed-top">
	    <div id="main" class="container-fluid">
		<div class="navbar-header">
		    <button type="button" class="navbar-toggle" 
			data-toggle="collapse" data-target="#mynavbar">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		    </button>
		    <a class="navbar-brand" href="index.php">Flight Tracker</a>
		</div>

		<div class="collapse navbar-collapse" id="mynavbar">
		    <ul class="nav navbar-nav">
			<li><a href="index.php">Find a Flight</a></li>
			<li class="active"><a href="results.php">My Search</a></li>
			<li><a href="about.php">About</a></li>
		    </ul>
		    <ul class="nav navbar-nav navbar-right">
			<li><a href="contact.php">Contact</a></li>
		    </ul>
		</div>
	    </div>
	</nav>

	<div class="container-fluid" id="searchheader">
	    <div class="row">
		<div class="col-xs-4 col-md-1"></div><!--end col-->
		<div class="col-xs-10 col-md-10">
		    <div class="row">
			<div class="col-md-6" id="search-title">
			<?php echo "<h1>Search Results For Request #{$id}<h1>"; ?>
			</div><!--end col-->

			<div class="col-md-6" id="background-info">
			    <img id="exclamation" src="exclamation.png" alt="Important" height="8%" width="8%" />

			    <?php
				require_once './flight_tracker.php';

				$remaining = getRemainingTime($id,$email);
				echo "<script>var remaining = {$remaining};</script>";
				if ($remaining > 0){
				    echo <<<_DES
					<p id="background-description">
						You can choose to either continue your search if 
						you would like for us to keep searching or 
						terminate the search by selecting one of the options 
						below.
					</p>
_DES;
				} else 
				{
				    echo <<<_DES2
					<p id="background-description">
					    Your search is complete! You can either choose one of the options
					    below, or start a new search from our <a href="index.php">
					    Search Page</a>.
					</p>
_DES2;
				}
			    ?>

			</div><!--end col-->
		    </div><!--end row-->

		    <h2>Search Time Remaining</h2>
		    <div class="clock"></div>
		    <?php
			if ($remaining > 0)
			{
			    echo <<<_STUFF
				<script>CountdownClock({$remaining})</script>
				<h2>Search Results So Far</h2>
_STUFF;
			} else {
			    echo <<<_STUFF2
				<script>CountdownClock(0)</script>
				<h2>Search Results</h2>
_STUFF2;
			} //if/else

		    ?>

		    <table id="results" class="table table-hover">
			<tr>
			    <th id="price">Total Price</th>
			    <th id="it">Itinerary</th>
			    <th id="info">More Info</th>
			</tr>
		    </table>

		    <div id="test">
			blah
		    </div>
		</div><!--end col-->
		<div class="col-xs-4 col-md-1"></div><!--end col-->
	    </div><!--end row-->
	</div><!--end div container-->
    </body>

    <script>
	var id = <?php echo $id; ?>;
	var email = "<?php echo $email; ?>";
	var seconds = 3;

	window.setInterval(function () {
	    if (remaining <= 0)
	    {
		document.getElementById("background-description").innerHTML = "Your search is complete! You can either choose one of the options below, or start a new search from our <a href=\"index.php\">Search Page.</a>";
	    }

	    var xmlhttp;
	    if (window.XMLHttpRequest)
	    { xmlhttp = new XMLHttpRequest(); }
	    else
	    { xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); }
	    xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
		{
		    document.getElementById("test").innerHTML = xmlhttp.responseText;
		}
	    }
	    var str = "id=" + id + "&email=" + email;
	    xmlhttp.open("GET","retrieve.php?" + str,true);
	    xmlhttp.send();
	},seconds * 1000);

	window.onload=function(){$('.dropdown').hide();};

	<?php
	    for ($i = 0; $i < $rowCount; $i++)
	    {
		echo <<<_SECTION1
		$(document).ready(function () {
		    $('#btnExpCol{$i}').click(function () {
			if ($(this).val() == 'Collapse') {
			    $('#row{$i}').stop().slideUp('3000');
			    $(this).val(' Expand ');
			} else {
			    $('#row{$i}').stop().slideDown('3000');
			    $(this).val('Collapse');
			}
		    });
		});
_SECTION1;
	    } // for
	?>

    </script>
</html>

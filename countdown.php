<!DOCTYPE html>
<html>
    <head>
	<title>UCD Flight Tracker</title>

	<meta charset="UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="stylesheet" href="bootstrap.css"/>
	<script src="jquery-2.1.3.js"/></script>
	<script src="bootstrap.js"></script>
	<link rel="stylesheet" href="styles.css"/>
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
			<li class="active"><a href="index.php">Search</a></li>
			<li><a href="about.php">About</a></li>
		    </ul>
		    <ul class="nav navbar-nav navbar-right">
			<li><a href="contact.php">Contact</a></li>
		    </ul>
		</div>
	    </div>
	</nav>

	<?php
	    require_once 'login.php';

	    $post = $_POST;
	    print_r($_POST);
	    echo "<br>";
	    echo "<br>";

	    // connect to database
	    $connection = new mysqli ($db_hostname, $db_username);
	    if($connection->connect_error) die($connection->connect_error);
	    mysqli_select_db($connection,"flight_tracker");

	    // get search ID
	    $query = "SELECT * FROM search_id";
	    $result = $connection->query($query);
	    if (!$result) die($connection->error);
	    $result->data_seek(0);
	    $row = $result->fetch_array(MYSQLI_ASSOC);
	    $search_id = $row['last_id'];

	    // increment search ID
	    $query2 = "UPDATE search_id SET last_id = last_id + 1;";
	    $result2 = $connection->query($query2);
	    if (!$result2) die($connection->error);

	    // add user info to db
	    $d_date = explode("/",$post['depart_date']);
	    $d_date = implode("-",array($d_date[2],$d_date[0],$d_date[1]));
	    $r_date = explode("/",$post['return_date']);
	    $r_date = implode("-",array($r_date[2],$r_date[0],$r_date[1]));
	    $query3 = "INSERT INTO searches ".
		      "VALUES (".
			    $search_id.",'".$post['email']."','".
			    $post['origin']."','".$post['destination']."','".
			    $d_date."','".$r_date."',".
			    $post['adults'].",".$post['children'].",".
			    $post['seniors'].",".$post['seat_infant'].",".
			    $post['lap_infant'].",".$post['price'].
			    ",now(),now()".
		      ");";
	    echo $query3;
	    $result3 = $connection->query($query3);
	    if (!$result3) die($connection->error);

	    $connection->close();
	?>
</html>

<?php
    require_once 'login.php';

    $connection = new mysqli ($db_hostname, $du_username);
    if ($connection->connect_error) die($connection->connect_error);
    $connection->select_db("flight_tracker");

/*
    if(isset($_GET))
	$get = $_GET;
    else
	echo "_GET not set";
	*/
    $get['email'] = 'nllayton@ucdavis.edu';
    $get['id'] = 
    
    $query = <<<_QUERY
	SELECT *
	FROM searches
	WHERE id=$get['id'] AND
	      email = '{$get['email']}';
_QUERY;
?>

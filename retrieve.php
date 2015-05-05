<?php
    require_once 'login.php';

    //$connection = new mysqli ($db_hostname, $db_username);
    $connection = new mysqli ("localhost", "root");
    if ($connection->connect_error) die($connection->connect_error);
    $connection->select_db("flight_tracker");

    $get = $_GET;
    if(isset($_GET) && isset($_GET['email']) && 
       isset($_GET['id']) && isset($_GET['lastQuery']))
    {
	$email = $get['email'];
	$id = $get['id'];
	$lastQuery = $get['lastQuery'];
	$lastQuery = date("Y-m-d H:i:s", $lastQuery);
    } else die("_GET not set");
    $id = 436;
    $email = 'nllayton@ucdavis.edu';
    $lastQuery = 1430759402; 
    $lastQuery = date("Y-m-d H:i:s", $lastQuery);
    
    $query = <<<_QUERY
	SELECT MIN(opt_saletotal), query_time
	FROM `{$id}`
	WHERE query_time = (
	    SELECT MAX(query_time)
	    FROM `{$id}`
	) AND
	      query_time > '{$lastQuery}';
_QUERY;
    $result = $connection->query($query);
    if (!$result) die($connection->connect_error); 
    $result->data_seek(0);
    $newData = $result->fetch_array(MYSQLI_ASSOC);
    $price = $newData['MIN(opt_saletotal)'];
    $date = explode("-", explode(" ", $newData['query_time'])[0]);
    $time = explode(":", explode(" ", $newData['query_time'])[1]);
    $fullTime = mktime($time[0], $time[1], $time[2], $date[1], $date[2], $date[0]);
    $fullTime = date("g:i:s A n/j", $fullTime);
    $rv =  "{$fullTime},{$price}+";
    echo $rv;
?>

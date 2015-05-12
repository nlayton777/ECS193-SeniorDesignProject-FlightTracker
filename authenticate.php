<?php
    $get = $_GET;
    //print_r($get);
    $rv = false;
    if (isset($get['email']) && isset($get['id']))
    {
	    require_once 'login.php';
	    require_once 'flight_tracker.php';
	    
	    $connection = new mysqli ($db_hostname, $db_username);
	    if($connection->connect_error) die($connection->connect_error);
	    mysqli_select_db($connection,"flight_tracker");

	    $query = <<<_QUERY
		SELECT * 
		FROM searches 
		WHERE email = '{$get['email']}' and 
		    ID = {$get['id']};
_QUERY;
	    $result = $connection->query($query);
	    if (!$result) die($connection->connect_error);

	    $result->data_seek(0);
	    //print_r($result);
	    $n = $result->num_rows;
	    $data = $result->fetch_array(MYSQLI_ASSOC);
	    $email = $data['email'];
	    $id = $data['ID'];

	    if ($n == 1 && $email == $get['email'] && $id == $get['id'])
		    $rv = "true";
	    else 
		    $rv = "false";
	    //print_r($data);
    }
    else 
    {
	    $rv = "false;";
    }

    echo $rv;
?>

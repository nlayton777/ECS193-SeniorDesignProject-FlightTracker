<?php
    require_once 'login.php';

    $connection = new mysqli ($db_hostname, $db_username);
    if ($connection->connect_error) die($connection->connect_error);
    $connection->select_db("flight_tracker");

    if(isset($_GET) && isset($_GET['email']) && isset($_GET['id']))
    {
	$get = $_GET;
	$email = $get['email'];
	$id = $get['id'];
    } else{
	echo "_GET not set";
	$email = 'nicholasllayton@gmail.com';
	$id = 345;
    }
    
    $query = <<<_QUERY
	SELECT *
	FROM `{$id}`
	ORDER BY opt_saletotal ASC;
_QUERY;
    $result = $connection->query($query);
    if (!$result)
    { die($connection->connect_error); }

    $str = "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
    $str .= "<xml>";

    print_r($result);
    $rows = $result->num_rows;
    $result->data_seek(0);
    $row = $result->fetch_array(MYSQLI_ASSOC);
    //print_r($row);

    /*
    for ($i = 0; $i < $rows; ++$i)
    {
	$row = $result->fetch_array(MYSQLI_ASSOC);

	$currId = $row['opt-id'];
    } // for

    $str .= "</xml>";
    echo $str;
    */
?>

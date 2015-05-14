<?php
/*
 * this file receives the user ID
 * and email address from the form in 
 * signin.php and echoes "true" if the 
 * user exists in the database and echoes "false" otherwise
 */
$get = $_GET;
$rv = false;
// check if the GET variables are set for ID and email
if (isset($get['email']) && isset($get['id']))
{
    require_once 'login.php';
    require_once 'flight_tracker.php';
    
    // connect to DB
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
    $n = $result->num_rows;
    $data = $result->fetch_array(MYSQLI_ASSOC);
    $email = $data['email'];
    $id = $data['ID'];

    // check if user ID and email were unique in our database
    if ($n == 1 && $email == $get['email'] && $id == $get['id'])
	$rv = "true";
    else 
	$rv = "false";
} else // information was not received correctly
    $rv = "false;";

echo $rv;
?>

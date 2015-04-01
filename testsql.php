<?php
    require_once 'login.php';
    $connection = new mysqli ($db_hostname, $db_username, $db_password, $db_database);

    if($connection ->connect_error) 
	die($connection->connect_error);
    else
	echo "success";

/*
    mysqli_select_db($connection, $db_database)
	or die("Unable to select database: " . mysqli_error());
    $query = "select email from userinfo";
    $result = mysqli_query($connection,$query);
    if(!$result) die("Database access failed: " . mysql_error());

    $rows =mysqli_num_rows($result);
    for($j = 0; $j < $rows; ++$j){
	$row = mysqli_fetch_row($result);
	
	echo 'email: ' . $row[0] .'<br>';
    }
    */
?>

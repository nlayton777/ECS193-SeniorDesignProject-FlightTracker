<?php 
    $time_in_seconds = 51;
    ignore_user_abort();
    set_time_limit($time_in_seconds);

    require_once('login.php');
    $connection = new mysqli ($db_hostname, $db_username);
    if ($connection->connect_error) die($connection->connect_error);
    mysqli_select_db($connection,"flight_tracker");

    $count = 0;
    $end = time() + $time_in_seconds;
    while (time() < $end)
    {
	$query2 = "INSERT INTO temp (id,email,count) VALUES 
		   ({$_GET['id']},'{$_GET['email']}',{$count});";
	$result2 = $connection->query($query2);
	if (!$result2) die ($connection->error);

	sleep(5);
	$count++;
    }
?>

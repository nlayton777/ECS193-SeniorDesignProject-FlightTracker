<?php
require_once 'login.php';

// connect to database
    $connection = new mysqli ($db_hostname, $db_username);
    if($connection->connect_error) die($connection->connect_error);
    mysqli_select_db($connection,"flight_tracker");

$post = $_POST;
$interval = 10; //seconds for sleep function

do{

$curent_sec = time();


$query = "SELECT * FROM searches WHERE {$post['ID']} = ID and {$post['email']} = email"; 
$result = $connection->query($query);
if (!$result) die ($connection->error);
$query2 = "SELECT airline FROM airlines WHERE {$post['ID']} = ID and {$post['email']} = email"; 
$result2 = $connection->query($query2);
if (!$result2) die ($connection->error);


$result->data_seek(0);
$rows = $result->fetch_array(MYSQLI_ASSOC);
$end = $rows['end'];

$rows2 = $result2->num_rows;

$airlines=array(); 

for($i=0; $i<$rows2; $i++)
{
    $result2->data_seek($i);
    $rows3 = $result2->fetch_array(MYSQLI_ASSOC);

    $airlines[] = $rows3['airline'];
}

$ymd = explode(" ", $end);
$ymd2 = explode("-", $ymd[0]);
$ymd3 = explode(":", $ymd[1]);

$end_time = mktime($ymd3[0], $ymd3[1], $ymd3[2], $ymd2[1], $ymd2[2], $ymd2[0]);

if($current_sec < $end_time)
{

$current_info = array( 
    "ID" => $rows['ID'],
    "email" => $rows['email'],
    "origin" => $rows['source'],
    "destination" => $rows['destination'],
    "depart_date" => $rows['depart_date'],
    "return_date" => $rows['return_date'],
    "adults" => $rows['adults'],
    "children" => $rows['children'],
    "seniors" => $rows['seniors'],
    "seat_infant" => $rows['seat_infants'],
    "lap_infant" => $rows['lap_infants'], 
    "price" => $rows['price'], 
    
    "airlines" => $airlines
  );
    


}
sleep($interval);
} while($current_sec < $end_time);



?>

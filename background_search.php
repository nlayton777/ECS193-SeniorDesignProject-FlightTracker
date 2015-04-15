<?php
require_once 'login.php';
<<<<<<< HEAD
require_once 'flight_tracker.php';
define('__ROOT__',dirname(__FILE__));

    /*      NEED THESE FILES FOR QPX API        */  
    require_once(__ROOT__ . 
    '/google-api-php-client/src/Google/Service/QPXExpress.php');
    require_once(__ROOT__ .
    '/google-api-php-client/src/Google/Client.php');

// connect to database
    $connection = new mysqli ($db_hostname, $db_username);
    if($connection->connect_error) die($connection->connect_error);
    mysqli_select_db($connection,"flight_tracker");

$post = $_POST;
$interval = 10; //seconds for sleep function

do{

$curent_sec = time();


$query = "SELECT * FROM searches WHERE ID={$post['ID']} and email= {$post['email']}"; 
$result = $connection->query($query);
if (!$result) die ($connection->error);
$query2 = "SELECT airline FROM airlines WHERE ID = {$post['ID']}  and email= {$post['email']}"; 
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
    
    $finalresults = getResults($current_info);
    $trips = $finalresult->getTrips();
    $rowCount = parseResults($trips, $current_info);

}
sleep($interval);
} while($current_sec < $end_time);


function parseResults($trips, $post)
{
    $options = $trips->getTripOption();

    if (isset($options)) 
    {
        $multPass = false;
        if ($post['adults'] > 1 || $post['children'] > 1 || $post['seniors'] > 1 || 
        $post['seat_infants'] > 1 || $post['lap_infants'] > 1)
        $multPass = true;


        foreach ($options as $option) 
        {
            $saleTotal = substr($option->getSaleTotal(),3)


            foreach ($option->getSlice()[0]->getSegment() as $segment)
            {
                foreach ($segment->getLeg() as $leg)
                {
                    //Source location and departure time from source
                    $origin = $leg->getOrigin();
                    $time = explode("T",$leg->getDepartureTime());
                    $time2 = explode("-",$time[1]);
                    $depart_time = $time2[0];

                    //Destination location and arrival time from destination
                    $destination = $leg->getDestination();
                    $time3 = explode("T",$leg->getArrivalTime());
                    $time4 = explode("-",$time3[1]);
                    $arrival_time = $time4[0];
                }

                if (!isOneWay($post)) {



                }
            }
        }
    }
}


function isOneWay($post)
{
    $query5 = SELECT * FROM searches WHERE return_date == NULL;
    $result5 = $connection->query($query);
    if (!$result5) die ($connection->error);
    $val = true; 

    if(query5 != NULL )
        return $val;
}


?>

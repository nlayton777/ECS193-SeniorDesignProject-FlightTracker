<?php
require_once 'login.php';
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

$userID = $post['ID'];
$userEmail = $post['email'];

//Create table email@email.comID and add attributes 
$usertable = "CREATE TABLE {$userEmail}{$userID}".
                "(opt_id VARCHAR(60) NOT NULL PRIMARY KEY,".
                "opt_saletotal FLOAT(10) NOT NULL PRIMARY KEY,".
                "opt_seg_flight_carrier VARCHAR(40) NOT NULL,".
                "opt_seg_flight_num VARCHAR(10) NOT NULL,".
                "opt_seg_cabin VARCHAR(20) NOT NULL,".
                "opt_seg_leg_aircraft VARCHAR(20) NOT NULL,".
                "opt_seg_leg_arrival_time TIMESTAMP NOT NULL,".
                "opt_seg_leg_departure_time TIMESTAMP NOT NULL,".
                "opt_seg_leg_origin VARCHAR(10) NOT NULL,".
                "opt_seg_leg_destination VARCHAR(10) NOT NULL,".
                "opt_seg_leg_duration INT NOT NULL".
                 ");";
$resulttable = $connection->query($usertable);
if (!$resulttable) die ($connection->error);



do {
    $query = "SELECT * FROM searches WHERE ID={$userID} and email= {$userEmail};"; 
    $result = $connection->query($query);
    if (!$result) die ($connection->error);
    $query2 = "SELECT airline FROM airlines WHERE ID={$userID} and email= {$userEmail};"; 
    $result2 = $connection->query($query2);
    if (!$result2) die ($connection->error);


    $result->data_seek(0);
    $rows = $result->fetch_array(MYSQLI_ASSOC);
    $end = $rows['end'];
    $rows2 = $result2->num_rows;

    $min_price = $rows['lowest_price'];

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

$end_secs = mktime($ymd3[0], $ymd3[1], $ymd3[2], $ymd2[1], $ymd2[2], $ymd2[0]);

if($current_sec < $end_secs)
{

//insert user input into an array
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


    //Check to see if same result is already in table 
    $check = "SELECT * FROM {$userEmail}{$userID} WHERE opt_saletotal = {$rowCount['opt_saletotal']}". 
                "and opt_seg_flight_carrier =  {$rowCount['opt_seg_flight_carrier']} and ".
                "opt_seg_flight_num = {$rowCount['opt_seg_flight_num']} and ".
                "opt_seg_cabin = {$rowCount['opt_seg_cabin']} and ".  
                "opt_seg_leg_aircraft = {$rowCount['opt_seg_leg_aircraft']} and ".
                "opt_seg_leg_arrival_time = {$rowCount['opt_seg_leg_arrival_time']} and ".
                "opt_seg_leg_departure_time = {$rowCount['opt_seg_leg_deparature_time']} and ".
                "opt_seg_leg_origin = {$rowCount['opt_seg_leg_origin']} and ".
                "opt_seg_leg_destination = {$rowCount['opt_seg_leg_destination']} and ".
                "opt_seg_leg_duration = {$rowCount['opt_seg_leg_duration']};";



    $checkresult = $connection->query($check);
    if (!$checkresult) die ($connection->error);

    //If result is not already there, insert entry into table
    if($check == NULL)
    {
        $db_insertion = "INSERT INTO {userEmail}{userID}".
                        "(opt_id, opt_saletotal,opt_seg_flight_carrier,".
                        "opt_seg_flight_num,opt_seg_cabin,".  
                        "opt_seg_leg_aircraft,opt_seg_leg_arrival_time,".
                        "opt_seg_leg_departure_time,opt_seg_leg_origin,".
                        "opt_seg_leg_destination,opt_seg_leg_duration)".
                        "VALUES (".
                            "{$rowCount['opt_id']}, {$rowCount['opt_saletotal']}, {$rowCount['opt_seg_flight_carrier']},".
                            "{$rowCount['opt_seg_flight_num']}, {$rowCount['opt_seg_cabin']},".
                            "{$rowCount['opt_seg_leg_aircraft']}, {$rowCount['opt_seg_leg_arrival_time']},".
                            "{$rowCount['opt_seg_leg_departure_time']}, {$rowCount['opt_seg_leg_origin']}," .
                            "{$rowCount['opt_seg_leg_destination']}, {$rowCount['opt_seg_leg_duration']});";
        $resultinsert = $connection->query($db_insertion);
        if (!$resultinsert) die ($connection->error);

    }
    

    //If sale total is less than current lowest price found, update table and send mail to user
    if($rowCount['opt_saletotal'] < $min_price)
    {
        $min_price = $rowCount['opt_saletotal'];

        //*********************************EMAIL CODE ************************

                            define('__ROOT3__',dirname(__FILE__));
                                require_once(__ROOT3__ . '/vendor/autoload.php');
                                use Mailgun\Mailgun;
                                // sql query
                                // put query data in variables
                                # Instantiate the client.
                                $mgClient = new Mailgun('key-d76af0f266f20519801b8997210febfd');
                                $domain = "sandboxc740d3f374c749c391b5e8abfdee56b2.mailgun.org";

                                # Make the call to the client.
                                $result = $mgClient->sendMessage($domain, array(
                                    'from'    => 'UCD Flight Tracker <ucd.flight.tracker@gmail.com>',
                                    'to'      => '<'.$post['email'].'>',
                                    'subject' => 'We found a flight for you!  ',
                                    'html'    => '
                                    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                        <html xmlns="http://www.w3.org/1999/xhtml" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                        <head>
                        <meta name="viewport" content="width=device-width" />
                        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                        <title>UCD Flight Tracker</title>

                        </head>

                        <body itemscope itemtype="http://schema.org/EmailMessage" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6">

                        <table class="body-wrap" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6">
                            <tr style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                <td style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top"></td>
                                <td class="container" width="600" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; width: 100% !important; margin: 0 auto;" valign="top">
                                    <div class="content" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 10px;">
                                        <table class="main" width="100%" cellpadding="0" cellspacing="0" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;" bgcolor="#fff">
                                            <tr style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                <td class="alert alert-warning" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 30px; vertical-align: top; color: #fff; font-weight: 500; text-align: center; border-radius: 3px 3px 0 0; background-color: #f3a56f; margin: 0; padding: 20px;" align="center" bgcolor="#f3a56f" valign="top">
                                                    It\'s Time to Fly!
                                                </td>
                                            </tr>
                                            <tr style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                <td class="content-wrap" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 10px;" valign="top">
                                                    <table width="100%" cellpadding="0" cellspacing="0" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                        <tr style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                            <td class="image" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                                                <img src="https://download.unsplash.com/photo-1422464804701-7d8356b3a42f" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; max-width: 100%; margin: 0;" />
                                                                </td>
                                                        </tr>
                                                            <td class="content-block" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                                                We found the perfect flight for you!                                                            </td>
                                                        </tr>
                                                        <tr style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                            <td class="content-block" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
                                                                Our search bot found the perfect flight for you from'.$userSource.' to ' .$userDestination. '. Please act on the following information quickly as we are not certain how long these deals will last for. Use your request ID and email to login to our page to view your flight results. As a reminder your request ID was' .$userID.'. We hope you enjoy your flight.
                                                            </td>
                                                        </tr>
                                                        <tr style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                            <td class="content-block-button" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;" align="center" valign="top">
                                                                <a href="http://www.mailgun.com" class="btn-primary" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; color: #FFF; text-decoration: none; line-height: 2; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; text-transform: capitalize; background-color: #5e4763; margin: 0; border-color: #5e4763; border-style: solid; border-width: 10px 20px;">Pack Your Bags!</a>
                                                            </td>
                                                        </tr>
                                                        <tr style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                            <td class="content-block-button" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;" align="center" valign="top">
                                                                Thanks for choosing UCD Flight Tracker!
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                        <div class="footer" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; clear: both; color: #999; margin: 0; padding: 20px;">
                                            <table width="100%" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                <tr style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                                    <td class="aligncenter content-block" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 12px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;" align="center" valign="top"><a href="http://www.mailgun.com" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 12px; color: #999; text-decoration: underline; margin: 0;">Unsubscribe</a> from these alerts.</td>
                                                </tr>
                                            </table>
                                        </div></div>
                                </td>
                                <td style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"></td>
                            </tr>
                        </table>


                        <style type="text/css">
                        img { max-width: 100% !important; }
                        body { -webkit-font-smoothing: antialiased !important; -webkit-text-size-adjust: none !important; width: 100% !important; height: 100% !important; line-height: 1.6 !important; }
                        body { background-color: #f6f6f6 !important; }
                        </style>
                        </body>
                        </html>'
                                ));

                        echo "<pre>";
                        print_r($result);
                        echo "</pre>";

/************************* END OF EMAIL CODE *********************/

    }

}
sleep($interval);
} while($current_sec < $end_secs);



 function parseResults($trips, $post)
{
//     $options = $trips->getTripOption();

//     if (isset($options)) 
//     {
//         $multPass = false;
//         if ($post['adults'] > 1 || $post['children'] > 1 || $post['seniors'] > 1 || 
//         $post['seat_infants'] > 1 || $post['lap_infants'] > 1)
//         $multPass = true;


//         foreach ($options as $option) 
//         {
//             $saleTotal = substr($option->getSaleTotal(),3)


//             foreach ($option->getSlice()[0]->getSegment() as $segment)
//             {
//                 foreach ($segment->getLeg() as $leg)
//                 {
//                     //Source location and departure time from source
//                     $origin = $leg->getOrigin();
//                     $time = explode("T",$leg->getDepartureTime());
//                     $time2 = explode("-",$time[1]);
//                     $depart_time = $time2[0];

//                     //Destination location and arrival time from destination
//                     $destination = $leg->getDestination();
//                     $time3 = explode("T",$leg->getArrivalTime());
//                     $time4 = explode("-",$time3[1]);
//                     $arrival_time = $time4[0];
//                 }

//                 if (!checkIsOneWay($post)) {



//                 }
//             }
//         }
//     }
 } // parseResults($current_info)


function checkIsOneWay($post)
{
    $query5 = "SELECT * FROM searches WHERE return_date == NULL;";
    $result5 = $connection->query($query);
    if (!$result5) die ($connection->error);
    $val = true; 

    if($query5 != NULL )
        return $val;
}

?>

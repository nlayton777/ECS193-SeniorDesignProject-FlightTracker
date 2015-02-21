<?php
    define('__ROOT__',dirname(__FILE__));
    require_once(__ROOT__ . 
	'/google-api-php-client/src/Google/Service/QPXExpress.php');
    require_once(__ROOT__ .
	'/google-api-php-client/src/Google/Client.php');

    $client = new Google_Client();
    $client->setApplicationName("Flight Tracker");
    $client->setDeveloperKey("AIzaSyAxaZBEiV9Lwr8tni1sx2V6WVj8LKnrCas");
    $service = new Google_Service_QPXExpress($client);


    print_r($_POST);
?>

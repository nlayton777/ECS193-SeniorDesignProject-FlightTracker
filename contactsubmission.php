<?php
define('__ROOT3__',dirname(__FILE__));
require_once(__ROOT3__ . '/vendor/autoload.php');
use Mailgun\Mailgun;

# Instantiate the client.
$mgClient = new Mailgun('key-d76af0f266f20519801b8997210febfd');
$domain = "sandboxc740d3f374c749c391b5e8abfdee56b2.mailgun.org";
$post = $_POST;

$comments = $post['comments'];
$email = $post['email'];
$name = $post['name'];

# Make the call to the client.
$result = $mgClient->sendMessage($domain, array(
    'from'    => $name . ' <' . $email . '>',
    'to'      => 'UCD Flight Tracker <ucd.flight.tracker@gmail.com>',
    'subject' => 'Comments',
    'text'    => $comments 
));

header("location:contact.php"); 
?>


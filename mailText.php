<!DOCTYPE html>
<html>
	<?php
		define('__ROOT3__',dirname(__FILE__));
		require_once(__ROOT3__ . '/vendor/autoload.php');
		use Mailgun\Mailgun;

		# Instantiate the client.
		$mgClient = new Mailgun('key-d76af0f266f20519801b8997210febfd');
		$domain = "sandboxc740d3f374c749c391b5e8abfdee56b2.mailgun.org";

		# Make the call to the client.
		$result = $mgClient->sendMessage($domain, array(
    		'from'    => 'Excited User <ucd.flight.tracker@gmail.com>',
    		'to'      => 'Kir <kirmail24@gmail.com>',
    		'subject' => 'Hello',
    		'text'    => 'Testing 1.. 2.. 3..' ));
    ?>	
</html>

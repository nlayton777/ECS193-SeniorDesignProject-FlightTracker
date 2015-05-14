<?php
/*
 * this file destroys the current
 * session and then redirects the 
 * user to the page that is specified
 * in the POST request that's received.
 */
session_start();
session_unset();
session_destroy();
header("Location: http://localhost:10088/{$_POST['webpage']}");
exit();
?>


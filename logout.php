<?php
    session_start();
    session_unset();
    session_destroy();
    //header('Location: http://localhost:10088/signin.php');
    header("Location: http://localhost:10088/{$_POST['webpage']}");
    exit();
?>

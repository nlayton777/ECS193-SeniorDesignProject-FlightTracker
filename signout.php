<?php
session_start();
session_destroy();
header("Location: http://localhost:10088/{$_POST['file']}");
?>

<?php
session_start();
session_unset();
session_destroy();
//destroy session and send user to login page
header("Location: index.php");
?>
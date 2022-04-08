<?php
session_start();
//verify session is started or send to login screen
if (!isset($_SESSION['username'])) {
  header('Location: index.php');
  exit();
}
?>
<?php
session_start();

require_once('sql_queries.php');

if (isset($_REQUEST["content"]) && isset($_REQUEST['postID'])) {
    
    
    
}
else {
    echo json_encode(array("success" => "false"));
}
?>
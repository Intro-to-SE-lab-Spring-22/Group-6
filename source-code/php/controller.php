<?php
session_start();
require_once('functions.php');

//must have function parameter present
if (!isset($_REQUEST['function'])) {
    exit(json_encode(array("success" => "false", "message" => "no_function_selected")));
}

//edit a post
if ($_REQUEST['function'] == "editPost") {
    if (!isset($_REQUEST['content']) || !isset($_REQUEST['postID'])) {
        exit(json_encode(array("success" => "false", "message" => "no_function_selected")));
    }
    if (!isset($_SESSION['username'])) {
        exit(json_encode(array("success" => "false", "message" => "not_logged_in")));
    }
    $return_data = editPost($_REQUEST['postID'], $_REQUEST['content'], $_SESSION['username']);
    exit(json_encode($return_data));
}
?>
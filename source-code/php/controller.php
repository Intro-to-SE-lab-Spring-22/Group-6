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

//add comment
if ($_REQUEST['function'] == "addComment")
{
    if(isset($_REQUEST["content"]) && isset($_REQUEST['postID']))
    {
        $content = $_REQUEST["content"];
        $postID = $_REQUEST["postID"];
        $user = $_SESSION["username"];
        
        $return = addComments($content, $postID, $user);
        exit(json_encode($return));
    }
    else{  
        exit(json_encode(array("success" => "false"))); 
    }
}

//create a user
if($_REQUEST['function']== "createAcct"){

    $firstName = $_POST["firstname"];
    $lastName = $_POST["lastname"];
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    $return = createAccount($firstName, $lastName, $email, $username, $password);

    exit(json_encode($return));
}
?>
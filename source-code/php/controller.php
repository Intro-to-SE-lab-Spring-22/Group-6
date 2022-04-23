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
        exit(json_encode(array("success" => "false", "message" => "invalid_parameters")));
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

//login request
if($_REQUEST['function'] == "login")
{
    $username = $_REQUEST['username'];
    $password = $_REQUEST['password'];
    $return = login($username, $password);
    exit(json_encode($return));
}

//serach request
if($_REQUEST['function'] == "search")
{
    $searchq = $_POST['searchVal'];

    $return = search($searchq);
    if($return == "THERE ARE NO RESULTS")
    {
        exit($return);
    }
    //$response;
    //THERE IS A BUG HERE THAT IS BEING THROWN IDK WHAT IT IS THO
    
    // foreach ($return as $row) {
    //     $response .='
    //         <div class="post">
    //             <a href="userpage.php?user='.$row['id'].'">
    //                 <h2>'.$row['id'].'</h2>
    //             </a>                
    //         </div>    
    //     ';
    // }
    exit(json_encode($return));
}

//get a post
if ($_REQUEST['function'] == "getOnePost") {
    if (!isset($_REQUEST['postID'])) {
        exit(json_encode(array("success" => "false", "message" => "invalid_parameters")));
    }
    if (!isset($_SESSION['username'])) {
        exit(json_encode(array("success" => "false", "message" => "not_logged_in")));
    }
    exit(json_encode(getOnePost($_REQUEST['postID'], $_SESSION['username'])));
}

//get many posts

//like post
if ($_REQUEST['function'] == "likePost") {
    if (!isset($_REQUEST['postID'])) {
        exit(json_encode(array("success" => "false", "message" => "invalid_parameters")));
    }
    if (!isset($_SESSION['username'])) {
        exit(json_encode(array("success" => "false", "message" => "not_logged_in")));
    }

    exit(json_encode(likePost($_REQUEST['postID'], $_SESSION['username'])));
}

//get current user
if ($_REQUEST['function'] == "getUser") {
    if (!isset($_SESSION['username'])) {
        exit(json_encode(array("success" => "false", "message" => "no_session_user")));
    }
    exit(json_encode(array("success" => "true", "user" => $_SESSION['username'])));
}

//get all comments on a post
if ($_REQUEST['function'] == "getAllPostComments") {
    if (!isset($_REQUEST['postID'])) {
        exit(json_encode(array("success" => "false", "message" => "invalid_parameters")));
    }
    if (!isset($_SESSION['username'])) {
        exit(json_encode(array("success" => "false", "message" => "not_logged_in")));
    }

    exit(json_encode(getAllPostComments($_REQUEST['postID'], $_SESSION['username'])));
}

if ($_REQUEST['function'] == "logout") {
    session_unset();
    session_destroy();
    //destroy session and send user to login page
    exit(json_encode(array("success" => "true", "location" => "index.php")));
}

?>
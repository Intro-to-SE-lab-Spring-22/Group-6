<?php

session_start();
require_once('functions.php');

// if (isset($_FILES["image"])) {
//     $img = $_FILES["image"]["name"];
//     $tmp = $_FILES["image"]["tmp_name"];
//     $errorimg = $_FILES["image"]["error"];

//     exit(json_encode(uploadFile($img, $tmp, $errorimg)));
// }

//must have function parameter present
if (!isset($_REQUEST['function'])) {
    exit(json_encode(array("success" => "false", "message" => "no_function_selected")));
}

//edit a post
else if ($_REQUEST['function'] == "editPost") {
    if (!isset($_REQUEST['content']) || !isset($_REQUEST['postID'])) {
        exit(json_encode(array("success" => "false", "message" => "invalid_parameters")));
    }
    if (!isset($_SESSION['username'])) {
        exit(json_encode(array("success" => "false", "message" => "not_logged_in")));
    }

    if (isset($_FILES["image"])) {
        $has_image = true;
        $img = $_FILES["image"]["name"];
        $tmp = $_FILES["image"]["tmp_name"];
        $errorimg = $_FILES["image"]["error"];
    }
    else {
        $has_image = false;
        $img = "";
        $tmp = "";
        $errorimg = "";
    }

    $return_data = editPost($_REQUEST['postID'], $_REQUEST['content'], $_SESSION['username'], $has_image, $img, $tmp, $errorimg);
    exit(json_encode($return_data));
}

//add comment
else if ($_REQUEST['function'] == "addComment")
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
else if($_REQUEST['function']== "createAcct"){

    $firstName = $_POST["firstname"];
    $lastName = $_POST["lastname"];
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    $return = createAccount($firstName, $lastName, $email, $username, $password);

    exit(json_encode($return));
}

//login request
else if($_REQUEST['function'] == "login")
{
    $username = $_REQUEST['username'];
    $password = $_REQUEST['password'];
    $return = login($username, $password);
    exit(json_encode($return));
}

//serach request
else if($_REQUEST['function'] == "search")
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
else if ($_REQUEST['function'] == "getOnePost") {
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
else if ($_REQUEST['function'] == "likePost") {
    if (!isset($_REQUEST['postID'])) {
        exit(json_encode(array("success" => "false", "message" => "invalid_parameters")));
    }
    if (!isset($_SESSION['username'])) {
        exit(json_encode(array("success" => "false", "message" => "not_logged_in")));
    }

    exit(json_encode(likePost($_REQUEST['postID'], $_SESSION['username'])));
}

//get current user
else if ($_REQUEST['function'] == "getUser") {
    if (!isset($_SESSION['username'])) {
        exit(json_encode(array("success" => "false", "message" => "no_session_user")));
    }
    exit(json_encode(array("success" => "true", "user" => $_SESSION['username'])));
}

//get all comments on a post
else if ($_REQUEST['function'] == "getAllPostComments") {
    if (!isset($_REQUEST['postID'])) {
        exit(json_encode(array("success" => "false", "message" => "invalid_parameters")));
    }
    if (!isset($_SESSION['username'])) {
        exit(json_encode(array("success" => "false", "message" => "not_logged_in")));
    }

    exit(json_encode(getAllPostComments($_REQUEST['postID'], $_SESSION['username'])));
}

else if ($_REQUEST['function'] == "logout") {
    session_unset();
    session_destroy();
    //destroy session and send user to login page
    exit(json_encode(array("success" => "true", "location" => "index.php")));
}

else if ($_REQUEST['function'] == "uploadProfilePicture") {
    if (!isset($_FILES["image"])) {
        exit(json_encode(array("success" => "false", "message" => "invalid_parameters")));
    }
    if (!isset($_SESSION['username'])) {
        exit(json_encode(array("success" => "false", "message" => "not_logged_in")));
    }

    $img = $_FILES["image"]["name"];
    $tmp = $_FILES["image"]["tmp_name"];
    $errorimg = $_FILES["image"]["error"];

    exit(json_encode(uploadFile($img, $tmp, $errorimg, $_SESSION['username'])));
}

else if ($_REQUEST['function'] == "getUserProfilePicture") {
    if (!isset($_REQUEST['username'])) {
        exit(json_encode(array("success" => "false", "message" => "invalid_parameters")));
    }
    if (!isset($_SESSION['username'])) {
        exit(json_encode(array("success" => "false", "message" => "not_logged_in")));
    }

    exit(json_encode(getUserProfilePicture($_REQUEST['username'])));
}

else if ($_REQUEST['function'] == "uploadPostImage") {
    if (!isset($_FILES["image"]) || !isset($_REQUEST['postID'])) {
        exit(json_encode(array("success" => "false", "message" => "invalid_parameters")));
    }
    if (!isset($_SESSION['username'])) {
        exit(json_encode(array("success" => "false", "message" => "not_logged_in")));
    }

    $img = $_FILES["image"]["name"];
    $tmp = $_FILES["image"]["tmp_name"];
    $errorimg = $_FILES["image"]["error"];

    exit(json_encode(uploadPostFile($img, $tmp, $errorimg, $_SESSION['username'], $_REQUEST['postID'])));
    // exit("test");
}

else {
    exit(json_encode(array("success" => "false", "message" => "invalid_function_selected: ".$_REQUEST['function'])));
}
?>
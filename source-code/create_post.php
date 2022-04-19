<?php
session_start();

require_once('sql_queries.php');

//inserting post into database and getting last_insert_id
if (isset($_REQUEST["content"])) {
    $content = $_REQUEST["content"];
    $user = $_SESSION["username"];

    $conn = new mysqli($hn, $un, $pw, $db);

    if ($conn->connect_error) {
        echo json_encode(array("success" => "false"));
        die($conn->connect_error);
    }
  
    $new_postID = insertNewPost($user, $content);

    //send data back
    echo json_encode(array("success" => "true", "location" => "post.php?action=view&id=$new_postID"));
}
else {
    echo json_encode(array("success" => "false"));
}
?>
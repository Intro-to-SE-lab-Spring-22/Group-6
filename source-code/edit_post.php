<?php
session_start();

require_once('sql_queries.php');

if (isset($_REQUEST["content"]) && isset($_REQUEST["postID"])) {
    $content = $_REQUEST["content"];
    $postID = $_REQUEST["postID"];
    $user = $_SESSION["username"];

    if (!postExists($postID)) {
        echo json_encode(array("success" => "false"));
    }
    else {
        
        if (!postBelongsToUser($postID, $user)) {
            echo json_encode(array("success" => "false"));
        }
        else {
            updatePost($postID, $content);

            //send data back
            echo json_encode(array("success" => "true", "location" => "post.php?action=view&id=$postID"));
        }
    }
}
else {
    echo json_encode(array("success" => "false"));
}
?>
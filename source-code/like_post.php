<?php
session_start();

require_once('sql_queries.php');

//add or remove like relationship
if (isset($_REQUEST["postID"])) {
    $postID = $_REQUEST["postID"];
    $user = $_SESSION["username"];
    $conn = new mysqli($hn, $un, $pw, $db);

    if ($conn->connect_error) {
        die($conn->connect_error);
        echo json_encode(array("success" => "false", "reason" => "connection_error"));
    }

    if (postIsLikedByUser($postID, $user)) {

        removeLikeFromPost($postID, $user);

        $num_likes = getNumLikesById($postID);

        echo json_encode(array("success" => "true", "action" => "unliked", "numlikes" => $num_likes));
    }
    else {
        $data = getPostDataById($postID, $user);
        $post_user = $data['user_id'];

        if (areFriends($post_user, $user) || $post_user == $user) {

            addLikeToPost($postID, $user);
            $num_likes = getNumLikesById($postID);

            echo json_encode(array("success" => "true", "action" => "liked", "numlikes" => $num_likes));
            
        }
        else {
            echo json_encode(array("success" => "false", "reason" => "friend_error"));
        }
    }
}
else {
    echo json_encode(array("success" => "false", "reason" => "postID_error"));
}
?>
<?php
session_start();

require_once('sql_queries.php');

// exit(json_encode(array("success" => "false", "content" => $_REQUEST["content"])));

if (isset($_REQUEST["content"]) && isset($_REQUEST["postID"])) {
    $content = $_REQUEST["content"];
    $postID = $_REQUEST["postID"];
    $user = $_SESSION["username"];

    if (!postExists($postID)) {
        echo json_encode(array("success" => "false", "message" => "post_does_not_exist"));
    }
    else {
        
        if (!postBelongsToUser($postID, $user)) {
            echo json_encode(array("success" => "false", "message" => "access_error"));
        }
        else {
            updatePost($postID, $content);
            $post_data = accessDB_PostById($postID);
            $post_data['num_likes'] = accessDB_GetNumLikesById($postID);
            $post_data['is_liked'] = accessDB_PostIsLikedByUser($postID, $user);
            if ($post_data['user_id'] == $user) {
                $post_data['is_editable'] = true;
            }
            else {
                $post_data['is_editable'] = false;
            }

            //send data back
            echo json_encode(array("success" => "true", "data" => $post_data));
        }
    }
}
else {
    echo json_encode(array("success" => "false", "message" => "invalid_parameters"));
}
?>
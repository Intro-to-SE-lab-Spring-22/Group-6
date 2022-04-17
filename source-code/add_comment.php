<?php
session_start();

require_once('sql_queries.php');

if (isset($_REQUEST["content"]) && isset($_REQUEST['postID'])) {
    $content = $_REQUEST["content"];
    $postID = $_REQUEST["postID"];
    $user = $_SESSION["username"];

    if (!postExists($postID)) {
        echo json_encode(array("success" => "false"));
    }
    else {
        $data = getPostDataById($postID, $user);
        $post_user = $data['user_id'];

        $are_friends = areFriends($user, $post_user);

        if (!$are_friends && $post_user != $user) {
            echo json_encode(array("success" => "false"));
        }
        else {
            $new_commentID = insertNewComment($postID, $user, $content);

            $data = getCommentDataById($new_commentID);

            //get timestamp of comment
            $date = new DateTime($data['created_at']);
            $created_at = date_format($date, 'M j, Y \a\t H:i:s');
            
            //send comment data back to page
            echo json_encode(array("success" => "true", "user" => "$user", "content" => "$content", "commentID" => "$new_commentID", "created_at" => "$created_at"));
        }
    }
}
else {
    echo json_encode(array("success" => "false"));
}
?>
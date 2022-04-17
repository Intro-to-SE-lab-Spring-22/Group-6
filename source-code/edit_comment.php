<?php
session_start();

require_once('sql_queries.php');

if (isset($_REQUEST["content"]) && isset($_REQUEST['commentID'])) {
    $content = $_REQUEST["content"];
    $commentID = $_REQUEST["commentID"];
    $user = $_SESSION["username"];

    if (!commentExists($commentID)) {
        exit(json_encode(array("success" => "false")));
    }
    else {
        $data = getCommentDataById($commentID);
        $comment_user = $data['username'];

        if ($comment_user != $user) {
            echo json_encode(array("success" => "false"));
        }
        else {
            updateComment($commentID, $content);

            $data = getCommentDataById($commentID);

            $date = new DateTime($data['last_edited_at']);
            $last_edited_at = date_format($date, 'M j, Y \a\t H:i:s');

            echo json_encode(array("success" => "true", "user" => "$user", "content" => "$content", "commentID" => "$commentID", "last_edited_at" => "$last_edited_at"));
        }
    }
}
else {
    echo json_encode(array("success" => "false"));
}
?>
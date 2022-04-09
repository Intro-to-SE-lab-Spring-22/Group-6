<?php
session_start();

require_once('credentials.php');

if (isset($_REQUEST["content"]) && isset($_REQUEST["postID"])) {
    $content = $_REQUEST["content"];
    $postID = $_REQUEST["postID"];
    $user = $_SESSION["username"];

    $conn = new mysqli($hn, $un, $pw, $db);

    if ($conn->connect_error) {
        echo json_encode(array("success" => "false"));
        die($conn->connect_error);
    }

    //check that post id exists
    $query = "SELECT COUNT(*) AS post_exists FROM post WHERE postID = '$postID'";

    $result = $conn->query($query);

    if (!$result) {
        echo json_encode(array("success" => "false"));
        die($conn->error);
    }

    $data = mysqli_fetch_assoc($result);
    $post_exists = intval($data['post_exists']);

    //post id doesn't exist
    if ($post_exists == 0) {
        echo json_encode(array("success" => "false"));
    }
    else {
        //check that post belongs to editing user
        $query = "SELECT user_id FROM post WHERE postID = '$postID'";

        $result = $conn->query($query);

        if (!$result) {
            echo json_encode(array("success" => "false"));
            die($conn->error);
        }

        $data = mysqli_fetch_assoc($result);
        
        //post does not belong to editing user
        if ($data['user_id'] != $user) {
            echo json_encode(array("success" => "false"));
        }
        else {
            //set the post to new updated content
            $query = "UPDATE post SET content = '$content' WHERE postID = '$postID'";

            $result = $conn->query($query);

            if (!$result) {
                echo json_encode(array("success" => "false"));
                die($conn->error);
            }

            //send data back
            echo json_encode(array("success" => "true", "location" => "post.php?action=view&id=$postID"));
        }
    }
}
else {
    echo json_encode(array("success" => "false"));
}
?>
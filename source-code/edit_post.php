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

    $query = "SELECT COUNT(*) AS post_exists FROM post WHERE postID = '$postID'";

    $result = $conn->query($query);

    if (!$result) {
        echo json_encode(array("success" => "false"));
        die($conn->error);
    }

    $data = mysqli_fetch_assoc($result);
    $post_exists = intval($data['post_exists']);

    if ($post_exists == 0) {
        echo json_encode(array("success" => "false"));
    }
    else {
        $query = "SELECT user_id FROM post WHERE postID = '$postID'";

        $result = $conn->query($query);

        if (!$result) {
            echo json_encode(array("success" => "false"));
            die($conn->error);
        }

        $data = mysqli_fetch_assoc($result);
        
        if ($data['user_id'] != $user) {
            echo json_encode(array("success" => "false"));
        }
        else {
            $query = "UPDATE post SET content = '$content' WHERE postID = '$postID'";

            $result = $conn->query($query);

            if (!$result) {
                echo json_encode(array("success" => "false"));
                die($conn->error);
            }

            echo json_encode(array("success" => "true", "location" => "post.php?action=view&id=$postID"));
        }
    }
}
else {
    echo json_encode(array("success" => "false"));
}
?>
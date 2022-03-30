<?php
session_start();

require_once('credentials.php');

if (isset($_REQUEST["content"])) {
    $content = $_REQUEST["content"];
    $user = $_SESSION["username"];
    $postID = -1;

    $conn = new mysqli($hn, $un, $pw, $db);

    if ($conn->connect_error) {
        echo json_encode(array("success" => "false"));
        die($conn->connect_error);
    }

    $query = "SELECT MAX(postID) from post";

    $result = $conn->query($query);
    
    if (!$result) {
        die($conn->error);
    }

    if ($result->num_rows > 0) {
        $row = $result->fetch_array();
        $postID = $row["MAX(postID)"] + 1;
    }
    else {
        $postID = 1;
    }

    $conn = new mysqli($hn, $un, $pw, $db);

    if ($conn->connect_error) {
        echo json_encode(array("success" => "false"));
        die($conn->connect_error);
    }

    $query = "INSERT INTO post (postID, user_id, content) values ('$postID', '$user', '$content')";

    $result = $conn->query($query);

    if (!$result) {
        echo json_encode(array("success" => "false"));
        die($conn->error);
    }

    echo json_encode(array("success" => "true", "location" => "home.php"));
}
else {
    echo json_encode(array("success" => "false"));
}
?>
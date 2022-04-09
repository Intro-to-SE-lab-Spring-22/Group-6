<?php
session_start();

require_once('credentials.php');

//inserting post into database and getting last_insert_id
if (isset($_REQUEST["content"])) {
    $content = $_REQUEST["content"];
    $user = $_SESSION["username"];

    $conn = new mysqli($hn, $un, $pw, $db);

    if ($conn->connect_error) {
        echo json_encode(array("success" => "false"));
        die($conn->connect_error);
    }

    //add post to database
    $query = "INSERT INTO post (user_id, content) values ('$user', '$content')";

    $result = $conn->query($query);

    if (!$result) {
        echo json_encode(array("success" => "false"));
        die($conn->error);
    }

    //get id of new post
    $query = "SELECT LAST_INSERT_ID() as postID";

    $result = $conn->query($query);

    if (!$result) {
        echo json_encode(array("success" => "false"));
        die($conn->error);
    }

    $data = mysqli_fetch_assoc($result);
    $new_postID = $data['postID'];

    //send data back
    echo json_encode(array("success" => "true", "location" => "post.php?action=view&id=$new_postID"));
}
else {
    echo json_encode(array("success" => "false"));
}
?>
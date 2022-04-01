<?php
session_start();

require_once('credentials.php');

if (isset($_REQUEST["content"])) {
    $content = $_REQUEST["content"];
    $user = $_SESSION["username"];

    $conn = new mysqli($hn, $un, $pw, $db);

    if ($conn->connect_error) {
        echo json_encode(array("success" => "false"));
        die($conn->connect_error);
    }

    $query = "INSERT INTO post (user_id, content) values ('$user', '$content')";

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
<?php
session_start();

require_once('credentials.php');

if (isset($_REQUEST["content"]) && isset($_REQUEST['commentID'])) {
    $content = $_REQUEST["content"];
    $commentID = $_REQUEST["commentID"];
    $user = $_SESSION["username"];

    $conn = new mysqli($hn, $un, $pw, $db);

    if ($conn->connect_error) {
        echo json_encode(array("success" => "false"));
        die($conn->connect_error);
    }

    $query = "SELECT COUNT(*) as comment_exists FROM comments WHERE commentID = '$commentID'";

    $result = $conn->query($query);

    if (!$result) {
        echo json_encode(array("success" => "false"));
        die($conn->error);
    }

    $data = mysqli_fetch_array($result);
    $comment_exists = intval($data['comment_exists']);

    if ($comment_exists == 0) {
        echo json_encode(array("success" => "false"));
    }
    else {
        $query = "SELECT username FROM comments WHERE commentID = '$commentID'";

        $result = $conn->query($query);

        if (!$result) {
            echo json_encode(array("success" => "false"));
            die($conn->error);
        }

        $data = mysqli_fetch_array($result);
        $comment_user = $data['username'];

        if ($comment_user != $user) {
            echo json_encode(array("success" => "false"));
        }
        else {
            $query = "UPDATE comments SET content = '$content' WHERE commentID = '$commentID'";

            $result = $conn->query($query);

            if (!$result) {
                echo json_encode(array("success" => "false"));
                die($conn->error);
            }

            $query = "SELECT last_edited_at FROM comments WHERE commentID = '$commentID'";

            $result = $conn->query($query);

            if (!$result) {
                echo json_encode(array("success" => "false"));
                die($conn->error);
            }

            $data = mysqli_fetch_assoc($result);

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
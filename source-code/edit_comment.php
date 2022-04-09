<?php
session_start();

require_once('credentials.php');

//editing a comment
if (isset($_REQUEST["content"]) && isset($_REQUEST['commentID'])) {
    $content = $_REQUEST["content"];
    $commentID = $_REQUEST["commentID"];
    $user = $_SESSION["username"];

    $conn = new mysqli($hn, $un, $pw, $db);

    if ($conn->connect_error) {
        echo json_encode(array("success" => "false"));
        die($conn->connect_error);
    }

    //check that comment id exists
    $query = "SELECT COUNT(*) as comment_exists FROM comments WHERE commentID = '$commentID'";

    $result = $conn->query($query);

    if (!$result) {
        echo json_encode(array("success" => "false"));
        die($conn->error);
    }

    $data = mysqli_fetch_array($result);
    $comment_exists = intval($data['comment_exists']);

    //comment doesn't exist
    if ($comment_exists == 0) {
        echo json_encode(array("success" => "false"));
    }
    else {
        //check that comment belongs to editing user
        $query = "SELECT username FROM comments WHERE commentID = '$commentID'";

        $result = $conn->query($query);

        if (!$result) {
            echo json_encode(array("success" => "false"));
            die($conn->error);
        }

        $data = mysqli_fetch_array($result);
        $comment_user = $data['username'];

        //comment does not belong to editing user
        if ($comment_user != $user) {
            echo json_encode(array("success" => "false"));
        }
        else {
            //update the content of the comment in database
            $query = "UPDATE comments SET content = '$content' WHERE commentID = '$commentID'";

            $result = $conn->query($query);

            if (!$result) {
                echo json_encode(array("success" => "false"));
                die($conn->error);
            }

            //get new timestamp of comment
            $query = "SELECT last_edited_at FROM comments WHERE commentID = '$commentID'";

            $result = $conn->query($query);

            if (!$result) {
                echo json_encode(array("success" => "false"));
                die($conn->error);
            }

            $data = mysqli_fetch_assoc($result);

            //format date and time
            $date = new DateTime($data['last_edited_at']);
            $last_edited_at = date_format($date, 'M j, Y \a\t H:i:s');

            //send data back
            echo json_encode(array("success" => "true", "user" => "$user", "content" => "$content", "commentID" => "$commentID", "last_edited_at" => "$last_edited_at"));
        }
    }
}
else {
    echo json_encode(array("success" => "false"));
}
?>
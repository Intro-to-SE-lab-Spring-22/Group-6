<?php
session_start();

require_once('credentials.php');

if (isset($_REQUEST["content"]) && isset($_REQUEST['postID'])) {
    $content = $_REQUEST["content"];
    $postID = $_REQUEST["postID"];
    $user = $_SESSION["username"];

    $conn = new mysqli($hn, $un, $pw, $db);

    if ($conn->connect_error) {
        echo json_encode(array("success" => "false"));
        die($conn->connect_error);
    }

    $query = "SELECT COUNT(*) as post_exists FROM post WHERE postID = '$postID'";

    $result = $conn->query($query);

    if (!$result) {
        echo json_encode(array("success" => "false"));
        die($conn->error);
    }

    $data = mysqli_fetch_array($result);
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

        $data = mysqli_fetch_array($result);
        $post_user = $data['user_id'];

        $query = "SELECT COUNT(*) AS are_friends FROM friends WHERE id_sender = '$user' AND id_receiver = '$post_user'";

        $result = $conn->query($query);

        if (!$result) {
            echo json_encode(array("success" => "false"));
            die($conn->error);
        }

        $data = mysqli_fetch_array($result);
        $are_friends = intval($data['are_friends']);

        if ($are_friends == 0 && $post_user != $user) {
            echo json_encode(array("success" => "false"));
        }
        else {
            $query = "INSERT INTO comments (postID, username, content) VALUES ('$postID', '$user', '$content')";

            $result = $conn->query($query);

            if (!$result) {
                echo json_encode(array("success" => "false"));
                die($conn->error);
            }

            $query = "SELECT LAST_INSERT_ID() as commentID";

            $result = $conn->query($query);

            if (!$result) {
                echo json_encode(array("success" => "false"));
                die($conn->error);
            }

            $data = mysqli_fetch_assoc($result);
            $new_commentID = $data['commentID'];

            $query = "SELECT created_at FROM comments WHERE commentID = '$new_commentID'";

            $result = $conn->query($query);

            if (!$result) {
                echo json_encode(array("success" => "false"));
                die($conn->error);
            }

            $data = mysqli_fetch_assoc($result);

            $date = new DateTime($data['created_at']);
            $created_at = date_format($date, 'M j, Y \a\t H:i:s');

            echo json_encode(array("success" => "true", "user" => "$user", "content" => "$content", "commentID" => "$new_commentID", "created_at" => "$created_at"));
        }
    }
}
else {
    echo json_encode(array("success" => "false"));
}
?>
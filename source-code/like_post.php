<?php
session_start();

require_once('credentials.php');

if (isset($_REQUEST["postID"])) {
    $postID = $_REQUEST["postID"];
    $user = $_SESSION["username"];

    $conn = new mysqli($hn, $un, $pw, $db);

    if ($conn->connect_error) {
        die($conn->connect_error);
        echo json_encode(array("success" => "false", "reason" => "connection_error"));
    }

    $query = "SELECT COUNT(*) as already_liked FROM likes WHERE postID = '".$postID."' AND username = '".$user."'";

    $result = $conn->query($query);

    if (!$result) {
        die($conn->error);
        echo json_encode(array("success" => "false", "reason" => "connection_error"));
    }

    $data = mysqli_fetch_assoc($result);

    $already_liked = intval($data['already_liked']);

    if ($already_liked > 0) {
        $query = "DELETE FROM likes WHERE postID = '".$postID."' AND username = '".$user."'";

        $result = $conn->query($query);

        if (!$result) {
            die($conn->error);
            echo json_encode(array("success" => "false", "reason" => "connection_error"));
        }

        $query = "SELECT COUNT(*) as numlikes FROM likes WHERE postID = '".$postID."'";

        $result = $conn->query($query);

        if (!$result) {
            die($conn->error);
            echo json_encode(array("success" => "false", "reason" => "connection_error"));
        }

        $data = mysqli_fetch_assoc($result);

        echo json_encode(array("success" => "true", "action" => "unliked", "numlikes" => $data['numlikes']));
    }
    else {
        $query = "SELECT user_id FROM post WHERE postID = ".$postID;

        $result = $conn->query($query);

        if (!$result) {
            die($conn->error);
            echo json_encode(array("success" => "false", "reason" => "connection_error"));
        }

        $data = mysqli_fetch_assoc($result);

        $post_user = $data['user_id'];

        $query = "SELECT COUNT(*) as are_friends FROM friends WHERE id_sender = '".$post_user."' AND id_receiver = '".$user."'";

        $result = $conn->query($query);

        if (!$result) {
            die($conn->error);
        }

        $data = mysqli_fetch_assoc($result);
        $are_friends = intval($data['are_friends']);

        if ($are_friends > 0 || $post_user == $user) {
            $query = "INSERT INTO likes VALUES ('".$postID."', '".$user."')";

            $result = $conn->query($query);

            if (!$result) {
                die($conn->error);
                echo json_encode(array("success" => "false", "reason" => "connection_error"));
            }

            $query = "SELECT COUNT(*) as numlikes FROM likes WHERE postID = '".$postID."'";

            $result = $conn->query($query);

            if (!$result) {
                die($conn->error);
                echo json_encode(array("success" => "false", "reason" => "connection_error"));
            }

            $data = mysqli_fetch_assoc($result);

            echo json_encode(array("success" => "true", "action" => "liked", "numlikes" => $data['numlikes']));
            
        }
        else {
            echo json_encode(array("success" => "false", "reason" => "friend_error"));
        }
    }
}
else {
    echo json_encode(array("success" => "false", "reason" => "postID_error"));
}
?>
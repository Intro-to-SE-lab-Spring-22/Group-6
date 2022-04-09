<?php
session_start();

require_once('credentials.php');

//add or remove like relationship
if (isset($_REQUEST["postID"])) {
    $postID = $_REQUEST["postID"];
    $user = $_SESSION["username"];
    $conn = new mysqli($hn, $un, $pw, $db);

    if ($conn->connect_error) {
        die($conn->connect_error);
        echo json_encode(array("success" => "false", "reason" => "connection_error"));
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
        //check if user has already liked the post
        $query = "SELECT COUNT(*) as already_liked FROM likes WHERE postID = '".$postID."' AND username = '".$user."'";

        $result = $conn->query($query);

        if (!$result) {
            die($conn->error);
            echo json_encode(array("success" => "false", "reason" => "connection_error"));
        }

        $data = mysqli_fetch_assoc($result);
        $already_liked = intval($data['already_liked']);
        
        //user has alredy liked post, remove like relationship from db
        if ($already_liked > 0) {
            $query = "DELETE FROM likes WHERE postID = '".$postID."' AND username = '".$user."'";

            $result = $conn->query($query);

            if (!$result) {
                die($conn->error);
                echo json_encode(array("success" => "false", "reason" => "connection_error"));
            }

            //get new number of likes for the post
            $query = "SELECT COUNT(*) as numlikes FROM likes WHERE postID = '".$postID."'";

            $result = $conn->query($query);

            if (!$result) {
                die($conn->error);
                echo json_encode(array("success" => "false", "reason" => "connection_error"));
            }

            $data = mysqli_fetch_assoc($result);

            //return updated number of likes
            echo json_encode(array("success" => "true", "action" => "unliked", "numlikes" => $data['numlikes']));
        }
        else {
            //get user id of post creator
            $query = "SELECT user_id FROM post WHERE postID = ".$postID;

            $result = $conn->query($query);

            if (!$result) {
                die($conn->error);
                echo json_encode(array("success" => "false", "reason" => "connection_error"));
            }

            $data = mysqli_fetch_assoc($result);
            $post_user = $data['user_id'];

            //check if post creater and logged-in user are friends
            $query = "SELECT COUNT(*) as are_friends FROM friends WHERE id_sender = '".$post_user."' AND id_receiver = '".$user."'";

            $result = $conn->query($query);

            if (!$result) {
                die($conn->error);
            }

            $data = mysqli_fetch_assoc($result);
            $are_friends = intval($data['are_friends']);

            //friends, or logged-in user created the post
            if ($are_friends > 0 || $post_user == $user) {
                //add like relationshi[]
                $query = "INSERT INTO likes VALUES ('".$postID."', '".$user."')";

                $result = $conn->query($query);

                if (!$result) {
                    die($conn->error);
                    echo json_encode(array("success" => "false", "reason" => "connection_error"));
                }

                //get updated number of likes
                $query = "SELECT COUNT(*) as numlikes FROM likes WHERE postID = '".$postID."'";

                $result = $conn->query($query);

                if (!$result) {
                    die($conn->error);
                    echo json_encode(array("success" => "false", "reason" => "connection_error"));
                }

                $data = mysqli_fetch_assoc($result);

                //send data back
                echo json_encode(array("success" => "true", "action" => "liked", "numlikes" => $data['numlikes']));
                
            }
            else {
                echo json_encode(array("success" => "false", "reason" => "friend_error"));
            }
        }
    }
}
else {
    echo json_encode(array("success" => "false", "reason" => "postID_error"));
}
?>
<?php
session_start();

require_once('credentials.php');
//require_once("verify_user.php");

if (isset($_REQUEST["username"]) && isset($_REQUEST["update"])) {
    $display_username = $_REQUEST["username"];
    $update = $_REQUEST["update"];
    $user_username = $_SESSION["username"];

    $conn = new mysqli($hn, $un, $pw, $db);

    if ($conn->connect_error) {
        die($conn->connect_error);
    }

    $user_exists = $are_friends = $request_sent = $request_pending = false;

    //check that target user exists
    $query = "SELECT * FROM users
    WHERE id = '$display_username'";

    $result = $conn->query($query);

    if (!$result) {
        die($conn->error);
    }
    if ($result->num_rows > 0) {
        $user_exists = true;
    }

    //user exists and is distinct from logged-in user
    if ($user_exists && $user_username != $display_username) {

        //check if already friends
        $query = "SELECT * FROM friends WHERE id_sender = '$user_username' AND id_receiver = '$display_username'";
    
        $result = $conn->query($query);
    
        if (!$result) {
            die($conn->error);
        }
        
        //are friends
        if ($result->num_rows > 0) {
            $are_friends = true;

            //update friend status
            if ($update == "true") {
                //remove friendship
                $query = "DELETE FROM friends
                WHERE id_sender='$display_username' AND id_receiver='$user_username'";

                $result = $conn->query($query);

                if (!$result) {
                    die($conn->error);
                }

                $query = "DELETE FROM friends
                WHERE id_sender='$user_username' AND id_receiver='$display_username'";

                $result = $conn->query($query);

                if (!$result) {
                    die($conn->error);
                }
                echo "Add Friend";
            }

            //do not update friend status
            else {
                echo "Remove Friend";
            }
        }
        else {
            //check if friend request already sent
            $query = "SELECT * FROM pendingfriends WHERE from_id = '$user_username' AND to_id = '$display_username'";
    
            $result = $conn->query($query);
    
            if (!$result) {
                die($conn->error);
            }
    
            //friend request already sent
            if ($result->num_rows > 0) {
                $request_sent = true;

                //update friend status
                if ($update == "true") {
                    //remove friend request
                    $query = "DELETE FROM pendingfriends
                    WHERE from_id='$user_username' AND to_id='$display_username'";

                    $result = $conn->query($query);

                    if (!$result) {
                        die($conn->error);
                    }
                    echo "Add Friend";
                }
                //do not update friend status
                else {
                    echo "Cancel Friend Request";
                }
            }
            else {
                //check if friend request is pending
                $query = "SELECT * FROM pendingfriends WHERE to_id = '$user_username' AND from_id = '$display_username'";
    
                $result = $conn->query($query);
    
                if (!$result) {
                    die($conn->error);
                }
    
                //friend request is pending
                if ($result->num_rows > 0) {
                    $request_pending = true;

                    //update friend status
                    if ($update == "true") {
                        //remove friend request
                        $query = "DELETE FROM pendingfriends
                        WHERE from_id='$display_username' AND to_id='$user_username'";
    
                        $result = $conn->query($query);
    
                        if (!$result) {
                            die($conn->error);
                        }

                        //add users to friends table
                        $query = "INSERT INTO friends
                        (id_sender, id_receiver) VALUES ('$user_username', '$display_username')";
    
                        $result = $conn->query($query);
    
                        if (!$result) {
                            die($conn->error);
                        }

                        $query = "INSERT INTO friends
                        (id_sender, id_receiver) VALUES ('$display_username', '$user_username')";
    
                        $result = $conn->query($query);
    
                        if (!$result) {
                            die($conn->error);
                        }

                        echo "Remove Friend";
                    }
                    //do not update friend status
                    else {
                        echo "Accept Friend Request";
                    }
                }
                //not friends and no friend request
                else {
                    //update friend status
                    if ($update == "true") {
                        //create friend request
                        $query = "INSERT INTO pendingfriends
                        VALUES ('$user_username', '$display_username')";

                        $result = $conn->query($query);

                        if (!$result) {
                            die($conn->error);
                        }

                        echo "Cancel Friend Request";
                    }
                    //do not update friend status
                    else {
                        echo "Add Friend";
                    }
                }
            }
        }
    }
}
?>


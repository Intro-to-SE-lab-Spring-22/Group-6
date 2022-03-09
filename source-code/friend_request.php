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

    // Display data
    $query = "SELECT * FROM users
    WHERE id = '$display_username'";

    $result = $conn->query($query);

    if (!$result) {
        die($conn->error);
    }
    if ($result->num_rows > 0) {
        $user_exists = true;
    }

    if ($user_exists && $user_username != $display_username) {
        // Check if already friends
        $query = "SELECT * FROM friends WHERE id_sender = '$user_username' AND id_receiver = '$display_username'";
    
        $result = $conn->query($query);
    
        if (!$result) {
            die($conn->error);
        }
    
        if ($result->num_rows > 0) {
            $are_friends = true;
            if ($update == "true") {
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
            else {
                echo "Remove Friend";
            }
        }
        else {
            // Check if friend request already sent
            $query = "SELECT * FROM pendingfriends WHERE from_id = '$user_username' AND to_id = '$display_username'";
    
            $result = $conn->query($query);
    
            if (!$result) {
                die($conn->error);
            }
    
            if ($result->num_rows > 0) {
                $request_sent = true;
                if ($update == "true") {
                    $query = "DELETE FROM pendingfriends
                    WHERE from_id='$user_username' AND to_id='$display_username'";

                    $result = $conn->query($query);

                    if (!$result) {
                        die($conn->error);
                    }
                    echo "Add Friend";
                }
                else {
                    echo "Cancel Friend Request";
                }
            }
            else {
                // Check if friend request is pending
                $query = "SELECT * FROM pendingfriends WHERE to_id = '$user_username' AND from_id = '$display_username'";
    
                $result = $conn->query($query);
    
                if (!$result) {
                    die($conn->error);
                }
    
                if ($result->num_rows > 0) {
                    $request_pending = true;
                    if ($update == "true") {
                        $query = "DELETE FROM pendingfriends
                        WHERE from_id='$display_username' AND to_id='$user_username'";
    
                        $result = $conn->query($query);
    
                        if (!$result) {
                            die($conn->error);
                        }

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
                    else {
                        echo "Accept Friend Request";
                    }
                }
                else {
                    if ($update == "true") {
                        $query = "INSERT INTO pendingfriends
                        VALUES ('$user_username', '$display_username')";

                        $result = $conn->query($query);

                        if (!$result) {
                            die($conn->error);
                        }

                        echo "Cancel Friend Request";
                    }
                    else {
                        echo "Add Friend";
                    }
                }
            }
        }
    }
}
?>


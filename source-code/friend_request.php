<?php
session_start();

require_once('sql_queries.php');

if (isset($_REQUEST["username"]) && isset($_REQUEST["update"])) {
    $display_username = $_REQUEST["username"];
    $update = $_REQUEST["update"];
    $user_username = $_SESSION["username"];

    if (getUserDataById($display_username) && $user_username != $display_username) {
        // Check if already friends
    
        if (areFriends($user_username, $display_username)) {
            if ($update == "true") {
                removeFriends($user_username, $display_username);
                echo "Add Friend";
            }

            //do not update friend status
            else {
                echo "Remove Friend";
            }
        }
        else {
    
            if (friendRequestPending($user_username, $display_username)) {
                if ($update == "true") {
                    removeFriendRequest($user_username, $display_username);
                    echo "Add Friend";
                }
                //do not update friend status
                else {
                    echo "Cancel Friend Request";
                }
            }
            else {
    
                if (friendRequestPending($display_username, $user_username)) {
                    if ($update == "true") {
                        removeFriendRequest($display_username, $user_username);
                        addFriends($user_username, $display_username);

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
                        addFriendRequest($user_username, $display_username);

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


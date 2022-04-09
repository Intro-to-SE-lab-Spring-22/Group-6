<?php
session_start();

require_once('credentials.php');

if (isset($_REQUEST['username']) && isset($_REQUEST['password'])) {
    $username = $_REQUEST['username'];
    $password = $_REQUEST['password'];
    $conn = new mysqli($hn, $un, $pw, $db);



    if ($conn->connect_error) {
        die($conn->connect_error);
    }
    //getting usernames 
    $query = "SELECT * FROM users
    WHERE id = '$username'";

    $result = $conn->query($query);
    if (!$result) {
        die($conn->error);
    }
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_array()) {

            //verify plaintext vs hash to allow log in
            if (password_verify($password,$row['password'])) {
                echo json_encode(array("success" => "true", "location" => "home.php"));
                $_SESSION['username'] = $username;
                break;
            }
            else {
                echo json_encode(array("success" => "false"));
                break;
            }
        }
    }
    else {
        echo json_encode(array("success" => "false"));
    }
}

?>
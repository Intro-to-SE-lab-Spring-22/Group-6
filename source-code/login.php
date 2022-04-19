<?php
session_start();

require_once('sql_queries.php');

if (isset($_REQUEST['username']) && isset($_REQUEST['password'])) {
    $username = $_REQUEST['username'];
    $password = $_REQUEST['password'];

    $user_data = getUserDataById($username);

    if ($user_data) {
        if (password_verify($password, $user_data['password'])) {           
            $_SESSION['username'] = $username;
            exit(json_encode(array("success" => "true", "location" => "home.php")));
        }
        else {
            exit(json_encode(array("success" => "false")));
        }
    }
    else {
        exit(json_encode(array("success" => "false")));
    }
}

?>
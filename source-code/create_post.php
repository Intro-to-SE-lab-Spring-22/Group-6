<?php
session_start();

require_once('sql_queries.php');

//inserting post into database and getting last_insert_id
if (isset($_REQUEST["content"])) {
    $content = $_REQUEST["content"];
    $user = $_SESSION["username"];

    $conn = new mysqli($hn, $un, $pw, $db);

    if ($conn->connect_error) {
        exit(json_encode(array("success" => "false")));
        die($conn->connect_error);
    }
  
    $new_postID = insertNewPost($user, $content);

    if (isset($_FILES['image'])) {
        $img = $_FILES["image"]["name"];
        $tmp = $_FILES["image"]["tmp_name"];
        $errorimg = $_FILES["image"]["error"];

        $path = "../images/post/";
        if (!is_dir($path.$new_postID)) {
            mkdir($path.$new_postID);
        }
        $path = $path.$new_postID."/";

        array_map('unlink', glob($path.$new_postID.".*"));

        $imageFileType = strtolower(pathinfo($img,PATHINFO_EXTENSION));

        $path = $path.$new_postID.".".$imageFileType;
    
        if (!move_uploaded_file($tmp, $path)) {
            exit(json_encode(array("success" => "false")));
        }
    }

    // send data back
    exit(json_encode(array("success" => "true", "location" => "post.php?action=view&id=$new_postID")));
}
else {
    echo json_encode(array("success" => "false"));
}
?>
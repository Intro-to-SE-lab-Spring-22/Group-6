<?php
require_once("verify_user.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">
  <script src="js/functions.js"></script>
  <script src="js/post_requests.js"></script>
  <title>Home</title>
  <!-- jQuery + Bootstrap JS -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
  <script src="https://kit.fontawesome.com/c56bd8cfd4.js" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

</head>

<body <?=$onload?> >
    <?php include ("navbar.php"); ?>
    <main>
    </main>

<script>
   
    var main = document.querySelector("main");
    const postID = selectGetVariable("id");
    
    if (postID >= 0) {
        post_getOnePost(postID);
        post_getAllCommentsByPost(postID);
        post_addNewCommentBox();
    }
    else {
        post_addNewPostBox();
    }

</script>
</body>
</html>
<?php

require_once("verify_user.php");
require_once('credentials.php');

$username = $_SESSION["username"];

if (isset($_GET['id'])) {
    if (isset($_GET['action'])) {
        if ($_GET['action'] == 'create') {
            header('Location: post.php?action=create');
        }
        else if ($_GET['action'] != 'view' && $_GET['action'] != 'edit') {
            header('Location: post.php?action=view&id='.$_GET['id']);
        }
    }
    else {
        header('Location: post.php?action=view&id='.$_GET['id']);
    }
}
else {
    if (isset($_GET['action'])) {
        if ($_GET['action'] != 'create') {
            header('Location: home.php');
        }
    }
    else {
        header('Location: home.php');
    }
}

$conn = new mysqli($hn, $un, $pw, $db);

if ($conn->connect_error) {
    die($conn->connect_error);
}

if ($_GET['action'] != 'create') {
    $query = "SELECT COUNT(*) as post_exists FROM post WHERE postID=".$_GET['id'];

    $result = $conn->query($query);

    if (!$result) {
        die($conn->error);
    }

    $data = mysqli_fetch_assoc($result);
    $post_exists = intval($data['post_exists']);

    if ($post_exists == 0) {
        header('Location: home.php');
    }
}
if ($_GET['action'] == 'edit') {
    $query = "SELECT user_id FROM post WHERE postID = '".$_GET['id']."'";

    $result = $conn->query($query);

    if (!$result) {
        die($conn->error);
    }

    $data = mysqli_fetch_assoc($result);

    if ($username != $data['user_id']) {
        header('Location: post.php?action=view&id='.$_GET['id']);
    }
}
else if ($_GET['action'] == 'view') {
    $query = "SELECT user_id FROM post WHERE postID = '".$_GET['id']."'";

    $result = $conn->query($query);

    if (!$result) {
        die($conn->error);
    }

    $data = mysqli_fetch_assoc($result);
    $post_username = $data['user_id'];

    $query = "SELECT COUNT(*) AS are_friends FROM friends WHERE id_sender = '".$username."' AND id_receiver = '".$post_username."'";

    $result = $conn->query($query);

    if (!$result) {
        die($conn->error);
    }

    $data = mysqli_fetch_assoc($result);
    $are_friends = intval($data['are_friends']);

    if ($are_friends == 0 && $post_username != $username) {
        header('Location: home.php');
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">
  <title>Home</title>
  <!-- jQuery + Bootstrap JS -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
  <script src="https://kit.fontawesome.com/c56bd8cfd4.js" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

</head>

<?php
    if ($_GET['action'] == 'edit' || $_GET['action'] == 'create') {
        $onload = 'onload="document.getElementById(\'content\').parentNode.dataset.replicatedValue = document.getElementById(\'content\').value"';
    }
    else $onload = "";
?>

<body <?=$onload?>>
    <nav class="navbar">
        <ul class="navbar-nav">
            <li class="nav-item" id="home">
                <a href="home.php" class="nav-link">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--! Font Awesome Pro 6.0.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M575.8 255.5C575.8 273.5 560.8 287.6 543.8 287.6H511.8L512.5 447.7C512.5 450.5 512.3 453.1 512 455.8V472C512 494.1 494.1 512 472 512H456C454.9 512 453.8 511.1 452.7 511.9C451.3 511.1 449.9 512 448.5 512H392C369.9 512 352 494.1 352 472V384C352 366.3 337.7 352 320 352H256C238.3 352 224 366.3 224 384V472C224 494.1 206.1 512 184 512H128.1C126.6 512 125.1 511.9 123.6 511.8C122.4 511.9 121.2 512 120 512H104C81.91 512 64 494.1 64 472V360C64 359.1 64.03 358.1 64.09 357.2V287.6H32.05C14.02 287.6 0 273.5 0 255.5C0 246.5 3.004 238.5 10.01 231.5L266.4 8.016C273.4 1.002 281.4 0 288.4 0C295.4 0 303.4 2.004 309.5 7.014L564.8 231.5C572.8 238.5 576.9 246.5 575.8 255.5L575.8 255.5z"/></svg>
                    
                    <span class="link-text">Home</span>
                </a>
            </li>
            <li class="nav-item" id="compose">
                <a href="#" class="nav-link">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M467.1 241.1L351.1 288h94.34c-7.711 14.85-16.29 29.28-25.87 43.01l-132.5 52.99h85.65c-59.34 52.71-144.1 80.34-264.5 52.82l-68.13 68.13c-9.38 9.38-24.56 9.374-33.94 0c-9.375-9.375-9.375-24.56 0-33.94l253.4-253.4c4.846-6.275 4.643-15.19-1.113-20.95c-6.25-6.25-16.38-6.25-22.62 0l-168.6 168.6C24.56 58 366.9 8.118 478.9 .0846c18.87-1.354 34.41 14.19 33.05 33.05C508.7 78.53 498.5 161.8 467.1 241.1z"/></svg>
                    
                    <span class="link-text">Compose</span>
                </a>
            </li>
            <li class="nav-item" id="profile">
                <a href="userpage.php?user=<?= $_SESSION["username"]?>" class="nav-link">
                
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.0.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M224 256c70.7 0 128-57.31 128-128s-57.3-128-128-128C153.3 0 96 57.31 96 128S153.3 256 224 256zM274.7 304H173.3C77.61 304 0 381.6 0 477.3c0 19.14 15.52 34.67 34.66 34.67h378.7C432.5 512 448 496.5 448 477.3C448 381.6 370.4 304 274.7 304z"/></svg>
                
                    <span class="link-text">Profile</span>
                </a>
            </li>
            <li class="nav-item" id="logout">
                <a href="logout.php" class="nav-link">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.0.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M96 480h64C177.7 480 192 465.7 192 448S177.7 416 160 416H96c-17.67 0-32-14.33-32-32V128c0-17.67 14.33-32 32-32h64C177.7 96 192 81.67 192 64S177.7 32 160 32H96C42.98 32 0 74.98 0 128v256C0 437 42.98 480 96 480zM504.8 238.5l-144.1-136c-6.975-6.578-17.2-8.375-26-4.594c-8.803 3.797-14.51 12.47-14.51 22.05l-.0918 72l-128-.001c-17.69 0-32.02 14.33-32.02 32v64c0 17.67 14.34 32 32.02 32l128 .001l.0918 71.1c0 9.578 5.707 18.25 14.51 22.05c8.803 3.781 19.03 1.984 26-4.594l144.1-136C514.4 264.4 514.4 247.6 504.8 238.5z"/></svg>
                    <span class="link-text">Log Out</span>
                </a>
            </li>  

        </ul>
    </nav>
    <div id="right" class="column">
        <nav class="topnav">
                
                
                <!-- <label for="search">Search</label>   -->
                
                <!-- <a href="search"> -->
                <input type="text" placeholder="Search">
                <a href="search.php" id="search">
                    <!-- <button type="submit"> -->
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <span class="link-text">Search</span>
                    <!-- </button> -->
                
                </a>
        </nav>
    <main>
        <?php
        if ($_GET['action'] == 'view' || $_GET['action'] == 'edit') {
            $query = "SELECT * FROM post WHERE postID = '".$_GET['id']."'";
            
            $result = $conn->query($query);

            if (!$result) {
                die($conn->error);
            }
        
            $data = mysqli_fetch_assoc($result);

            $query = "SELECT COUNT(*) as numlikes FROM likes WHERE postID = ".$data['postID'];

            $subresult = $conn->query($query);
            
            if (!$subresult) {
                die($connection->error);
            }
            
            $subdata = mysqli_fetch_assoc($subresult);
            $numlikes = $subdata['numlikes'];

            $query = "SELECT COUNT(*) as already_liked FROM likes WHERE postID = '".$data['postID']."' AND username = '".$username."'";

            $subresult = $conn->query($query);
            
            if (!$subresult) {
                die($connection->error);
            }

            $subdata = mysqli_fetch_assoc($subresult);
            $already_liked = intval($subdata['already_liked']);

            if ($already_liked > 0) {
                $like_class = " is-liked";
            }
            else {
                $like_class = "";
            }
            $comment_content = "";

            if ($_GET['action'] == 'view') {
                $post_content = '
                <p class="post-content">
                    '.$data['content'].'
                </p>';

                if ($post_username == $username) {
                    $edit_button = '
                    <div class="post-icon post-icon-edit">
                        <a href="post.php?action=edit&id='.$_GET['id'].'">
                            <i class="fa-solid fa-pencil"></i>
                        </a>         
                    </div>';
                }
                else {
                    $edit_button = "";
                }

                if ($data['created_at'] == $data['last_edited_at']) {
                    $date = new DateTime($data['created_at']);
                    $date_content = 'Created: '.date_format($date, 'M j, Y \a\t H:i:s');
                }
                else {
                    $date = new DateTime($data['last_edited_at']);
                    $date_content = 'Edited: '.date_format($date, 'M j, Y \a\t H:i:s');
                }

                $footer_content = '
                <div class="post-icon-holder">
                    <div class="post-icon post-icon-like'.$like_class.'">
                        <div onclick=likePost('.$data['postID'].')>
                            <i class="fa-solid fa-heart"></i>
                        </div>
                        <p>'.$numlikes.'</p>
                    </div>
                    '.$edit_button.'
                </div>
                <div class="post-date">'.$date_content.'</div>
                ';

                $query = "SELECT * FROM comments WHERE postID = '".$data['postID']."'";

                $subresult = $conn->query($query);

                if (!$subresult) {
                    die($connection->error);
                }
                
                if ($subresult->num_rows > 0) {
                    while($row = mysqli_fetch_array($subresult)) {

                        $query = "SELECT username FROM comments WHERE commentid = '".$row['commentID']."'";

                        $comment_query_result = $conn->query($query);

                        if (!$comment_query_result) {
                            die($connection->error);
                        }

                        $comment_query_data = mysqli_fetch_array($comment_query_result);

                        if ($comment_query_data['username'] == $username) {
                            $comment_edit_button = '
                            <div class="comment-icon comment-icon-edit">
                                <a href="comment.php?action=edit&id='.$row['commentID'].'">
                                    <i class="fa-solid fa-pencil"></i>
                                </a>         
                            </div>';
                        }
                        else {
                            $comment_edit_button = "";
                        }

                        if ($row['created_at'] == $row['last_edited_at']) {
                            $date = new DateTime($row['created_at']);
                            $comment_date_content = 'Created: '.date_format($date, 'M j, Y \a\t H:i:s');
                        }
                        else {
                            $date = new DateTime($row['last_edited_at']);
                            $comment_date_content = 'Edited: '.date_format($date, 'M j, Y \a\t H:i:s');
                        }

                        $comment_content .= '
                        <div class="comment" id="c.'.$row['commentID'].'">
                            <a href="userpage.php?user='.$row['username'].'">
                                <h2>'.$row['username'].'</h2>
                            </a>
                            <p class="comment-content">
                                '.$row['content'].'
                            </p>
                            <div class="comment-footer">
                                <div class="comment-icon-holder">
                                    '.$comment_edit_button.'
                                </div>
                                <div class="post-date">'.$comment_date_content.'</div>  
                            </div>                
                        </div>
                        ';
                    }
                }       
            }
            else {

                // Reference: https://css-tricks.com/the-cleanest-trick-for-autogrowing-textareas/
                $post_content = '
                <div class="grow-wrap post-content">
                    <textarea id="content" name="content" onInput="this.parentNode.dataset.replicatedValue = this.value">'.$data['content'].'</textarea>
                </div>';
                $footer_content = '<button type="submit" id="submit_post" class="btn btn-outline-primary btn-small btn-block" onclick="editPost('.$_GET['id'].')">Save</button>';
            }

            $html_content = '
                <div class="post" id="p.'.$data['postID'].'">
                    <a href="userpage.php?user='.$data['user_id'].'">
                        <h2>'.$data['user_id'].'</h2>
                    </a>
                    '.$post_content.'
                    <div class="post-footer">
                        '.$footer_content.'   
                    </div>                
                </div>    
        '.$comment_content;
        echo $html_content; 
        }
        else if ($_GET['action'] == 'create') {
            $post_content = '
                <div class="grow-wrap post-content">
                    <textarea id="content" name="content" onInput="this.parentNode.dataset.replicatedValue = this.value"></textarea>
                </div>';
            $footer_content = '<button type="submit" id="submit_post" class="btn btn-outline-primary btn-small btn-block" onclick="createPost()">Save</button>';

            $html_content = '
                <div class="post">
                    <a href="userpage.php?user='.$username.'">
                        <h2>'.$username.'</h2>
                    </a>
                    '.$post_content.'
                    <div class="post-footer">
                        '.$footer_content.'   
                    </div>                
                </div>    
            ';
            echo $html_content; 
        }
        ?>
    </main>
</body>
</html>

<script>
    function likePost(postID) {
        $.post(
            "like_post.php",
            {
                postID: postID
            },
            function(result) {

                var json = JSON.parse(result);

                if (json.success == "true") {
                    var post = document.getElementById('p.' + postID);
                    var like_element = post.querySelector('.post-icon-like');
                    if (json.action == "liked") {
                        like_element.classList.add("is-liked");
                    }
                    else {
                        like_element.classList.remove("is-liked");
                    }
                    like_element.querySelector('p').innerHTML = json.numlikes;
                }
            }
        );
    }

    function editPost(postID) {
        var content = document.getElementById('content').value;

        $.post(
            "edit_post.php",
            {
                postID: postID,
                content: content
            },
            function(result) {

                var json = JSON.parse(result);

                if (json.success == "true") {
                    document.location = json.location;
                }
            }
        );
    }

    function createPost() {
        var content = document.getElementById('content').value;

        $.post(
            "create_post.php",
            {
                content: content
            },
            function(result) {

                var json = JSON.parse(result);

                if (json.success == "true") {
                    document.location = json.location;
                }
            }
        );
    }
</script>
<?php

require_once("verify_user.php");
require_once('sql_queries.php');

$username = $_SESSION["username"];

//ensure that get requests are valid
//no get request should default to create action
//no post id should default to create action
//no action should default to view action
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

if ($_GET['action'] != 'create') {
    
    if (!postExists($_GET['id'])) {
        header('Location: home.php');
    }
}

if ($_GET['action'] == 'edit') {
    
    if (!postBelongsToUser($_GET['id'], $username)) {
        header('Location: post.php?action=view&id='.$_GET['id']);
    }
}

else if ($_GET['action'] == 'view') {
    $data = getPostDataById($_GET['id'], $username);
    $post_username = $data['user_id'];

    if (!areFriends($username, $post_username) && $post_username != $username) {
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
    //setup editable textbox
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
                <a href="post.php?action=create" class="nav-link">
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
                
                <input type="text" placeholder="Search">
                <a href="search.php" id="search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <span class="link-text">Search</span>
                </a>
        </nav>
    </div>
    <main>
        <?php
        //decide function by get request
        if ($_GET['action'] == 'view' || $_GET['action'] == 'edit') {
            
            $post_data = getPostDataById($_GET['id'], $_SESSION['username']);

            if ($post_data['is_liked']) {
                $like_class = " is-liked";
            }
            else {
                $like_class = "";
            }

            $comment_content = "";

            //load content for viewing
            if ($_GET['action'] == 'view') {
                $post_content = '
                <p class="post-content">
                    '.$post_data['content'].'
                </p>';

                //users can edit their own post
                if ($post_username == $username) {
                    $edit_button = '
                    <div class="post-icon post-icon-edit">
                        <a href="post.php?action=edit&id='.$_GET['id'].'">
                            <i class="fa-solid fa-pencil"></i>
                        </a>         
                    </div>';
                }
                //users cannot edit others' posts
                else {
                    $edit_button = "";
                }

                if ($post_data['created_at'] == $post_data['last_edited_at']) {
                    $date = new DateTime($post_data['created_at']);
                    $date_content = 'Created: '.date_format($date, 'M j, Y \a\t H:i:s');
                }
                //post has been edited
                else {
                    $date = new DateTime($post_data['last_edited_at']);
                    $date_content = 'Edited: '.date_format($date, 'M j, Y \a\t H:i:s');
                }

                //assemble post footer
                $footer_content = '
                <div class="post-icon-holder">
                    <div class="post-icon post-icon-like'.$like_class.'">
                        <div onclick=likePost('.$post_data['postID'].')>
                            <i class="fa-solid fa-heart"></i>
                        </div>
                        <p>'.$post_data['num_likes'].'</p>
                    </div>
                    <div class="post-icon post-icon-comment">
                        <div onclick="scrollToAddComment()">
                            <i class="fa-solid fa-comment"></i>
                        </div>
                    </div>
                    '.$edit_button.'
                </div>
                <div class="post-date">'.$date_content.'</div>
                ';

                $comment_data = getAllCommentDataByPostId($_GET['id']);

                foreach($comment_data as $row) {

                    if ($row['username'] == $username) {
                        $comment_edit_button = '
                        <div class="comment-icon comment-icon-edit">
                            <div onclick="makeCommentEditable(this)">
                                <i class="fa-solid fa-pencil"></i>
                            </div>         
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
                            <div class="comment-date">'.$comment_date_content.'</div>  
                        </div>                
                    </div>
                    ';
                }
                //add textbox for creating a new comment
                $comment_content .= '
                <div class="comment" id="c.new">
                    <a href="userpage.php?user='.$username.'">
                        <h2>'.$username.'</h2>
                    </a>
                    <div class="grow-wrap comment-content">
                        <textarea id="content" name="content" onInput="this.parentNode.dataset.replicatedValue = this.value"></textarea>
                    </div>
                    <div class="comment-footer">
                        <button type="button" id="create_comment" class="btn btn-outline-primary btn-small btn-block" onclick="addComment()">Comment</button>
                    </div>                
                </div>';
            }
            //edit post
            else {
                // Reference: https://css-tricks.com/the-cleanest-trick-for-autogrowing-textareas/
                $post_content = '
                <div class="grow-wrap post-content">
                    <textarea id="content" name="content" onInput="this.parentNode.dataset.replicatedValue = this.value">'.$post_data['content'].'</textarea>
                </div>';
                $footer_content = '<button type="submit" id="submit_post" class="btn btn-outline-primary btn-small btn-block" onclick="editPost('.$_GET['id'].')">Save</button>';
            }

            //assemble html
            $html_content = '
                <div class="post" id="p.'.$post_data['postID'].'">
                    <a href="userpage.php?user='.$post_data['user_id'].'">
                        <h2>'.$post_data['user_id'].'</h2>
                    </a>
                    '.$post_content.'
                    <div class="post-footer">
                        '.$footer_content.'   
                    </div>                
                </div>    
        '.$comment_content;
        echo $html_content; 
        }
        
        //create post
        else if ($_GET['action'] == 'create') {
            $post_content = '
                <div class="grow-wrap post-content">
                    <textarea name="comment_content" onInput="this.parentNode.dataset.replicatedValue = this.value"></textarea>
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

<script>
    //edit comment
    function editComment(eventElement) {
        //store input and relevant data
        var comment = eventElement.parentElement.parentElement;
        var commentID = comment.id.substring(2);
        var content = comment.querySelector('textarea').value;

        $.post(
            "edit_comment.php",
            {
                commentID: commentID,
                content: content
            },
            function(result) {

                var json = JSON.parse(result);

                if (json.success == "true") {
                    updateCommentBox(json.user, json.content, json.commentID, json.last_edited_at, 'edit');
                }
            }
        );
    }
    //transforms display comment to editable comment
    function makeCommentEditable(eventElement) {
        //save comment id
        var commentID = eventElement.parentElement.parentElement.parentElement.parentElement.id;

        //reference to comment element
        var comment = document.getElementById(commentID);
        var content = comment.querySelector('.comment-content').childNodes[0].nodeValue.trim();

        //remove static content
        comment.querySelector('.comment-content').remove();

        //add editable textarea
        var new_textbox_textarea = document.createElement('textarea');
        new_textbox_textarea.name = 'comment_content';
        new_textbox_textarea.oninput = function() {this.parentNode.dataset.replicatedValue = this.value};
        var new_content_node = document.createTextNode(content);
        new_textbox_textarea.appendChild(new_content_node);

        //add container for textarea that grows with line breaks
        var new_textbox_textarea_container = document.createElement('div');
        new_textbox_textarea_container.classList.add('grow-wrap', 'comment-content');

        new_textbox_textarea_container.appendChild(new_textbox_textarea);

        //add new footer
        var comment_footer = comment.querySelector('.comment-footer');
        comment.insertBefore(new_textbox_textarea_container, comment_footer);

        comment_footer.querySelector('.comment-icon-holder').remove();

        //add button to save changes
        var new_textbox_button = document.createElement('button');
        new_textbox_button.type = 'button';
        new_textbox_button.id = 'edit_comment';
        new_textbox_button.classList.add('btn', 'btn-outline-primary', 'btn-small', 'btn-block');
        new_textbox_button.onclick = function() {editComment(this)};

        var new_textbox_button_text = document.createTextNode('Save');
        new_textbox_button.appendChild(new_textbox_button_text);

        //add date
        var comment_date = comment_footer.querySelector('comment-date');
        comment_footer.prepend(new_textbox_button);
    }
    function addComment() {
        //add comments
        var postID = document.getElementsByClassName('post')[0].id.substring(2);
        var content = document.getElementById('c.new').querySelector('textarea').value;

        $.post(
            "php/controller.php",
            {
                function: "addComment",
                postID: postID,
                content: content
            },
            function(result) {

                var json = JSON.parse(result);

                if (json.success == "true") {
                    updateCommentBox(json.user, json.content, json.commentID, json.created_at, 'add');
                    addNewCommentBox(json.user);
                }
                else {
                    alert(json);
                }
            }
        );
    }
    // add comment onto part of screen
    function updateCommentBox(user, content, commentID, created_at, action) {

        //reference to new textbox
        if (action == 'add') {
            var comment_box = document.getElementById('c.new'); 
        }
        //reference to existing textbox
        else {
            var comment_box = document.getElementById('c.' + commentID);
        }

        //update comment id if necessary
        comment_box.id = 'c.' + commentID;

        //reference to footer
        var comment_footer = comment_box.querySelector('.comment-footer');

        //remove existing content
        comment_box.querySelector('.comment-content').remove();

        //create content holder
        var new_p = document.createElement('p');
        new_p.classList.add('comment-content');
        var new_content = document.createTextNode(content);
        new_p.appendChild(new_content);
        comment_box.insertBefore(new_p, comment_footer);

        //remove button and dates from footer
        while (comment_footer.firstChild) {
            comment_footer.removeChild(comment_footer.firstChild);
        }

        //add icons to footer
        var icon_holder = document.createElement('div');
        icon_holder.classList.add('comment-icon-holder');

        var edit_icon_container = document.createElement('div');
        edit_icon_container.classList.add('comment-icon', 'comment-icon-edit');

        var edit_icon_clickable = document.createElement('div');
        edit_icon_clickable.onclick = function() {makeCommentEditable(this)};

        var edit_icon_display = document.createElement('i');
        edit_icon_display.classList.add('fa-solid', 'fa-pencil');

        edit_icon_clickable.appendChild(edit_icon_display);
        edit_icon_container.appendChild(edit_icon_clickable);
        icon_holder.appendChild(edit_icon_container);
        comment_footer.appendChild(icon_holder);

        //add date to footer
        var date_holder = document.createElement('div');
        date_holder.classList.add('comment-date');

        //comment is not edited
        if (action == 'add') {
            var date_text = document.createTextNode('Created: ' + created_at);
        }

        //comment is edited
        else {
            var date_text = document.createTextNode('Edited: ' + created_at);
        }

        date_holder.appendChild(date_text);
        comment_footer.appendChild(date_holder);
    }
    function addNewCommentBox(user) {
        //create new textbox header
        var new_textbox_header = document.createElement('h2');
        var new_textbox_header_text = document.createTextNode(user);
        new_textbox_header.appendChild(new_textbox_header_text);

        var new_textbox_header_link = document.createElement('a');
        new_textbox_header_link.href = 'userpage.php?user=' + user;
        new_textbox_header_link.appendChild(new_textbox_header);

        //create editable textbox
        var new_textbox = document.createElement('div');
        new_textbox.classList.add('comment');
        new_textbox.id = 'c.new';
        new_textbox.appendChild(new_textbox_header_link);

        //add growable textarea
        var new_textbox_textarea = document.createElement('textarea');
        new_textbox_textarea.name = 'comment_content';
        new_textbox_textarea.oninput = function() {this.parentNode.dataset.replicatedValue = this.value};

        var new_textbox_textarea_container = document.createElement('div');
        new_textbox_textarea_container.classList.add('grow-wrap', 'comment-content');

        new_textbox_textarea_container.appendChild(new_textbox_textarea);

        new_textbox.appendChild(new_textbox_textarea_container);

        //add submit button
        var new_textbox_button = document.createElement('button');
        new_textbox_button.type = 'button';
        new_textbox_button.id = 'create_comment';
        new_textbox_button.classList.add('btn', 'btn-outline-primary', 'btn-small', 'btn-block');
        new_textbox_button.onclick = function() {addComment()};

        var new_textbox_button_text = document.createTextNode('Comment');
        new_textbox_button.appendChild(new_textbox_button_text);

        var new_textbox_footer = document.createElement('div');
        new_textbox_footer.classList.add('comment-footer');
        new_textbox_footer.appendChild(new_textbox_button);

        new_textbox.appendChild(new_textbox_footer);
        document.querySelector('main').appendChild(new_textbox);
    }
    //similar to other pages likepost function. updating db and making button change color
    function likePost(postID) {
        $.post(
            "like_post.php",
            {
                postID: postID
            },
            function(result) {
                //alert(result);
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
    //edit button sends post to the controller
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
    //send post to controller which sends to db
    function createPost() {
        var content = document.getElementsByClassName('post')[0].querySelector('textarea').value;

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
    function scrollToAddComment() {
        document.getElementById("c.new").scrollIntoView();
    }


</script>
</body>
</html>
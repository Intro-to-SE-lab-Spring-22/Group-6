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
    <?php include ("navbar.php"); ?>
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
            "add_comment.php",
            {
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
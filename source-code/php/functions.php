<?php
require_once('sql_queries.php');

function editPost($postID, $content, $username) {
    if (!accessDB_PostExists($postID)) {
        return array("success" => "false", "message" => "post_does_not_exist");
    }
    $post_data = accessDB_PostById($postID);
    if (!($post_data['user_id'] == $username)) {
        return array("success" => "false", "message" => "access_error");
    }
    $success = accessDB_UpdatePost($postID, $content);
    return array("success" => "true", "data" => [$postID]);
}

//add comment

//create account

//create post

//edit comment

//edit post

//friend request

//getpost

//home page load posts

//like_post

//login

//logout

//post (all of the actions and functions within regarding the get variables)

//search

//userpage (loading posts)
//verify_user

//
?>
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
function addComments($content, $postID, $user)
{
   

    if (!postExists($postID)) {
        echo json_encode(array("success" => "false"));
    }
    else {
        $data = getPostDataById($postID, $user);
        $post_user = $data['user_id'];

        $are_friends = areFriends($user, $post_user);

        if (!$are_friends && $post_user != $user) {
            return array("success" => "false");
        }
        else {
            $new_commentID = insertNewComment($postID, $user, $content);

            $data = getCommentDataById($new_commentID);

            //get timestamp of comment
            $date = new DateTime($data['created_at']);
            $created_at = date_format($date, 'M j, Y \a\t H:i:s');
            
            //send comment data back to page
            return array("success" => "true", "user" => "$user", "content" => "$content", "commentID" => "$new_commentID", "created_at" => "$created_at");
        }
    }
    
}
//create account
function createAccount($firstname, $lastname, $email, $username, $password)
{
    $correctOP = false;
    if(accessDB_CheckValidUsername($username, $email))
    {
        $fullname = $firstname . " " . $lastname;
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        if((preg_match("/^[a-zA-Z ]*$/", $firstname)) && (preg_match("/^[a-zA-Z ]*$/", $lastname)) &&
             (filter_var($email, FILTER_VALIDATE_EMAIL)))
        {
            $correctOP = accessDB_CreateNewUser($firstname, $lastname, $fullname, $email, $username, $password_hash);
            return array("success"=> "true");
        }
        
    } 
    return array("success"=>"false");

}
//create post

//edit comment

//edit post

//friend request

//getpost

//home page load posts

//like_post

//login
function login($username, $password)
{
    $user_data = getUserDataById($username);

    if ($user_data) {
        if (password_verify($password, $user_data['password'])) {           
            $_SESSION['username'] = $username;
            return array("success" => "true", "location" => "home.php");
        }
        else {
            return array("success" => "false");
        }
    }
    else {
        return array("success" => "false");
    }
}
//logout

//post (all of the actions and functions within regarding the get variables)

//search
function search($searchq)
{
    $searchq = preg_replace("#[^0-9a-z]#i","", $searchq);
            
    $data = searchDatabase($searchq);
    
    $response = '';
    if (count($data) == 0) {
        $response = "THERE ARE NO RESULTS";
    }
    else{
        $response = array();
        foreach ($data as $row) {
            array_push($response, $row);
        }
    }
    return ($response);
}
//userpage (loading posts)
//verify_user

//
?>
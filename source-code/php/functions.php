<?php
require_once('sql_queries.php');

// function editPost($postID, $content, $username) {
//     if (!accessDB_PostExists($postID)) {
//         return array("success" => "false", "message" => "post_does_not_exist");
//     }
//     $post_data = accessDB_PostById($postID);
//     if (!($post_data['user_id'] == $username)) {
//         return array("success" => "false", "message" => "access_error");
//     }
//     $success = accessDB_UpdatePost($postID, $content);
//     return array("success" => "true", "data" => [$postID]);
// }

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
function editPost($postID, $content, $sessionUser, $has_image, $img, $tmp, $errorimg) {

    if (!accessDB_PostExists($postID)) {
        return array("success" => "false", "message" => "post_does_not_exist");
    }

    if (!accessDB_PostBelongsToUser($postID, $sessionUser)) {
        return array("success" => "false", "message" => "access_error");
    }

    updatePost($postID, $content);

    if ($has_image) {
        $path = "../../images/post/";
        if (!is_dir($path.$postID)) {
            mkdir($path.$postID);
        }
        $path = $path.$postID."/";

        array_map('unlink', glob($path.$postID.".*"));

        $imageFileType = strtolower(pathinfo($img,PATHINFO_EXTENSION));

        $path = $path.$postID.".".$imageFileType;
    
        if (!move_uploaded_file($tmp, $path)) {
            return array("success" => "false");
        }
    }

    $post_data = accessDB_PostById($postID);
    $post_data['num_likes'] = accessDB_GetNumLikesById($postID);
    $post_data['is_liked'] = accessDB_PostIsLikedByUser($postID, $sessionUser);
    if ($post_data['user_id'] == $sessionUser) {
        $post_data['is_editable'] = true;
    }
    else {
        $post_data['is_editable'] = false;
    }

    $path = "../../images/post/$postID/$postID.*";

    //die($path);

    $result = glob($path);
    if (!empty($result)) {
        $post_data['has_image'] = true;
        $post_data['image_filename'] = pathinfo($result[0])['basename'];
    }
    else {
        $post_data['has_image'] = false;
        $post_data['image_filename'] = "";
    }

    //send data back
    return array("success" => "true", "data" => $post_data);
}

//friend request

//get a post
function getOnePost($postID, $sessionUsername) {
    if (!accessDB_PostExists($postID)) {
        return array("success" => "false", "message" => "post_does_not_exist");
    }
    
    $post_data = accessDB_PostById($postID);

    if (!accessDB_PostBelongsToUser($postID, $sessionUsername) && !accessDB_AreFriends($post_data['user_id'], $sessionUsername)) {
        return array("success" => "false", "message" => "access_error");
    }

    $post_data['num_likes'] = accessDB_GetNumLikesById($postID);
    $post_data['is_liked'] = accessDB_PostIsLikedByUser($postID, $sessionUsername);
    $post_data['num_comments'] = accessDB_GetNumCommentsById($postID);
    if ($post_data['user_id'] == $sessionUsername) {
        $post_data['is_editable'] = true;
    }
    else {
        $post_data['is_editable'] = false;
    }

    $path = "../../images/post/$postID/$postID.*";

    //die($path);

    $result = glob($path);
    if (!empty($result)) {
        $post_data['has_image'] = true;
        $post_data['image_filename'] = pathinfo($result[0])['basename'];
    }
    else {
        $post_data['has_image'] = false;
        $post_data['image_filename'] = "";
    }

    return array("success" => "true", "data" => $post_data, "message" => $path);
}

//get many posts

//home page load posts

//like_post
function likePost($postID, $sessionUsername) {
    if (!accessDB_PostExists($postID)) {
        return array("success" => "false", "message" => "post_does_not_exist");
    }

    if (accessDB_PostIsLikedByUser($postID, $sessionUsername)) {
        accessDB_RemoveLikeFromPost($postID, $sessionUsername);

        $num_likes = getNumLikesById($postID);

        exit(json_encode(array("success" => "true", "action" => "unliked", "num_likes" => $num_likes)));
    }
    else {
        $post_data = accessDB_PostById($postID);

        if (!accessDB_PostBelongsToUser($postID, $sessionUsername) && !accessDB_AreFriends($post_data['user_id'], $sessionUsername)) {
            return array("success" => "false", "message" => "access_error");
        }

        accessDB_AddLikeToPost($postID, $sessionUsername);

        $num_likes = getNumLikesById($postID);

        exit(json_encode(array("success" => "true", "action" => "liked", "num_likes" => $num_likes)));
    }

}

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

//get all post comments
function getAllPostComments($postID, $sessionUsername) {
    if (!accessDB_PostExists($postID)) {
        return array("success" => "false", "message" => "post_does_not_exist");
    }

    $post_data = accessDB_PostById($postID);

    if (!accessDB_PostBelongsToUser($postID, $sessionUsername) && !accessDB_AreFriends($post_data['user_id'], $sessionUsername)) {
        return array("success" => "false", "message" => "access_error");
    }

    $comments = accessDB_AllCommentDataByPostId($postID);

    foreach ($comments as &$comment) {
        if ($comment['username'] == $sessionUsername) {
            $comment['is_editable'] = true;
        }
        else {
            $comment['is_editable'] = false;
        }
    }

    return array("success" => "true", "data" => $comments);
}

function uploadFile($img, $tmp, $imgerror, $filename) {
    $path = "../../images/profile/";
    array_map('unlink', glob($path.$filename.".*"));

    $imageFileType = strtolower(pathinfo($img,PATHINFO_EXTENSION));

    $path = $path.$filename.".".$imageFileType;

    if (move_uploaded_file($tmp, $path)) {
        return array("success" => "true");
    }
    else {
        return array("success" => "false", "message" => $tmp);
    }
}

function uploadPostFile($img, $tmp, $imgerror, $sessionUser, $postID) {
    if (!accessDB_PostExists($postID)) {
        return array("success" => "false", "message" => "post_does_not_exist");
    }
    if (!accessDB_PostBelongsToUser($postID, $sessionUser)) {
        return array("success" => "false", "message" => "access_error");
    }

    $path = "../../images/post/";
    if (!is_dir($path.$postID)) {
        mkdir($path.$postID);
    }
    $path = $path."/".$postID;

    array_map('unlink', glob($path.$postID.".*"));

    return array("success" => "true", "message" => "test");

    // $imageFileType = strtolower(pathinfo($img,PATHINFO_EXTENSION));

    // $path = $path.$filename.".".$imageFileType;

    // if (move_uploaded_file($tmp, $path)) {
    //     return array("success" => "true");
    // }
    // else {
    //     return array("success" => "false", "message" => $tmp);
    // }
}

function getUserProfilePicture($username) {
    $path = "../../images/profile/$username.*";

    //die($path);

    $result = glob($path);
    if (!empty($result)) {
        $img_src = pathinfo($result[0])['basename'];
    }
    else {
        $img_src = "default.png";
    }

    return array("success" => "true", "filename" => $img_src);
}
?>
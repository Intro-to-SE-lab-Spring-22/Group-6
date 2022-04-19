<?php

require_once('credentials.php');

//setup database connection
$dbh = new PDO("mysql:host=$hn;dbname=$db", $un, $pw, array(PDO::ATTR_PERSISTENT => TRUE));
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//SQL Error Handling
function handleSQLError($query, $error_message) {
    echo '<pre>';
    echo $query;
    echo '</pre>';
    echo $error_message;
    die;
}

//Execute SQL Statements
function runSQLStatement($query, $parameters) {
    global $dbh;
    //Error handling
    try {
        $stmt = $dbh->prepare($query);
        $stmt->execute($parameters);
    }
    catch (PDOException $e) {
        handleSQLError($query, $e->getMessage());
    }

    return $stmt;
}

//add new post to database
function accessDB_InsertNewPost($user, $content) {
    $query = "INSERT INTO post (user_id, content) VALUES (?, ?)";
    $parameters = [$user, $content];

    $stmt = runSQLStatement($query, $parameters);

    //get id of new post
    $query = "SELECT LAST_INSERT_ID() as postID";
    $parameters = [];

    $stmt = runSQLStatement($query, $parameters);

    $data = $stmt->fetch();
    $new_postID = $data['postID'];

    return $new_postID;
}

//edit existing post in database
function accessDB_UpdatePost($postID, $content) {
    $query = "UPDATE post SET content = ? WHERE postID = ?";
    $parameters = [$content, $postID];

    $stmt = runSQLStatement($query, $parameters);
    
    return true;
}

//Get post info by post id
function accessDB_PostById($postID) {
    $query = "SELECT * FROM post WHERE postID = ?";
    $parameters = [$postID];

    $stmt = runSQLStatement($query, $parameters);  

    $data = $stmt->fetch(); 
    return $data;
}

//Get number of likes by post id
function accessDB_GetNumLikesById($postID): int{
    $query = "SELECT COUNT(*) as num_likes FROM likes WHERE postID = ?";
    $parameters = [$postID];

    $stmt = runSQLStatement($query, $parameters);

    $data = $stmt->fetch();
    return intval($data['num_likes']);
}

//get number of comments by post id
function accessDB_GetNumCommentsById($postID) {
    $query = "SELECT COUNT(*) as num_comments FROM comments WHERE postID = ?";
    $parameters = [$postID];

    $stmt = runSQLStatement($query, $parameters);

    $data = $stmt->fetch();
    return intval($data['num_comments']);
}

//Check if post is liked by user
function accessDB_PostIsLikedByUser($postID, $user) {
    $query = "SELECT COUNT(*) as already_liked FROM likes WHERE postID = ? AND username = ?";
    $parameters = [$postID, $user];

    $stmt = runSQLStatement($query, $parameters);

    $data = $stmt->fetch();
    $already_liked = intval($data['already_liked']);

    //post is liked by user
    if ($already_liked > 0) {
        return true;
    }
    //post is not liked by user
    else {
        return false;
    }
}

//remove a like from a post
function accessDB_RemoveLikeFromPost($postID, $user) {
    $query = "DELETE FROM likes WHERE postID = ? AND username = ?";
    $parameters = [$postID, $user];

    $stmt = runSQLStatement($query, $parameters);
    
    return true;
}

//add a like to a post
function accessDB_AddLikeToPost($postID, $user) {
    $query = "INSERT INTO likes VALUES (?, ?)";
    $parameters = [$postID, $user];

    $stmt = runSQLStatement($query, $parameters);
    
    return true;
}

//check if post exists
function accessDB_PostExists($postID) {
    $query = "SELECT COUNT(*) as post_exists FROM post WHERE postID = ?";
    $parameters = [$postID];

    $stmt = runSQLStatement($query, $parameters);

    $data = $stmt->fetch();
    $post_exists = intval($data['post_exists']);

    //post does not exist
    if ($post_exists == 0) {
        return false;
    }
    //post exists
    else {
        return true;
    }
}

//get data for all comments associated with post
function accessDB_AllCommentDataByPostId($postID) {
    $query = "SELECT * FROM comments WHERE postID = ?";
    $parameters = [$postID];

    $stmt = runSQLStatement($query, $parameters);

    $data = $stmt->fetchAll();

    return $data;
}

//check if post belongs to user
function accessDB_PostBelongsToUser($postID, $user) {
    $query = "SELECT user_id FROM post WHERE postID = ?";
    $parameters = [$postID];

    $stmt = runSQLStatement($query, $parameters);

    $data = $stmt->fetch();
    
    //post belongs to user
    if ($data['user_id'] == $user) {
        return true;
    }
    //post does not belong to user
    else {
        return false;
    }
}

//check if users are friends
function accessDB_AreFriends($user1, $user2) {
    $query = "SELECT COUNT(*) AS are_friends FROM friends WHERE id_sender = ? AND id_receiver = ?";
    $parameters = [$user1, $user2];

    $stmt = runSQLStatement($query, $parameters);

    $data = $stmt->fetch();
    $are_friends = intval($data['are_friends']);

    //users are friends
    if ($are_friends > 0) {
        return true;
    }
    //users are not friends
    else {
        return false;
    }
}

//remove friend status between users
function accessDB_RemoveFriends($user1, $user2) {
    //remove user1->user2
    $query = "DELETE FROM friends WHERE id_sender = ? AND id_receiver = ?";
    $parameters = [$user1, $user2];

    $stmt = runSQLStatement($query, $parameters);

    //remove user2->user1
    $query = "DELETE FROM friends WHERE id_sender = ? AND id_receiver = ?";
    $parameters = [$user2, $user1];

    $stmt = runSQLStatement($query, $parameters);

    return true;
}

//add friend status between users
function accessDB_AddFriends($user1, $user2) {
    //add user1->user2
    $query = "INSERT INTO friends (id_sender, id_receiver) VALUES (?, ?)";
    $parameters = [$user1, $user2];

    $stmt = runSQLStatement($query, $parameters);

    //add user2->user1
    $query = "INSERT INTO friends (id_sender, id_receiver) VALUES (?, ?)";
    $parameters = [$user2, $user1];

    $stmt = runSQLStatement($query, $parameters);

    return true;
}

//check if a friends request is pending from one user to another
function accessDB_FriendRequestPending($user_from, $user_to) {
    $query = "SELECT COUNT(*) as pending_friends FROM pendingfriends WHERE from_id = ? AND to_id = ?";
    $parameters = [$user_from, $user_to];

    $stmt = runSQLStatement($query, $parameters);

    $data = $stmt->fetch();
    $pending_friends = intval($data['pending_friends']);

    //friend request is pending
    if ($pending_friends > 0) {
        return true;
    }
    //friend request is not pending
    else {
        return false;
    }
}

//add friend request from one user to another
function accessDB_AddFriendRequest($user_from, $user_to) {
    $query = "INSERT INTO pendingfriends VALUES (?, ?)";
    $parameters = [$user_from, $user_to];

    $stmt = runSQLStatement($query, $parameters);

    return true;
}

//remove friend request from one user to another
function accessDB_RemoveFriendRequest($user_from, $user_to) {
    $query = "DELETE FROM pendingfriends WHERE from_id = ? AND to_id = ?";
    $parameters = [$user_from, $user_to];

    $stmt = runSQLStatement($query, $parameters);

    return true;
}

//get user data by user id
function accessDB_GetUserDataById($user) {
    $query = "SELECT * FROM users WHERE id = ?";
    $parameters = [$user];

    $stmt = runSQLStatement($query, $parameters);  

    $data = $stmt->fetch();

    return $data;
}

//add new comment to the database
function accessDB_InsertNewComment($postID, $user, $content) {
    $query = "INSERT INTO comments (postID, username, content) VALUES (?, ?, ?)";
    $parameters = [$postID, $user, $content];

    $stmt = runSQLStatement($query, $parameters);

    $query = "SELECT LAST_INSERT_ID() as commentID";
    $parameters = [];

    $stmt = runSQLStatement($query, $parameters);

    $data = $stmt->fetch();
    $new_commentID = $data['commentID'];

    return $new_commentID;
}

//get comment data
function accessDB_GetCommentDataById($commentID) {
    $query = "SELECT * FROM comments WHERE commentID = ?";
    $parameters = [$commentID];

    $stmt = runSQLStatement($query, $parameters);  

    $data = $stmt->fetch(); 
    return $data;
}

//check if a comment exists
function accessDB_CommentExists($commentID) {
    $query = "SELECT COUNT(*) as comment_exists FROM comments WHERE commentID = ?";
    $parameters = [$commentID];

    $stmt = runSQLStatement($query, $parameters);

    $data = $stmt->fetch();
    $comment_exists = intval($data['comment_exists']);

    //comment does not exist
    if ($comment_exists == 0) {
        return false;
    }
    //comment exists
    else {
        return true;
    }
}

//edit an existing comment
function accessDB_UpdateComment($commentID, $content) {
    $query = "UPDATE comments SET content = ? WHERE commentID = ?";
    $parameters = [$content, $commentID];

    $stmt = runSQLStatement($query, $parameters);
    
    return true;
}

//search database for user
function accessDB_SearchDatabase($search_term) {
    $query = "SELECT * FROM users WHERE firstName LIKE ? OR lastName LIKE ? or id LIKE ?";
    $parameters = ["%$search_term%", "%$search_term%", "%$search_term%"];

    $stmt = runSQLStatement($query, $parameters);

    $data = $stmt->fetchAll();

    return $data;
}

//get friend list of a user
function accessDB_GetUserFriends($user) {
    $query = "SELECT id_receiver FROM friends WHERE id_sender = ?";
    $parameters = [$user];

    $stmt = runSQLStatement($query, $parameters);

    $data = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

    return $data;
}

//get list of posts by list of users
function accessDB_GetPostsByUserList($user_list, $start, $limit) {
    $start = intval($start);
    $limit = intval($limit);
    $parameter_template_string = str_repeat("?,", count($user_list) - 1) . "?";
    $query = "SELECT * FROM post WHERE user_id IN ($parameter_template_string) ORDER BY created_at DESC LIMIT $start, $limit";
    $parameters = $user_list;
    
    $stmt = runSQLStatement($query, $parameters);

    $data = $stmt->fetchAll();

    return $data;
}

//get all posts by a certain user
function accessDB_GetPostsByUser($user, $start, $limit) {
    $start = intval($start);
    $limit = intval($limit);
    $query = "SELECT * FROM post WHERE user_id = ? ORDER BY created_at DESC LIMIT $start, $limit";
    $parameters = [$user];
    
    $stmt = runSQLStatement($query, $parameters);

    $data = $stmt->fetchAll();

    return $data;
}

//wrapper function for creating a post
function insertNewPost($user, $content): int {
    $new_postID = accessDB_InsertNewPost($user, $content);

    return $new_postID;
}

//wrapper function for editing a post
function updatePost($postID, $content) {
    $success = accessDB_UpdatePost($postID, $content);
}

//wrapper function for getting post data by post id
function getPostDataById($postID, $user) {
    $post_data = accessDB_PostById($postID);
    $post_data['num_likes'] = accessDB_GetNumLikesById($postID);
    //$post_data['num_comments'] = accessDB_GetNumCommentsById($postID);
    $post_data['is_liked'] = accessDB_PostIsLikedByUser($postID, $user);

    return $post_data;
}

//wrapper function for getting all posts by a user
function getPostsByUser($user, $start, $limit) {
    $post_data = accessDB_GetPostsByUser($user, $start, $limit);

    return $post_data;
}

//wrapper function for checking if a post is liked by a user
function postIsLikedByUser($postID, $user): bool {
    $post_is_liked_by_user = accessDB_PostIsLikedByUser($postID, $user);

    return $post_is_liked_by_user;
}

//wrapper function for removing a like by a user
function removeLikeFromPost($postID, $user) {
    $success = accessDB_RemoveLikeFromPost($postID, $user);
}

//wrapper function for adding a like by a user
function addLikeToPost($postID, $user) {
    $success = accessDB_AddLikeToPost($postID, $user);
}

//wrapper function for getting number of likes on a post
function getNumLikesById($postID): int {
    $num_likes = accessDB_GetNumLikesById($postID);

    return $num_likes;
}

//wrapper function for getting number of comments on a post
function getNumCommentsById($postID): int {
    $num_comments = accessDB_GetNumCommentsById($postID);

    return $num_comments;
}

//wrapper function for getting comment data associated with a post
function getAllCommentDataByPostId($postID) {
    $comment_data = accessDB_AllCommentDataByPostId($postID);

    return $comment_data;
}

//wrapper function for checking if a post exists
function postExists($postID): bool {
    $post_exists = accessDB_PostExists($postID);
    return $post_exists;
}

//wrapper function for checking if a post belongs to a user
function postBelongsToUser($postID, $user): bool {
    $post_belongs_to_user = accessDB_PostBelongsToUser($postID, $user);

    return $post_belongs_to_user;
}

//wrapper function for checking if two users are friends
function areFriends($user1, $user2): bool {
    $are_friends = accessDB_AreFriends($user1, $user2);

    return $are_friends;
}

//wrapper function for removing friend status between two users
function removeFriends($user1, $user2) {
    $success = accessDB_RemoveFriends($user1, $user2);
}

//wrapper function for adding friend status between two users
function addFriends($user1, $user2) {
    $success = accessDB_AddFriends($user1, $user2);
}

//wrapper function for checking if a friend request is pending
function friendRequestPending($user_from, $user_to): bool {
    $friend_request_pending = accessDB_FriendRequestPending($user_from, $user_to);

    return $friend_request_pending;
}

//wrapper function for adding a pending friend request
function addFriendRequest($user_from, $user_to) {
    $success = accessDB_AddFriendRequest($user_from, $user_to);
}

//wrapper function for removing a pending friend request
function removeFriendRequest($user_from, $user_to) {
    $success = accessDB_RemoveFriendRequest($user_from, $user_to);
}

//wrapper function for getting user data
function getUserDataById($user) {
    $user_data = accessDB_GetUserDataById($user);

    return $user_data;
}

//wrapper function for adding a new comment
function insertNewComment($postID, $user, $content): int {
    $new_commentID = accessDB_InsertNewComment($postID, $user, $content);

    return $new_commentID;
}

//wrapper function for getting comment data
function getCommentDataById($commentID) {
    $comment_data = accessDB_GetCommentDataById($commentID);
    
    return $comment_data;
}

//wrapper function for checking if a comment exists
function commentExists($commentID): bool {
    $comment_exists = accessDB_CommentExists($commentID);

    return $comment_exists;
}

//wrapper function for editing a comment
function updateComment($commentID, $content) {
    $success = accessDB_UpdateComment($commentID, $content);
}

//wrapper function for searching the database
function searchDatabase($search_term) {
    $search_data = accessDB_SearchDatabase($search_term);

    return $search_data;
}

//wrapper function for getting a list of a user's friends
function getUserFriends($user) {
    $user_friends = accessDB_GetUserFriends($user);

    return $user_friends;
}

//wrapper function for getting posts by a list of users
function getPostsByUserList($user_list, $start, $limit) {
    $post_data = accessDB_GetPostsByUserList($user_list, $start, $limit);

    return $post_data;
}

?>
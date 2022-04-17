<?php

session_start();
require_once('sql_queries.php');

if(ISSET($_POST['userPost'])) {
    if($_POST['userPost'] == 0){

        $start = $_POST['start'];
        $limit = $_POST['limit'];
        
        $username = $_SESSION['username'];

        $friendList = getUserFriends($username);

        array_push($friendList, $username);

        $post_data = getPostsByUserList($friendList, $start, $limit);

        $response = "";

        foreach ($post_data as $data) {
            $num_likes = getNumLikesById($data['postID']);

            if (postIsLikedByUser($data['postID'], $username)) {
                $like_class = " is-liked";
            }
            else {
                $like_class = "";
            }

            $num_comments = getNumCommentsById($data['postID']);

            if ($data['user_id'] == $username) {
                $edit_button = 
                    '<div class="post-icon post-icon-edit">
                        <a href="post.php?action=edit&id='.$data['postID'].'">
                            <i class="fa-solid fa-pencil"></i>
                        </a>         
                    </div>';
            }
            else {
                $edit_button = "";
            }

            $response .='
                
                    <div class="post" id="p.'.$data['postID'].'" href="post.php?action=view&id='.$data['postID'].'">

                        <a href="userpage.php?user='.$data['user_id'].'">
                            <h2>'.$data['user_id'].'</h2>
                        </a>
                        <p>
                        '.$data['content'].'
                        </p>
                        <div class="post-footer">
                            <div class="post-icon-holder">
                                <div class="post-icon post-icon-like'.$like_class.'">
                                    <div onclick=likePost('.$data['postID'].')>
                                        <i class="fa-solid fa-heart"></i>
                                    </div>
                                    <p>'.$num_likes.'</p>
                                </div>
                                <div class="post-icon post-icon-comment">
                                    <a href="post.php?action=view&id='.$data['postID'].'">
                                        <i class="fa-solid fa-comment"></i>
                                    </a>      
                                    <p>'.$num_comments.'</p>
                                </div>'.$edit_button.'
                            </div>
                            <div class="post-date">'.$data['created_at'].'</div>
                        </div>  

                                    
                    </div>
                        
            ';
        }
        exit($response);
    }

    else{

        $postloadUN = $_POST['username'];

        $start = $_POST['start'];
        $limit = $_POST['limit'];
        
        $username = $_SESSION['username'];

        $post_data = getPostsByUser($postloadUN, $start, $limit);

        foreach($post_data as $data) {

            $num_likes = getNumLikesById($data['postID']);

            if (postIsLikedByUser($data['postID'], $username)) {
                $like_class = " is-liked";
            }
            else {
                $like_class = "";
            }

            $num_comments = getNumCommentsById($data['postID']);

            if ($data['user_id'] == $username) {
                $edit_button = 
                    '<div class="post-icon post-icon-edit">
                        <a href="post.php?action=edit&id='.$data['postID'].'">
                            <i class="fa-solid fa-pencil"></i>
                        </a>         
                    </div>';
            }
            else {
                $edit_button = "";
            }

            $response .='
                <div class="post" id="p.'.$data['postID'].'">
                <a href="userpage.php?user='.$data['user_id'].'">
                    <h2>'.$data['user_id'].'</h2>
                </a>
                <p>
                    '.$data['content'].'
                </p>
                <div class="post-footer">
                    <div class="post-icon-holder">
                        <div class="post-icon post-icon-like'.$like_class.'">
                            <div onclick=likePost('.$data['postID'].')>
                                <i class="fa-solid fa-heart"></i>
                            </div>
                            <p>'.$num_likes.'</p>
                        </div>
                        <div class="post-icon post-icon-comment">
                            <a href="post.php?action=view&id='.$data['postID'].'">
                                <i class="fa-solid fa-comment"></i>
                            </a>      
                            <p>'.$num_comments.'</p>
                        </div>'.$edit_button.'
                    </div>
                    <div class="post-date">'.$data['created_at'].'</div>
                </div>                
            </div>    
            ';
        }
        exit($response);
    }
}

?>
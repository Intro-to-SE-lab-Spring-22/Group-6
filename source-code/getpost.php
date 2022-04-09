<?php
//connect to db
session_start();
require_once('credentials.php');
$connection = new mysqli($hn, $un, $pw, $db);
//will activate once POST has been sent
if(ISSET($_POST['userPost'])) {
    if($_POST['userPost'] == 0){
        $postStart = $_POST['start'];
        //sanitize
        $start = $_POST['start'];//mysqli_real_escape_string($connection, $postStart);//mysqli_real_escape_string($connection, $_POST['start']); // $connection->
        $limit = mysqli_real_escape_string($connection, $_POST['limit']); //$connection->
        //get username
        $username = $_SESSION['username'];
        //get a list of all friends of the user
        $query = "SELECT id_receiver FROM friends WHERE id_sender = '$username'";

        $result = $connection->query($query);

        $friendList = [];
        // put results into an array
        while ($row = mysqli_fetch_assoc($result)) {

            array_push($friendList, $row['id_receiver']);
        }
        //make sure own posts are on page
        array_push($friendList, $username);
        if (!$result) {
            die($connection->error);
        }

    
        //echo "\n";
        if($result->num_rows > 0) {
            //query to x num of posts by friends. must turn array into a string for SQL so thats what implode does
            $query = "SELECT * FROM post WHERE user_id IN ('" . implode("', '", $friendList) 
            . "') ORDER BY created_at DESC LIMIT $start, $limit";
            $result = $connection->query($query);
            
            
            
            if (!$result) {
                die($connection->error);
            }

            $response = "";

            //getting data attributes from each post
            while($data = mysqli_fetch_assoc($result)){
                $query = "SELECT COUNT(*) as numlikes FROM likes WHERE postID = ".$data['postID'];

                $subresult = $connection->query($query);
                
                if (!$subresult) {
                    die($connection->error);
                }
                
                $subdata = mysqli_fetch_assoc($subresult);
                $numlikes = $subdata['numlikes'];
                //getting data if user has liked post so it will show
                $query = "SELECT COUNT(*) as already_liked FROM likes WHERE postID = '".$data['postID']."' AND username = '".$username."'";
    
                $subresult = $connection->query($query);
                
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
                //getting comments from db
                $query = "SELECT COUNT(*) as num_comments FROM comments WHERE postID = '".$data['postID']."'";
    
                $subresult = $connection->query($query);
                
                if (!$subresult) {
                    die($connection->error);
                }

                $subdata = mysqli_fetch_assoc($subresult);
                $num_comments = intval($subdata['num_comments']);
                //if owner of post, user can edit
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
                // this is the html format of the post
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
                                        <p>'.$numlikes.'</p>
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
            //exit will allow respose to be appended to home.php
            exit($response);
        }
        
        else{
            
            exit('reachedMax');
        }
    }

    else{
        //this section is very similar to above section, it just is for getting posts of one user, ie the user logged in so that they can see all of their own posts on the user profile page
        $postloadUN = $_POST['username'];
        $postStart = $_POST['start'];

        $start = $_POST['start'];//mysqli_real_escape_string($connection, $postStart);//mysqli_real_escape_string($connection, $_POST['start']); // $connection->
        $limit = mysqli_real_escape_string($connection, $_POST['limit']); //$connection->
        
        $username = $_SESSION['username'];

        $query = "SELECT * FROM post WHERE user_id = '$postloadUN' ORDER BY created_at DESC LIMIT $start, $limit";

        $result = $connection->query($query);

        

        
        
    

        $response = "";


        while($data = mysqli_fetch_assoc($result)){

            $query = "SELECT COUNT(*) as numlikes FROM likes WHERE postID = ".$data['postID'];

            $subresult = $connection->query($query);
            
            if (!$subresult) {
                die($connection->error);
            }
            
            $subdata = mysqli_fetch_assoc($subresult);
            $numlikes = $subdata['numlikes'];

            $query = "SELECT COUNT(*) as already_liked FROM likes WHERE postID = '".$data['postID']."' AND username = '".$username."'";

            $subresult = $connection->query($query);
            
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

            $query = "SELECT COUNT(*) as num_comments FROM comments WHERE postID = '".$data['postID']."'";
    
            $subresult = $connection->query($query);
            
            if (!$subresult) {
                die($connection->error);
            }

            $subdata = mysqli_fetch_assoc($subresult);
            $num_comments = intval($subdata['num_comments']);

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
                            <p>'.$numlikes.'</p>
                        </div>
                        <div class="post-icon post-icon-comment">
                            <a href="post.php?action=view&id='.$data['postID'].'">
                                <i class="fa-solid fa-comment"></i>
                            </a>      
                            <p>'.$num_comments.'</p>
                        </div>'.$edit_buttom.'
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
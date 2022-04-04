<?php

session_start();
require_once('credentials.php');
$connection = new mysqli($hn, $un, $pw, $db);
if(ISSET($_POST['getpost']))
    console.log($_POST['userPost']);
    if($_POST['userPost'] == 0){
        $postStart = $_POST['start'];

        $start = $_POST['start'];//mysqli_real_escape_string($connection, $postStart);//mysqli_real_escape_string($connection, $_POST['start']); // $connection->
        $limit = mysqli_real_escape_string($connection, $_POST['limit']); //$connection->
        
        $username = $_SESSION['username'];

        $query = "SELECT id_receiver FROM friends WHERE id_sender = '$username'";

        $result = $connection->query($query);

        $friendList = [];

        while ($row = mysqli_fetch_assoc($result)) {

            array_push($friendList, $row['id_receiver']);
        }
        array_push($friendList, $username);
        if (!$result) {
            die($connection->error);
        }

    
        echo "\n";
        if($result->num_rows > 0) {
            $query = "SELECT * FROM post WHERE user_id IN ('" . implode("', '", $friendList) 
            . "') ORDER BY created_at DESC LIMIT $start, $limit";
            $result = $connection->query($query);
            
            
            
            if (!$result) {
                die($connection->error);
            }

            $response = "";


            while($data = mysqli_fetch_assoc($result)){
                $response .='
                    <div class="post">
                    <a href="userpage.php?user='.$data['user_id'].'">
                        <h2>'.$data['user_id'].'</h2>
                    </a>
                    <p>
                        '.$data['content'].'
                    </p>
                    <div class="post-footer">
                        <div class="post-icon-holder">
                            <div class="post-icon post-icon-like">
                                <i class="fa-solid fa-heart"></i>
                                <p>12</p>
                            </div>
                            <div class="post-icon post-icon-comment">
                                <a href="testpage.php">
                                    <i class="fa-solid fa-comment"></i>
                                </a>      
                                <p>12</p>
                            </div>
                            <div class="post-icon post-icon-edit">
                                <a href="testpage.php">
                                    <i class="fa-solid fa-pencil"></i>
                                </a>         
                            </div>
                        </div>
                        <div class="post-date">'.$data['created_at'].'</div>  
                    </div>
                </div>                
                ';
            }
            exit($response);
        }



        
        else{
            
            exit('reachedMax');
        }
    }

    else{

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
                            <a href="testpage.php">
                                <i class="fa-solid fa-comment"></i>
                            </a>      
                            <p>12</p>
                        </div>
                        <div class="post-icon post-icon-edit">
                            <a href="testpage.php">
                                <i class="fa-solid fa-pencil"></i>
                            </a>         

                        </div>
                        <div class="post-date">'.$data['created_at'].'</div>  
                    </div>
                </div>                
                ';
            }
            exit($response);
        }



        
        else{
            exit('reachedMax');
        }
    }

?>
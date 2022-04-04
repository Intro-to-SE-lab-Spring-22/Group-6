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
            echo "MADE IT HERE";
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

        

        
        
    

    
        echo "\n";
        if($result->num_rows > 0) {
            
            
            
            
            

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
            echo "MADE IT HERE";
            exit('reachedMax');
        }
    }

?>
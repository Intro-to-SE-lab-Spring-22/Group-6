<?php

require_once("verify_user.php");
require_once('sql_queries.php');

$user_username = $_SESSION["username"];
$display_username = $_GET["user"];

$firstname = $lastname = $email = "";
$user_exists = $are_friends = $request_sent = $request_pending = false;
$button_text = "Add Friend";
$button_function = "add_friend()";

// Display data
$data = getUserDataById($display_username);

if ($data) {
    $user_exists = true;

    $firstname = $data["firstName"];
    $lastname = $data["lastName"];
    $email = $data["email"];
}
include ("navbar.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">
  <title>Home</title>
  <script src="js/post_requests.js"></script>
  <script src="js/functions.js"></script>
  <!-- jQuery + Bootstrap JS -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
  <script src="https://kit.fontawesome.com/c56bd8cfd4.js" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

</head>

<body onload="<?="friend_request('$display_username', 'false')"?>">
    
    <main>
        
        <?php 
        //load personal data
        if ($user_exists) {
            $path = "../images/profile/$display_username.*";
            $result = glob($path);
            if (!empty($result)) {
                $img_src = $result[0];
            }
            else {
                $img_src = "../images/profile/default.png";
            }
            echo "<div class=\"userpage-display\">";
            echo "<div class=\"userpage-display-image\">";
            echo "<img src=\"$img_src\" />";
            if ($user_username == $display_username) {
                echo "<input type=\"file\" id=\"upload-image\" name=\"upload-image\" style=\"display:none\" onInput=\"updateImage(this)\">";
                echo "<div class=\"file-upload-button\" onclick=\"document.getElementById('upload-image').click()\">";
                echo "<i class = \"fa-solid fa-pencil\"></i>";
                echo "</div>";
            }      
            echo "</div>";
            echo "<div class=\"userpage-display-text\">";
            echo "<h1>$display_username</h1>";
            echo "<p>$firstname $lastname<br>";
            echo "<p>$email</p>";
            echo "</div>";
            echo "</div>";
            //if you are visiting another users page, you can request to be friends with them
            if ($display_username != $user_username) {
                echo "<br>";
                echo "<button type=\"submit\" id=\"submit_friend_request\" class=\"btn btn-outline-primary btn-small btn-block\" onclick=\"friend_request('$display_username', 'true')\">";
                echo " ";
                echo "</button>";
            }
        }
        else {
            echo "<p>User does not exist!</p>";
        }
        //below is posts
        ?>
        <div class="homepage" >
            <h1>
                Posts
            </h1>
            <div id="homepage">

            </div>

            
        </div>
    </main>

    <script>
        var start = 0;
        var limit = 10;
        var reachedMax = false;
        //script similar to home that loads and appends posts to userpage
        $(window).scroll(function(){
            if($(window).scrollTop() + $(window).height() > $(document).height() -1.5)
            {    
                getPost();
            }
            
        });

        $(document).ready(function (){
            
            
            if($(window).height() >= $(document).height()) {
                console.log("THE HEIGHT DOES NOT SEEM TO MATCH THE WINDOW, MUST LOAD MORE");
                getPost();
            }
            
        });

    
    

        //send post to getpost.php and appends
        function getPost(){
            var user = "<?php echo $display_username ?>";
            if (reachedMax){
                return;
            }
            $.ajax({
                url: 'getpost.php',
                type: "POST",
                dataType: 'text',
                data: {
                    getData: 1,
                    //userpost:1 signifies that it only needs the posts of one user, the username
                    userPost: 1,
                    username: user,
                    start: start,
                    limit: limit
                },
                success: function(response) {
                    if(response == 'reachedMax')
                        reachedMax == true;
                    else {
                        start += limit;
                        $("#homepage").append(response);
                    }
                }
            })
        }
    </script>
</body>
</html>

<script>
function likePost(postID) {
    $.post(
        "like_post.php",
        {
            postID: postID
        },
        function(result) {

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

function friend_request(username, update) {
    var username = username;
    var update = update;

    $.post(
        "friend_request.php",
        {
            username: username,
            update: update
        },
        function(result) {
            document.getElementById('submit_friend_request').innerHTML = result;
        }
    );
}


</script>
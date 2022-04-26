<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">
  <title>Home</title>
  <!-- jQuery + Bootstrap JS -->
  <script src="js/functions.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
  <script src="https://kit.fontawesome.com/c56bd8cfd4.js" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>

<?php
require_once("verify_user.php");
include("navbar.php");
?>

<main class="homepage" >
    <h1>
        Your Timeline
    </h1>
    <div id="homepage">

    </div>

            
</main>
    <script type="text/javascript">
        var start = 0;
        var limit = 10;
        var reachedMax = false;
        console.log("TEST1") ;

        //script to load more posts whenuser has reached bottom of page
        $(window).scroll(function(){
            console.log("TEST") ;
            if($(window).scrollTop() + $(window).height() > $(document).height() -1.5)
            {    
                
                getPost();
            }
            
        });

        //initial post loading for page, after nav and other objects have loaded
        $(document).ready(function (){
            
            
            if($(window).height() >= $(document).height()) {
                console.log("THE HEIGHT DOES NOT SEEM TO MATCH THE WINDOW, MUST LOAD MORE");
                getPost();
            }
            
        });

        //uses ajax to get posts. sends a POST to post.php requesting the post object. when recieved it appends it to the html elements.
        function getPost(){
            
            if (reachedMax){
                return;
            }
            $.ajax({
                url: 'getpost.php',
                type: "POST",
                dataType: 'text',
                data: {
                    getData: 1,
                    userPost: 0,
                    start: start,
                    limit: limit
                },
                success: function(response) {
                    json = JSON.parse(response);
                    data = json.data;

                    for (var i = 0; i < data.length; i++) {
                        document.querySelector("main").appendChild(
                            generatePostElement(
                                json.data[i].postID,
                                json.data[i].user_id,
                                json.data[i].content,
                                json.data[i].num_likes,
                                json.data[i].is_liked,
                                json.data[i].num_comments,
                                json.data[i].is_editable,
                                (json.data[i].created_at != json.data[i].last_edited_at),
                                json.data[i].last_edited_at)
                        );
                    }

                    start += limit;
                }
            })
        }
        
        //Like posts. send post to like_post.php with postID num and will change the heart to blue once liked.
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
    </script>



</body>
</html>
    

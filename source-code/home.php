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
?>

<body>
    
    


    <nav class="navbar">
        
        <ul class="navbar-nav">
            <li class="nav-item" id="home">
                <a href="home.php" class="nav-link">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--! Font Awesome Pro 6.0.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M575.8 255.5C575.8 273.5 560.8 287.6 543.8 287.6H511.8L512.5 447.7C512.5 450.5 512.3 453.1 512 455.8V472C512 494.1 494.1 512 472 512H456C454.9 512 453.8 511.1 452.7 511.9C451.3 511.1 449.9 512 448.5 512H392C369.9 512 352 494.1 352 472V384C352 366.3 337.7 352 320 352H256C238.3 352 224 366.3 224 384V472C224 494.1 206.1 512 184 512H128.1C126.6 512 125.1 511.9 123.6 511.8C122.4 511.9 121.2 512 120 512H104C81.91 512 64 494.1 64 472V360C64 359.1 64.03 358.1 64.09 357.2V287.6H32.05C14.02 287.6 0 273.5 0 255.5C0 246.5 3.004 238.5 10.01 231.5L266.4 8.016C273.4 1.002 281.4 0 288.4 0C295.4 0 303.4 2.004 309.5 7.014L564.8 231.5C572.8 238.5 576.9 246.5 575.8 255.5L575.8 255.5z"/></svg>
                    
                    <span class="link-text">Home</span>
                </a>
            </li>
            <li class="nav-item" id="compose">
                <a href="post.php?action=create" class="nav-link">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M467.1 241.1L351.1 288h94.34c-7.711 14.85-16.29 29.28-25.87 43.01l-132.5 52.99h85.65c-59.34 52.71-144.1 80.34-264.5 52.82l-68.13 68.13c-9.38 9.38-24.56 9.374-33.94 0c-9.375-9.375-9.375-24.56 0-33.94l253.4-253.4c4.846-6.275 4.643-15.19-1.113-20.95c-6.25-6.25-16.38-6.25-22.62 0l-168.6 168.6C24.56 58 366.9 8.118 478.9 .0846c18.87-1.354 34.41 14.19 33.05 33.05C508.7 78.53 498.5 161.8 467.1 241.1z"/></svg>
                    
                    <span class="link-text">Compose</span>
                </a>
            </li>
            <li class="nav-item" id="profile">
                <a href="userpage.php?user=<?= $_SESSION["username"]?>" class="nav-link">
                
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.0.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M224 256c70.7 0 128-57.31 128-128s-57.3-128-128-128C153.3 0 96 57.31 96 128S153.3 256 224 256zM274.7 304H173.3C77.61 304 0 381.6 0 477.3c0 19.14 15.52 34.67 34.66 34.67h378.7C432.5 512 448 496.5 448 477.3C448 381.6 370.4 304 274.7 304z"/></svg>
                
                    <span class="link-text">Profile</span>
                </a>
            </li>
            <li class="nav-item" id="logout">
                <a href="logout.php" class="nav-link">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.0.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M96 480h64C177.7 480 192 465.7 192 448S177.7 416 160 416H96c-17.67 0-32-14.33-32-32V128c0-17.67 14.33-32 32-32h64C177.7 96 192 81.67 192 64S177.7 32 160 32H96C42.98 32 0 74.98 0 128v256C0 437 42.98 480 96 480zM504.8 238.5l-144.1-136c-6.975-6.578-17.2-8.375-26-4.594c-8.803 3.797-14.51 12.47-14.51 22.05l-.0918 72l-128-.001c-17.69 0-32.02 14.33-32.02 32v64c0 17.67 14.34 32 32.02 32l128 .001l.0918 71.1c0 9.578 5.707 18.25 14.51 22.05c8.803 3.781 19.03 1.984 26-4.594l144.1-136C514.4 264.4 514.4 247.6 504.8 238.5z"/></svg>
                    <span class="link-text">Log Out</span>
                </a>
            </li>  

        </ul>
    </nav>



    <div id="right" class="column">
        <form method="POST" action="search.php" class="topnav">
            <input type="text" name="search" placeholder="Search"  id='search'>
            <input type="submit" id="search" >
                <span class="link-text">Search</span>
            </input>
        </form>
        
        <main class="homepage" >
            <h1>
                Your Timeline
            </h1>
            <div id="homepage">

            </div>

            
        </main>
    </div>
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
    

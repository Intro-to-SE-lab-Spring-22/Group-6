<?php

require_once("verify_user.php");
require_once('credentials.php');

$user_username = $_SESSION["username"];
$display_username = $_GET["user"];

$conn = new mysqli($hn, $un, $pw, $db);

if ($conn->connect_error) {
    die($conn->connect_error);
}

$firstname = $lastname = $email = "";
$user_exists = $are_friends = $request_sent = $request_pending = false;
$button_text = "Add Friend";
$button_function = "add_friend()";

// Display data
$query = "SELECT * FROM users
WHERE id = '$display_username'";

$result = $conn->query($query);

if (!$result) {
    die($conn->error);
}
if ($result->num_rows > 0) {
    $user_exists = true;
    while ($row = $result->fetch_array()) {
        $firstname = $row["firstName"];
        $lastname = $row["lastName"];
        $email = $row["email"];
        break;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">
  <title>Home</title>
  <!-- jQuery + Bootstrap JS -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
  <script src="https://kit.fontawesome.com/c56bd8cfd4.js" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

</head>

<body onload="<?="friend_request('$display_username', 'false')"?>">
    <nav class="navbar">
        <ul class="navbar-nav">
            <li class="nav-item" id="home">
                <a href="home.php" class="nav-link">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--! Font Awesome Pro 6.0.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M575.8 255.5C575.8 273.5 560.8 287.6 543.8 287.6H511.8L512.5 447.7C512.5 450.5 512.3 453.1 512 455.8V472C512 494.1 494.1 512 472 512H456C454.9 512 453.8 511.1 452.7 511.9C451.3 511.1 449.9 512 448.5 512H392C369.9 512 352 494.1 352 472V384C352 366.3 337.7 352 320 352H256C238.3 352 224 366.3 224 384V472C224 494.1 206.1 512 184 512H128.1C126.6 512 125.1 511.9 123.6 511.8C122.4 511.9 121.2 512 120 512H104C81.91 512 64 494.1 64 472V360C64 359.1 64.03 358.1 64.09 357.2V287.6H32.05C14.02 287.6 0 273.5 0 255.5C0 246.5 3.004 238.5 10.01 231.5L266.4 8.016C273.4 1.002 281.4 0 288.4 0C295.4 0 303.4 2.004 309.5 7.014L564.8 231.5C572.8 238.5 576.9 246.5 575.8 255.5L575.8 255.5z"/></svg>
                    
                    <span class="link-text">Home</span>
                </a>
            </li>
            <li class="nav-item" id="compose">
                <a href="#" class="nav-link">
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
        <nav class="topnav">
                
                
                <!-- <label for="search">Search</label>   -->
                
                <!-- <a href="search"> -->
                <input type="text" placeholder="Search">
                <a href="search.php" id="search">
                    <!-- <button type="submit"> -->
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <span class="link-text">Search</span>
                    <!-- </button> -->
                
                </a>
        </nav>
    <main>

        <?php 
        if ($user_exists) {
            echo "<h1>$display_username</h1>";
            echo "<p>$firstname $lastname<br>";
            echo "<p>$email</p>";

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
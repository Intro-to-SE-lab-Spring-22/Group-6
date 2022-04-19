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



    <?php
        require_once("verify_user.php");
        require_once('sql_queries.php');
    
        if (ISSET($_POST['searchVal'])){
            
            $searchq = $_POST['searchVal'];
            $searchq = preg_replace("#[^0-9a-z]#i","", $searchq);
            
            $data = searchDatabase($searchq);
            
            $response = '';
            if (count($data) == 0) {
                $response = "THERE ARE NO RESULTS";
            }
            else{
                foreach ($data as $row) {
                    $response .='
                        <div class="post">
                            <a href="userpage.php?user='.$row['id'].'">
                                <h2>'.$row['id'].'</h2>
                            </a>                
                        </div>    
                    ';
                }
            }
            exit($response);
        }
        include("navbar.php");
      
        ?>
            
      

<body>
    

        <main class="homepage" >
            <h1>
                Results
            </h1>
            <div id="homepage">

            </div>

            
        </main>
    </div>

</body>


<script>
    //javascript to send POST to php at top of the page
    function searchF(){
            var searchTxt = "<?php echo $_POST['search'] ?>"
            console.log(searchTxt);
            if(searchTxt != "")
            {
                $.post("search.php", {searchVal: searchTxt}, function(result) {
                $("#homepage").html(result);
            
                });
            }

        }
    //search when document loads
    $(document).ready(function (){
        searchF();
    });
    //search when you input text into textbox on another page and then hit submit
    function searchq() {
        console.log("FIREING FUNCTION");
        var searchTxt = $("input[name='search']").val();
        console.log(searchTxt);
        $.post("search.php", {searchVal: searchTxt}, function(result) {
            $("#homepage").html(result);
        
        });
    }
</script>
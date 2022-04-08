<?php 
session_start();

if (isset($_SESSION['username'])) {
  header('Location: home.php');
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">
  <title>Login</title>
  <!-- jQuery + Bootstrap JS -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</head>

<body>
  <div class="App">
    <div class="vertical-center">
      <div class="inner-block">
        <h3>Log In</h3>  
          <form onsubmit="return false">
          <div class="form-group">
            <label for="username">Username</label>  
            <input type="text" name="username" id="username"><br>
          </div>
          <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" id="password"><br>
          </div>
          <button type="submit" name="submit_log_in" id="submit_log_in" class="btn btn-outline-primary btn-lg btn-block" onclick="submitButton()">
            Log In
          </button>
          <a href="create_acct.php" class="btn btn-outline-primary btn-lg btn-block">
            Sign Up
          </a>
          <div id="placeholder"></div>
      </div>
    </div>
  </div>
</form>
    
   
</body>
</html>

<script>
//submit button that will activate on click
function submitButton() {
  var username = document.getElementById('username').value;
  var password = document.getElementById('password').value;

//send input fields to login.php
  $.post(
    "php/controller.php",
    {
      function: "login",
      username: username,
      password: password
    },
    function(result) {

      var json = JSON.parse(result);
      //get result and if login is successful, will redirect you to the home page
      if (json.success == "true") {
        document.location = json.location;
      }
      //else notify of faulty login credentials
      else {
        document.getElementById('placeholder').innerHTML = "Your username or password is incorrect";
      }
    }
  );

}


</script>






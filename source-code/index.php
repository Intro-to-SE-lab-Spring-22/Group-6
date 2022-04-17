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
            <input type="password" id="password"><br>
          </div>
          <button type="submit" id="submit_log_in" value="Log In" class="btn btn-outline-primary btn-lg btn-block" onclick="submitButton()">
            Log In
          </button>
          <a href="create_acct.php" class="btn btn-outline-primary btn-lg btn-block">
            Sign Up
          </a>
      </div>
    </div>
  </div>
</form>
    <div id="placeholder"></div>
   
</body>
</html>

<script>

function submitButton() {
  var username = document.getElementById('username').value;
  var password = document.getElementById('password').value;


  $.post(
    "login.php",
    {
      username: username,
      password: password
    },
    function(result) {
      var json = JSON.parse(result);

      if (json.success == "true") {
        document.location = json.location;
      }
      else {
        document.getElementById('placeholder').innerHTML = "Your username or password is incorrect";
      }
    }
  );

}


</script>






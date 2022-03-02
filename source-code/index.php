<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Default Title</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="ajax.js"></script>
</head>

<body>
  <form onsubmit="return false">
    <div class="row">
      Username<br><input type="text" id="username"><br>
    </div>
    <div class="row">
      Password<br><input type="password" id="password"><br>
    </div>
    <div class="row">
      <input type="submit" id="submit" value="Log In" onclick="submitButton()">
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

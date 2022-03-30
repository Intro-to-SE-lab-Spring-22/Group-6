<?php
require_once("verify_user.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Default Title</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="ajax.js"></script>
</head>

<body>
    Content<br><textarea id="content" name="content" rows="6" cols = "75"></textarea><br>
    <input type="submit" id="submit" value="Create Post" onclick="submitButton()">
</body>
</html>

<script>

function submitButton() {
  var content = document.getElementById('content').value;

  $.post(
    "create_post.php",
    {
        content: content
    },
    function(result) {
        alert(result);
        var json = JSON.parse(result);

        if (json.success == "true") {
            document.location = json.location;
        }
    }
  );

}

</script>
<?php
session_start();

require_once("verify_user.php");

require_once('credentials.php');

$conn = new mysqli($hn, $un, $pw, $db);

if ($conn->connect_error) {
    die($conn->connect_error);
}

$username = $_SESSION["username"];
$firstname = $lastname = $email = "";

$query = "SELECT * FROM users
WHERE id = '$username'";

$result = $conn->query($query);

if (!$result) {
    die($conn->error);
}
if ($result->num_rows > 0) {
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
  <meta charset="utf-8">
  <title>Default Title</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="ajax.js"></script>
</head>

<body>
  <p>
      <?= $username ?><br>
      <?= $firstname ?><br>
      <?= $lastname ?><br>
      <?= $email ?><br>
</body>
</html>
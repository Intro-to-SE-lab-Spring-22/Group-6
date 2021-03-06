<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">

   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <title>Create Account</title>
    <!-- jQuery + Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script> 
</head>
<body>
    <div class="App">
        <div class="vertical-center">
            <div class="inner-block">
                <form onsubmit="return false" > 
                    <h3>Register</h3>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" class="form-control" name="username" id="username" />
                    </div>
                    <div class="form-group">
                        <label>First name</label>
                        <input type="text" class="form-control" name="firstname" id="firstName" />
                    </div>
                    <div class="form-group">
                        <label>Last name</label>
                        <input type="text" class="form-control" name="lastname" id="lastName" />
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" id="email" />
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" name="password" id="password" />
                    </div>
                    <button type="submit" name="submit_signup" id="submit_signup" class="btn btn-outline-primary btn-lg btn-block" onclick="submitSignUp()">
                        Sign up
                    </button>
                </form>
                <div id="placeholder"></div>
            </div>
        </div>
    </div>
</body>
</html>

<script>
    function submitSignUp()
    {
        var username = document.getElementById('username').value;
        var firstname = document.getElementById('firstName').value;
        var lastname = document.getElementById('lastName').value;
        var email = document.getElementById('email').value;
        var password = document.getElementById('password').value;

    $.post(
        "php/controller.php",
        {
        function: "createAcct",
        username: username,
        firstname: firstname,
        lastname: lastname,
        email: email,
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
                document.getElementById('placeholder').innerHTML = "Your username or email is already taken";
            }
        }
    );
    }
</script>
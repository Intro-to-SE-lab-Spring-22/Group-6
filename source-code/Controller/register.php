<?php

include('../credentials.php');

$connection = mysqli_connect($hn, $un, $pw, $db);



$firstName = $lastName = $email = $username = $password = "";
if(isset($_POST["submit_signup"])){
    $firstName = $_POST["firstname"];
    $lastName = $_POST["lastname"];
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = $_POST["password"];

    $usernamecheck= mysqli_query($connection, "SELECT * FROM users WHERE id = '{$username}' OR email ='{$email}'");
    $rowCount = mysqli_num_rows($usernamecheck);
    
    
    
    
    
    if(!empty($username) && !empty($firstName) && !empty($lastName) && !empty($email) && !empty($password)){
        
        if($rowCount > 0){
            $usernameTaken = '
            <div class="alert alert-danger" role="alert">
                User with username already exits
            </div>';
        }
        else{
            
            $_first_name = mysqli_real_escape_string($connection, $firstname);
            $_last_name = mysqli_real_escape_string($connection, $lastname);
            $_email = mysqli_real_escape_string($connection, $email);
            $_username = mysqli_real_escape_string($connection, $username);
            $_password = mysqli_real_escape_string($connection, $password);

           
            

            if((preg_match("/^[a-zA-Z ]*$/", $_first_name)) && (preg_match("/^[a-zA-Z ]*$/", $_last_name)) &&
             (filter_var($_email, FILTER_VALIDATE_EMAIL))){
                
                
                $fullName = $firstName . " " . $lastName;
                // Password hash
                $password_hash = password_hash($password, PASSWORD_BCRYPT);
                // Query
                $sql = "INSERT INTO users (id, firstName, lastName, name, email, password) VALUES ('{$username}', '{$firstName}', '{$lastName}', '{$fullName}', '{$email}', '{$password_hash}')";
                
                // Create mysql query
                $sqlQuery = mysqli_query($connection, $sql);
                
                if(!$sqlQuery){
                    die("MySQL query failed!" . mysqli_error($connection));
                } 
            }
        }        
    }
    else{
    //This will be to inform user that they must input information.
    }
}

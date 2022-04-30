<?php
include("../../source-code");
class unitTest extends \Codeception\Test\Unit
{
    
    

    private function _executeCreateAccount(array $parrams = array())
    {
        $_POST = $parrams;
        ob_start();
        include 'register.php';
        return ob_get_clean();
    }
    // public function testcreateAccount()
    // {
    //     require_once("source-code/php/credentials.php");
    //     $firstname = "Bob";
    //     $lastname = "Jenkins";
    //     $email = "bob@gmail.com";
    //     $username = "bobbyj";
    //     $password = "password";

    //     $response = createAccount($firstname,$lastname,$email,$username,$password);

    //     // this-> asserttrue(response[0]);



    // }
    // public function getOnePost()
    // {

    // }

}

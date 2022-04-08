<?php

class unitTest extends \Codeception\Test\Unit
{
    private function _executeCreateAccount(array $parrams = array())
    {
        $_POST = $parrams;
        ob_start();
        include 'register.php';
        return ob_get_clean();
    }
    public function createAccount()
    {
        if(isset($_POST['submit_signup']))
        {
            unset($_POST['submit_signup']);
        }
        $username = "bobby23";
        $post = array('firstName'=>"Bobby", 
                        'lastName'=>'Jenkins',
                        'email'=>"bobby@gmail.com", 
                        'username'=>"bobby23", 
                        'password'=>'password');
        $this->_executeCreateAccount($post);
        $this->assertTrue($_SESSION['username'], $username );



    }
}

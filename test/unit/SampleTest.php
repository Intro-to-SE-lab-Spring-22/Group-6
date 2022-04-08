<?php
// require_once 'PHPUnit/Autoload.php';

class SampleTest extends \PHPUnit\Framework\TestCase
{
    private function _executeCreateAccount(array $parrams = array())
    {
        $_POST = $parrams;
        ob_start();
        require_once( '/source-code/home.php');
        return ob_get_clean();
    }
    public function testCreateAccount()
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
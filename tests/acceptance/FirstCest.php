<?php



class FirstCest
{
    public function frontpageWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('Log In');
    }
    public function userCanLogIn(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->fillField('username', 'test');
        $I->fillField('password', 'test');
        $I->click('submit_log_in');
        $I->wait(2);
        $I->see('Timeline');
    }
    public function userCanNavigateToCreateAccount(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->click('Sign Up');
        $I->see('Register');

    }
    public function userCannotLogInWithInvalidCredentials(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->fillField('username', 'frank');
        $I->fillField('password', '123456');
        $I->click('submit_log_in');
        
        $I->see('Your username or password is incorrect');
    }
    public function cannotLogInWithoutPassword(AcceptanceTester $I)
    {
        $I->amOnPage('/home.php');
        // $I->see('homepage');
        $I->see('Log In');
    }
    public function userCanSeeProfile(AcceptanceTester $I)
    {
        $this->userCanLogIn($I);
        $I->click('Profile');
        $I->see('Posts');
    }
    public function userCanSearchForUser(AcceptanceTester $I)
    {
        $this->userCanLogIn($I);
        $I->click(['id'=>'searchsbumit']);
        $I->fillField('#searchtext', 'hailey');
        $I->click(['id'=>'searchsbumit']);
        $I->wait(1);
        $I->see('Results');
        $I->see('hailey');
    }
    public function userCanComposePost(AcceptanceTester $I)
    {
        $this->userCanLogIn($I);
        $I->click('Compose');
        $I->wait(1);
        $I->fillField('post_content', "This is a test post and I am testing the post functionality");
        $I->click('#submit_post');
        $I->wait(1);
        $I->see("Created:");
    }
    public function userCanLikePost(AcceptanceTester $I)
    {
        $this->userCanLogIn($I);
        $I->click("#likeButton");
        
    }
    public function userCanLogOut(AcceptanceTester $I)
    {
        $this->userCanLogIn($I);
        $I->click('Log Out');
        $I->see('Log In');
    }
    public function userCanSeePhotos(AcceptanceTester $I)
    {
        $this->userCanLogIn($I);
        $I->seeElement("#profilePicture");
    }
    public function userCanChangeProfilePhoto(AcceptanceTester $I)
    {
        $this->userCanLogIn($I);
        $I->click("Profile");
        $I->click(["class" => "file-upload-button"]);
    }
    public function userCanEditPost(AcceptanceTester $I)
    {
        $this->userCanLogIn($I);
        $I->click(["id"=>"editPost"]);
        $I->fillField('post_content', "This is a test post for editing");
        $I->click("Save");
        $I->click("Home");
        $I->see("This is a test post for editing");
    }

    
    public function _before(AcceptanceTester $I)
    {
    }

    // tests
    public function tryToTest(AcceptanceTester $I)
    {
    }



    
}

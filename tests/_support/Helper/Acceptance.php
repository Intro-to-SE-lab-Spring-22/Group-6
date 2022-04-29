<?php 
// Simply place the following two functions in _support/Helper/Acceptance.php
// Then you can call $I->verifyRedirect(...) inside your tests

namespace Helper;
class Acceptance extends \Codeception\Module
{
    
    /**
     * Ensure that a particular URL does NOT contain a 301/302
     * nor a "Location" header
     */
    public function verifyNoRedirect($url)
    {    
        $phpBrowser = $this->getModule('PhpBrowser');
        $guzzle = $phpBrowser->client;
        
        // Disable the following of redirects
        $guzzle->followRedirects(false);
        
        $phpBrowser->_loadPage('GET', $url);
        $response = $guzzle->getInternalResponse();
        $responseCode   = $response->getStatus();
        $locationHeader = $response->getHeader('Location');
        
        $this->assertNotEquals($responseCode, 301);
        $this->assertNotEquals($responseCode, 302);
        $this->assertNull($locationHeader);
        
        $guzzle->followRedirects(true);
    }
    
    /**
     * Ensure that a particular URL redirects to another URL
     * 
     * @param string $startUrl
     * @param string $endUrl (should match "Location" header exactly)
     * @param integer $redirectCode (301 = permanent, 302 = temporary)
     */
    public function verifyRedirect($startUrl, $endUrl, $redirectCode = 301)
    {
        $phpBrowser = $this->getModule('PhpBrowser');
        $guzzle = $phpBrowser->client;
        
        // Disable the following of redirects
        $guzzle->followRedirects(false);
        
        $phpBrowser->_loadPage('GET', $startUrl);
        $response = $guzzle->getInternalResponse();
        $responseCode   = $response->getStatus();
        $locationHeader = $response->getHeader('Location');
        
        $this->assertEquals($redirectCode, $responseCode);
        $this->assertEquals($endUrl, $locationHeader);
        
        $guzzle->followRedirects(true);
    }
   
}
<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
namespace Diglin\Ricardo\Managers;

class SecurityTest extends TestAbstract
{
    /**
     * @var Security
     */
    protected $_securityManager;

    protected function setUp()
    {
        $this->_securityManager = $this->getServiceManager()->getSecurityManager();
        parent::setUp();
    }

    protected function tearDown()
    {
        $this->_securityManager = null;
        parent::tearDown();
    }

    public function testGetAnonymousToken()
    {
        $token = $this->_securityManager->getAnonymousToken();

        $this->assertCount(1, $this->_matchToken($token), 'Anonymous Token is ' . $token);

        echo 'Anonymous token: ' . $token . "\n";
    }

    public function testGetTemporaryToken()
    {
        $token = $this->_securityManager->getTemporaryToken();

        $this->assertCount(1, $this->_matchToken($token), 'Temporary Token is ' . $token);
        $this->assertAttributeContains('http', '_validationUrl', $this->_securityManager);

        echo 'Temporary token: ' . $token . "\n";
        echo 'Validation url: ' . $this->_securityManager->getValidationUrl() . "\n";
    }

    /**
     * @expectedException \Diglin\Ricardo\Exceptions\SecurityErrors
     */
    public function testgetCredentialTokenException()
    {
        // Exception is expected because we do not simulate the authorization process when we ask a new token credential
        $this->_securityManager->setAllowSimulateAuthorization(false);
        $this->_securityManager->getCredentialToken();
    }

    /**
     * @depends testGetTemporaryToken
     */
    public function testSimulateValidationUrl()
    {
        $this->_securityManager->getTemporaryToken();
        $result = $this->_securityManager->simulateValidationUrl();
        $this->assertTrue($result['Success'], 'Result of simulate url ' . print_r($result, true));
    }

    /**
     * @depends testSimulateValidationUrl
     */
    public function testGetCredentialToken()
    {
        $this->_securityManager->setAllowSimulateAuthorization(true);
        $result = $this->_securityManager->getCredentialToken();

        $this->assertCount(1, $this->_matchToken($result), 'Credential Token is ' . $result);
        echo 'Credential token: ' . $result . "\n";
        echo 'Credential token Expiration Date: ' . $this->_securityManager->getCredentialTokenExpirationDate()  . "\n";
        echo 'Credential token Session Duration: ' . $this->_securityManager->getCredentialTokenSessionDuration()  . "\n";

        return $result;
    }

    public function testGetAntiforgeryToken()
    {
        $result = $this->_securityManager->getAntiforgeryToken();

        $this->assertCount(1, $this->_matchToken($result), 'Antiforgery Token is ' . $result);
        $this->assertTrue(isset($result), 'TokenExpirationDate is missing');

        echo 'Antiforgery Key ' . $result;
    }

    /**
     * Cannot be tested :(
     */
//    public function testRefreshTokenCredential()
//    {
//        $result = $this->_securityManager->refreshToken('9dc9914e-d0c6-4162-9374-05fe5809df89');
//
//        echo $this->getLastApiDebug();
//
//        $this->assertTrue(isset($result['TokenCredentialKey']), 'Refreshed TokenCredentialKey is missing ' . print_r($result, true));
//    }

    protected function _matchToken($token)
    {
        // token example:  4a8b285e-417b-431e-bc91-4295793c0d71
        preg_match('/[^.]{8}-[^.]{4}-[^.]{4}-[^.]{4}-[^.]{12}/', $token, $matches);

        return $matches;
    }
}
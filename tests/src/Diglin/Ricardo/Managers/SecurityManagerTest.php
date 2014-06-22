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

class SecurityManagerTest extends TestAbstract
{
    /**
     * @var Security
     */
    protected $_securityManager;

    protected function setUp()
    {
        $this->_securityManager = new Security($this->getServiceManager());
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
        $this->assertAttributeContains('Date', '_anonymousTokenExpirationDate', $this->_securityManager);

        echo 'Anonymous token: ' . $token . "\n";
    }

    public function testGetTemporaryToken()
    {
        $token = $this->_securityManager->getTemporaryToken();

        $this->assertCount(1, $this->_matchToken($token), 'Temporary Token is ' . $token);
        $this->assertAttributeContains('Date', '_temporaryTokenExpirationDate', $this->_securityManager);
        $this->assertAttributeContains('http', '_validationUrl', $this->_securityManager);

        echo 'Temporary token: ' . $token  . "\n";
        echo 'Validation url: ' . $this->_securityManager->getValidationUrl()  . "\n";
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
    public function testGetTokenCredential()
    {
        $temporaryToken = $this->_securityManager->getTemporaryToken();

        $result = $this->getServiceManager()
            ->proceed('security', 'TokenCredential', $temporaryToken);

        $this->assertTrue(!isset($result['TokenExpirationDate']), 'TokenExpirationDate is NOT missing');
        $this->assertTrue(!isset($result['TokenCredentialKey']), 'TokenCredentialKey is NOT missing');

        //print_r($this->getServiceManager()->getApi()->getLastDebug(true));

        $this->_securityManager->simulateValidationUrl();

        $result = $this->getServiceManager()
            ->proceed('security', 'TokenCredential', $temporaryToken);

        $this->assertTrue(isset($result['TokenExpirationDate']), 'TokenExpirationDate is missing');
        $this->assertTrue(isset($result['TokenCredentialKey']), 'TokenCredentialKey is missing');

        //print_r($this->getServiceManager()->getApi()->getLastDebug(true));

        $token =  (isset($result['TokenCredentialKey'])) ? $result['TokenCredentialKey'] :  '';

        $this->assertCount(1, $this->_matchToken($token), 'Credential Token is ' . $token);
        $this->assertContains('Date', $result['TokenExpirationDate']);

        echo 'Credential token: ' . $token  . "\n";
        echo 'Credential token Expiration Date: ' . $result['TokenExpirationDate']  . "\n";
        echo 'Credential token Session Duration: ' . $result['SessionDuration']  . "\n";

        return $token;
    }

    /**
     * @depends testGetTokenCredential
     */
    public function testRefreshTokenCredential($token)
    {
        $token = '53eeb15b-f81b-4662-bb9b-6c063ef3c4bd';

        $result = $this->getServiceManager()->proceed('security', 'RefreshTokenCredential', $token);

        //print_r($this->getServiceManager()->getApi()->getLastDebug());

        $this->assertTrue(isset($result['TokenCredentialKey']), 'Refreshed TokenCredentialKey is missing ' . print_r($result, true));
    }

    public function testGetAntiforgeryToken()
    {
        $result = $this->_serviceManager->proceed('security', 'AntiforgeryToken');

        //print_r($this->getServiceManager()->getApi()->getLastDebug());

        $this->assertTrue(isset($result['AntiforgeryTokenKey']), 'Antiforgery Token Key is missing');
        $this->assertCount(1, $this->_matchToken($result['AntiforgeryTokenKey']), 'Antiforgery Token is ' . $result['AntiforgeryTokenKey']);
        $this->assertTrue(isset($result['TokenExpirationDate']), 'TokenExpirationDate is missing');

        echo 'Antiforgery Key ' . $result['AntiforgeryTokenKey'];
    }

    protected function _matchToken($token)
    {
        // token example:  4a8b285e-417b-431e-bc91-4295793c0d71
        preg_match('/[^.]{8}-[^.]{4}-[^.]{4}-[^.]{4}-[^.]{12}/', $token, $matches);

        return $matches;
    }
}
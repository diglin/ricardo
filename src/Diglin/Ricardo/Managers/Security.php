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

use \Diglin\Ricardo\Services\Security as SecurityService;
use \Diglin\Ricardo\Services\ServiceAbstract;
use \Diglin\Ricardo\Service;

/**
 * Class Security
 *
 * Do the bridge with the Security service class and its data
 *
 * @package Diglin\Ricardo
 */
class Security extends ManagerAbstract
{
    /**
     * @var string
     */
    protected $_serviceName = 'security';

    /**
     * @var string
     */
    protected $_allowSimulateAuthorization;

    /**
     * @var string
     */
    protected $_temporaryToken;

    /**
     * @var string
     */
    protected $_temporaryTokenExpirationDate;

    /**
     * @var string
     */
    protected $_validationUrl = null;

    /**
     * @var string
     */
    protected $_anonymousToken;

    /**
     * @var string
     */
    protected $_anonymousTokenExpirationDate;

    /**
     * @var string
     */
    //protected $_anonymousTokenSessionDuration;

    /**
     * @var string
     */
    protected $_tokenCredential;

    /**
     * @var string
     */
    protected $_tokenCredentialExpirationDate;

    /**
     * @var int
     */
    protected $_tokenCredentialSessionDuration;

    /**
     * @var int
     */
    protected $_tokenCredentialSessionStart;

    /**
     * @var string
     */
    protected $_antiforgeryToken;

    /**
     * @var string
     */
    protected $_antiforgeryTokenExpirationDate;

    /**
     * @param Service $serviceManager
     * @param bool $allowSimulateAuthorization
     */
    public function __construct(Service $serviceManager, $allowSimulateAuthorization = false)
    {
        $this->_allowSimulateAuthorization = $allowSimulateAuthorization;
        parent::__construct($serviceManager);
    }

    /**
     * Get the token depending of the type wished
     *
     * @param $typeOfToken
     * @return bool|mixed|string
     */
    public function getToken($typeOfToken)
    {
        switch ($typeOfToken)
        {
            case ServiceAbstract::TOKEN_TYPE_IDENTIFIED:
                return $this->getTokenCredential();
                break;
            case ServiceAbstract::TOKEN_TYPE_ANONYMOUS:
                return $this->getAnonymousToken();
                break;
            case ServiceAbstract::TOKEN_TYPE_ANTIFORGERY:
                return $this->getAntiforgeryToken();
                break;
            case ServiceAbstract::TOKEN_TYPE_DEFAULT:
                return '';
                break;
        }
    }

    /**
     * Get the anonymous token and set internally the expiration date for this anonymous token
     *
     * @return string|array
     */
    public function getAnonymousToken()
    {
        if ($this->_anonymousTokenExpirationDate && !$this->isDateExpired($this->_anonymousTokenExpirationDate)) {
            return $this->_anonymousToken;
        }

        $result = $this->_proceed('AnonymousTokenCredential');
        if (isset($result['TokenExpirationDate']) && isset($result['TokenCredentialKey'])) {
            $this->_anonymousTokenExpirationDate = $result['TokenExpirationDate'];
            //$this->_anonymousTokenSessionDuration = $result['SessionDuration'];
            $this->_anonymousToken = $result['TokenCredentialKey'];
            return $this->_anonymousToken;
        }

        return $result;
    }

    /**
     * Get the temporary token, set internally the expiration date for this temporary token and the validation url
     *
     * @return string|array
     */
    public function getTemporaryToken()
    {
        if ($this->_temporaryToken && !$this->isDateExpired($this->_temporaryTokenExpirationDate)) {
            return $this->_temporaryToken;
        }

        $result = $this->_proceed('TemporaryCredential');

        if (isset($result['ExpirationDate']) && isset($result['TemporaryCredentialKey'])) {
            $this->_temporaryTokenExpirationDate = $result['ExpirationDate'];
            $this->_temporaryToken = $result['TemporaryCredentialKey'];
            $this->_validationUrl = $result['ValidationUrl'];
            return $this->_temporaryToken;
        }

        return $result;
    }

    /**
     * Get the token credential, set internally expiration date and session duration
     * Refresh the token if necessary
     *
     * @return string|array
     */
    public function getTokenCredential()
    {
        if ($this->_tokenCredential && !$this->isDateExpired($this->_tokenCredentialExpirationDate)
            && ($this->_tokenCredentialSessionStart + ($this->_tokenCredentialSessionDuration * 60)) < time()) {
            return $this->_tokenCredential;
        }

        if ($this->_tokenCredential && $this->_tokenCredentialSessionDuration
            && ($this->_tokenCredentialSessionStart + ($this->_tokenCredentialSessionDuration * 60)) > time() ) {
            return $this->refreshToken($this->_tokenCredential);
        }

        // Temporary Token must be created before to simulate the authorization
        $temporaryToken = $this->getTemporaryToken();

        if ($this->_allowSimulateAuthorization) {
            $this->simulateValidationUrl();
        }

        $result = $this->_proceed('TokenCredential', $temporaryToken);

        if (isset($result['TokenExpirationDate']) && isset($result['TokenCredentialKey'])) {
            $this->_tokenCredentialExpirationDate = $result['TokenExpirationDate'];
            $this->_tokenCredentialSessionDuration = $result['SessionDuration']; // in minutes
            $this->_tokenCredentialSessionStart = time(); // in seconds
            $this->_temporaryToken = null;
            return $this->_tokenCredential = $result['TokenCredentialKey'];
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getAntiforgeryToken()
    {
        $result = $this->_proceed('AntiforgeryToken');

        if (isset($result['AntiforgeryTokenKey']) && isset($result['TokenExpirationDate'])) {
            $this->_antiforgeryTokenExpirationDate = $result['TokenExpirationDate'];
            return $this->_antiforgeryToken = $result['AntiforgeryTokenKey'];
        }

        return $result;
    }

    /**
     * If the session duration period has expired, the token must be refreshed
     *
     * @param string $token
     * @return string | array
     */
    public function refreshToken($token)
    {
        $result = $this->_proceed('RefreshTokenCredential', $token);

        if (isset($result['TokenCredentialKey'])) {
            $this->_tokenCredentialSessionStart = time();
            return $this->_tokenCredential = $result['TokenCredentialKey'];
        }

        return $result;
    }

    /**
     * Check if the date in parameter is expired or not
     * The parameter must be a json date
     *
     * @param string $jsonExpirationDate '/Date(123456789+0200)/'
     * @return bool
     */
    public function isDateExpired($jsonExpirationDate)
    {
        $jsonDateTimestamp = $this->getHelper()->getJsonTimestamp($jsonExpirationDate, null);
        
        if (time() < $jsonDateTimestamp) {
            return false;
        }

        return true;
    }

    /**
     * Set the anonymous token, useful in case of data coming from saved DB
     *
     * @param $token
     * @return $this
     */
    public function setAnonymousToken($token)
    {
        $this->_anonymousToken = $token;
        return $this;
    }

    /**
     * Set the credential token, useful in case of data coming from saved DB
     *
     * @param mixed $tokenCredential
     * @return $this
     */
    public function setTokenCredential($tokenCredential)
    {
        $this->_tokenCredential = $tokenCredential;
        return $this;
    }

    /**
     * Set the temporary token, useful in case of data coming from saved DB
     *
     * @param mixed $temporaryToken
     * @return $this
     */
    public function setTemporaryToken($temporaryToken)
    {
        $this->_temporaryToken = $temporaryToken;
        return $this;
    }

    /**
     * Set the antiforgery token, useful in case of data coming from saved DB
     *
     * @param string $antiforgeryToken
     */
    public function setAntiforgeryToken($antiforgeryToken)
    {
        $this->_antiforgeryToken = $antiforgeryToken;
    }

    /**
     * Allow or not to simulate the authorization process
     *
     * @param bool $allow
     */
    public function setAllowSimulateAuthorization($allow)
    {
        $this->_allowSimulateAuthorization = (bool) $allow;
    }

    /**
     * Get if allow or not to simulate the authorization process
     *
     * @return bool
     */
    public function getAllowSimulateAuthorization()
    {
        return (bool) $this->_allowSimulateAuthorization;
    }

    /**
     * Set the anonymous token expiration date, useful in case of data coming from saved DB
     *
     * @param mixed $anonymousTokenExpirationDate
     * @return $this
     */
    public function setAnonymousTokenExpirationDate($anonymousTokenExpirationDate)
    {
        $this->_anonymousTokenExpirationDate = $anonymousTokenExpirationDate;
        return $this;
    }

    public function getAnonymousTokenExpirationDate()
    {
        return $this->_anonymousTokenExpirationDate;
    }

    /**
     * Set the credential token expiration date, useful in case of data coming from saved DB
     *
     * @param mixed $tokenCredentialExpirationDate
     * @return $this
     */
    public function setTokenCredentialExpirationDate($tokenCredentialExpirationDate)
    {
        $this->_tokenCredentialExpirationDate = $tokenCredentialExpirationDate;
        return $this;
    }

    /**
     * Get the credential token expiration date
     *
     * @return string
     */
    public function getTokenCredentialExpirationDate()
    {
        return $this->_tokenCredentialExpirationDate;
    }

    /**
     * Set the temporary token expiration date, useful in case of data coming from saved DB
     *
     * @param mixed $temporaryTokenExpirationDate
     * @return $this
     */
    public function setTemporaryTokenExpirationDate($temporaryTokenExpirationDate)
    {
        $this->_temporaryTokenExpirationDate = $temporaryTokenExpirationDate;
        return $this;
    }

    /**
     * Get the temporary token expiration date
     *
     * @return string
     */
    public function getTemporaryTokenExpirationDate()
    {
        return $this->_temporaryTokenExpirationDate;
    }

    /**
     * Set the antiforgery token expiration date, useful in case of data coming from saved DB
     *
     * @param string $antiforgeryTokenExpirationDate
     */
    public function setAntiforgeryTokenExpirationDate($antiforgeryTokenExpirationDate)
    {
        $this->_antiforgeryTokenExpirationDate = $antiforgeryTokenExpirationDate;
    }

    /**
     * Get the antiforgery token expiration date
     *
     * @return string
     */
    public function getAntiforgeryTokenExpirationDate()
    {
        return $this->_antiforgeryTokenExpirationDate;
    }

    /**
     * @param int $tokenCredentialSessionDuration
     */
    public function setTokenCredentialSessionDuration($tokenCredentialSessionDuration)
    {
        $this->_tokenCredentialSessionDuration = $tokenCredentialSessionDuration;
    }

    /**
     * @return int
     */
    public function getTokenCredentialSessionDuration()
    {
        return $this->_tokenCredentialSessionDuration;
    }

    /**
     * @param int $tokenCredentialSessionStart
     */
    public function setTokenCredentialSessionStart($tokenCredentialSessionStart)
    {
        $this->_tokenCredentialSessionStart = $tokenCredentialSessionStart;
    }

    /**
     * @return int
     */
    public function getTokenCredentialSessionStart()
    {
        return $this->_tokenCredentialSessionStart;
    }

    /**
     * Set the validation url, useful in case of data coming from saved DB
     *
     * @param null $validationUrl
     * @return $this
     */
    public function setValidationUrl($validationUrl)
    {
        $this->_validationUrl = $validationUrl;
        return $this;
    }

    /**
     * Get the validation Url
     *
     * @return string
     */
    public function getValidationUrl()
    {
        return $this->_validationUrl;
    }

    /**
     * The simulation of the authorization process is for development purpose
     *
     * @param string $url
     * @return bool|mixed
     * @throws \Exception
     */
    public function simulateValidationUrl($url = '')
    {
        // Example: https://www.ch.betaqxl.com/apiconnect/login/index?token=XXXXX-XXXX-XXXX-XXXX-XXXXXXX&countryId=2&partnershipId=XXXX&partnerurl=
        if (empty($url)) {
            $url = $this->getValidationUrl();
        }

        if (empty($url)) {
            return false;
        }

        // Step 1 - Call the login page to init the cookies
        $cookieFile = tempnam('/tmp', 'Cookiefile');

        $curlOptions = array(
            CURLOPT_URL => $url,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_COOKIEJAR => $cookieFile
        );

        $ch = curl_init();
        curl_setopt_array($ch, $curlOptions);
        curl_exec($ch);

        if (curl_errno($ch)) {
            throw new \Exception ('Error to get the login form page to save Ricardo Authorization: ' . curl_errno($ch));
        }

        // Step 2 - Send the user authentification to the web form to get the rights to use the credential token
        // Dismantle the validation url to get the host and the parameters
        $parsedUrl = parse_url($url);

        $query = null;
        if ($parsedUrl) {
            // Create the post action form url
            if ($parsedUrl['scheme'] == 'http') {
                $parsedUrl['scheme'] = 'https';
            }
            $url = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . SecurityService::VALIDATION_SAVE_PATH;
            parse_str($parsedUrl['query'], $query);
        }

        $params = array(
            'CustomerNick' => $this->_serviceManager->getConfig()->getCustomerUsername(),
            'CustomerPassword' => $this->_serviceManager->getConfig()->getCustomerPassword(),
            'Token' => ($query['token']) ? $query['token'] : '',
            'PartnerUrl' => ($query['partnerurl']) ? $query['partnerurl'] : '',
            'CountryId' => ($query['countryId']) ? $query['countryId'] : ''
        );

        $curlOptions = array(
            CURLOPT_URL => $url,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => http_build_query($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_COOKIEFILE => $cookieFile
        );

        curl_setopt_array($ch, $curlOptions);
        $return = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new \Exception ('Error while saving the form into Ricardo Authorization page: ' . curl_errno($ch));
        }

        curl_close($ch);

        return json_decode($return, true);
    }


}
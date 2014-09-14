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

use Diglin\Ricardo\Exceptions\SecurityException;
use Diglin\Ricardo\Enums\SecurityErrors as SecurityErrorsEnum;
use Diglin\Ricardo\Services\Security as SecurityService;
use Diglin\Ricardo\Services\ServiceAbstract;
use Diglin\Ricardo\Service;
use Diglin\Ricardo\Core\Helper;

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
    protected $_credentialToken;

    /**
     * @var string
     */
    protected $_credentialTokenExpirationDate;

    /**
     * @var int
     */
    protected $_credentialTokenSessionDuration;

    /**
     * @var int
     */
    protected $_credentialTokenSessionStart;

    /**
     * @var bool
     */
    protected $_credentialTokenRefreshed = false;

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
        switch ($typeOfToken) {
            case ServiceAbstract::TOKEN_TYPE_IDENTIFIED:
                return $this->getCredentialToken();
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
            $this->_anonymousTokenExpirationDate = Helper::getJsonTimestamp($result['TokenExpirationDate']);
            //$this->_anonymousTokenSessionDuration = $result['SessionDuration'];
            $this->_anonymousToken = $result['TokenCredentialKey'];
            return $this->_anonymousToken;
        }

        return $result;
    }

    /**
     * Get the temporary token, set internally the expiration date for this temporary token and the validation url
     *
     * @param bool $refresh
     * @return string|array
     */
    public function getTemporaryToken($refresh = false)
    {
        if ($this->_temporaryToken && !$this->isDateExpired($this->_temporaryTokenExpirationDate) && !$refresh) {
            return $this->_temporaryToken;
        }

        $result = $this->_proceed('TemporaryCredential');

        if (isset($result['ExpirationDate']) && isset($result['TemporaryCredentialKey'])) {
            $this->_temporaryTokenExpirationDate = Helper::getJsonTimestamp($result['ExpirationDate']);
            $this->_temporaryToken = $result['TemporaryCredentialKey'];
            $this->_validationUrl = $this->parseValidationUrl($result['ValidationUrl']);
            return $this->_temporaryToken;
        }

        return $result;
    }

    /**
     * Example: https://www.ch.betaqxl.com/apiconnect/login/index?token=XXXXX-XXXX-XXXX-XXXX-XXXXXXX&countryId=2&partnershipId=XXXX&partnerurl=
     *
     * @param string $url
     * @return string
     */
    public function parseValidationUrl($url)
    {
        $parsed_url = parse_url($url);
        $outQuery = array();

        if (isset($parsed_url['query'])) {
            $query = explode('&', $parsed_url['query']);
            foreach ($query as &$item) {
                list($key, $value) = explode('=', $item);
                if ($key == 'partnerurl') {
                    $value = $this->_serviceManager->getConfig()->getPartnerUrl();
                }
                $outQuery[$key] = $value;
            }
            $parsed_url['query'] = http_build_query($outQuery);
        }

        $scheme = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
        $host = isset($parsed_url['host']) ? $parsed_url['host'] : '';
        $port = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
        $user = isset($parsed_url['user']) ? $parsed_url['user'] : '';
        $pass = isset($parsed_url['pass']) ? ':' . $parsed_url['pass'] : '';
        $pass = ($user || $pass) ? "$pass@" : '';
        $path = isset($parsed_url['path']) ? $parsed_url['path'] : '';
        $query = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
        $fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';
        return "$scheme$user$pass$host$port$path$query$fragment";
    }

    /**
     * Get the token credential, set internally expiration date and session duration
     * Refresh the token if necessary
     *
     * @return string|array
     * @throws SecurityException
     */
    public function getCredentialToken()
    {
        if ($this->_credentialToken && !$this->isDateExpired($this->_credentialTokenExpirationDate)) {
            return $this->_credentialToken;
        }

        /**
         * Temporary Token must be created before to simulate the authorization or getting the token credential
         * MUST get the returned variable in case of DB saved or Authorization process has been done
         */
        $temporaryToken = $this->getTemporaryToken();

        if ($this->_allowSimulateAuthorization) {
            $this->getTemporaryToken();
            $this->simulateValidationUrl();
        }

        $result = $this->_proceed('TokenCredential', $temporaryToken);

        if (isset($result['TokenExpirationDate']) && isset($result['TokenCredentialKey'])) {
            $this->_credentialTokenExpirationDate = Helper::getJsonTimestamp($result['TokenExpirationDate']);
            $this->_credentialTokenSessionDuration = $result['SessionDuration']; // in minutes
            $this->_credentialTokenSessionStart = time(); // in seconds
            $this->_temporaryToken = null;
            return $this->_credentialToken = $result['TokenCredentialKey'];
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
            $this->_antiforgeryTokenExpirationDate = Helper::getJsonTimestamp($result['TokenExpirationDate']);
            return $this->_antiforgeryToken = $result['AntiforgeryTokenKey'];
        }

        return $result;
    }

    /**
     * If the session duration period has expired, the token must be refreshed
     *
     * @param string|null $token
     * @return string | array
     */
    public function refreshToken($token = null)
    {
        if (is_null($token)) {
            $token = $this->_credentialToken;
        }

        $result = $this->_proceed('RefreshTokenCredential', $token);

        if (isset($result['TokenCredentialKey'])) {
            $this->_credentialTokenRefreshed = true;
            $this->_credentialTokenSessionStart = time();
            return $this->_credentialToken = $result['TokenCredentialKey'];
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
    public function setCredentialToken($tokenCredential)
    {
        $this->_credentialToken = $tokenCredential;
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
        $this->_allowSimulateAuthorization = (bool)$allow;
    }

    /**
     * Get if allow or not to simulate the authorization process
     *
     * @return bool
     */
    public function getAllowSimulateAuthorization()
    {
        return (bool)$this->_allowSimulateAuthorization;
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
    public function setCredentialTokenExpirationDate($tokenCredentialExpirationDate)
    {
        $this->_credentialTokenExpirationDate = $tokenCredentialExpirationDate;
        return $this;
    }

    /**
     * Get the credential token expiration date
     *
     * @return string
     */
    public function getCredentialTokenExpirationDate()
    {
        return $this->_credentialTokenExpirationDate;
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
     * @return $this
     */
    public function setAntiforgeryTokenExpirationDate($antiforgeryTokenExpirationDate)
    {
        $this->_antiforgeryTokenExpirationDate = $antiforgeryTokenExpirationDate;
        return $this;
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
     * @return $this
     */
    public function setCredentialTokenSessionDuration($tokenCredentialSessionDuration)
    {
        $this->_credentialTokenSessionDuration = $tokenCredentialSessionDuration;
        return $this;
    }

    /**
     * @return int
     */
    public function getCredentialTokenSessionDuration()
    {
        return $this->_credentialTokenSessionDuration;
    }

    /**
     * @param int $tokenCredentialSessionStart
     * @return $this
     */
    public function setCredentialTokenSessionStart($tokenCredentialSessionStart)
    {
        $this->_credentialTokenSessionStart = $tokenCredentialSessionStart;
        return $this;
    }

    /**
     * @return int
     */
    public function getCredentialTokenSessionStart()
    {
        return $this->_credentialTokenSessionStart;
    }

    /**
     * Get the validation Url
     *
     * @param bool $refresh
     * @return string
     */
    public function getValidationUrl($refresh = false)
    {
        if (empty($this->_validationUrl) || $refresh) {
            $this->getTemporaryToken($refresh);
        }
        return $this->_validationUrl;
    }

    /**
     * @param boolean $credentialTokenRefreshed
     * @return $this
     */
    public function setIsCredentialTokenRefreshed($credentialTokenRefreshed)
    {
        $this->_credentialTokenRefreshed = $credentialTokenRefreshed;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsCredentialTokenRefreshed()
    {
        return (bool) $this->_credentialTokenRefreshed;
    }


}
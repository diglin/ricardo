<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @category    magento
 * @package     Diglin_
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
namespace Diglin\Ricardo;

use \Diglin\Ricardo\ServiceManager;
use \Diglin\Ricardo\Services\ServiceAbstract;

/**
 * Class SecurityManager
 *
 * Do the bridge with the Security service class and its data
 *
 * @package Diglin\Ricardo
 */
class SecurityManager
{
    protected $_serviceManager;

    protected $_temporaryToken;

    protected $_temporaryTokenExpirationDate;

    protected $_validationUrl = null;

    protected $_anonymousToken;

    protected $_anonymousTokenExpirationDate;

    //protected $_anonymousTokenSessionDuration;

    protected $_credentialToken;

    protected $_credentialTokenExpirationDate;

    protected $_credentialTokenSessionDuration;

    public function __construct(ServiceManager $serviceManager)
    {
        $this->_serviceManager = $serviceManager;
    }

    /**
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
            case ServiceAbstract::TOKEN_TYPE_DEFAULT:
                break;
        }
    }

    /**
     * Get the anonymous token and set internally the expiration date for this anonymous token
     *
     * @return string|boolean
     */
    public function getAnonymousToken()
    {
        if (!empty($this->_anonymousTokenExpirationDate) && !$this->isDateExpired($this->_anonymousTokenExpirationDate)) {
            return $this->_anonymousToken;
        }

        $result = $this->_serviceManager->proceed('security', 'AnonymousTokenCredential');

        if (isset($result['TokenExpirationDate']) && isset($result['TokenCredentialKey'])) {
            $this->_anonymousTokenExpirationDate = $result['TokenExpirationDate'];
            //$this->_anonymousTokenSessionDuration = $result['SessionDuration'];
            $this->_anonymousToken = $result['TokenCredentialKey'];
            return $this->_anonymousToken;
        }

        return false;
    }

    /**
     * Get the temporary token, set internally the expiration date for this temporary token and the validation url
     *
     * @return string|boolean
     */
    public function getTemporaryToken()
    {
        if (!empty($this->_temporaryToken) && !$this->isDateExpired($this->_temporaryTokenExpirationDate)) {
            return $this->_temporaryToken;
        }

        $result = $this->_serviceManager->proceed('security', 'TemporaryCredential');

        if (isset($result['ExpirationDate']) && isset($result['TemporaryCredentialKey'])) {
            $this->_temporaryTokenExpirationDate = $result['ExpirationDate'];
            $this->_temporaryToken = $result['TemporaryCredentialKey'];
            $this->_validationUrl = $result['ValidationUrl'];
            return $this->_temporaryToken;
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function getTokenCredential()
    {
        $temporaryCredential = $this->getTemporaryToken();
        //@todo take care of validation url

        $result = $this->_serviceManager->proceed('security', 'TokenCredential', $temporaryCredential);

        if (isset($result['ExpirationDate']) && isset($result['TemporaryCredentialKey'])) {
            $this->_credentialTokenExpirationDate = $result['ExpirationDate'];
            $this->_credentialTokenSessionDuration = $result['SessionDuration'];
            $this->_credentialToken = $result['TemporaryCredentialKey'];
            return $this->_credentialToken;
        }

        return false;
    }

    /**
     * Json Date coming from Ricardo API
     * are formatted as the following '/Date(1400795100000+0200)/'
     * We convert in a "PHP way" per default 'Y-m-d H:i:s'
     *
     * @param string $date
     * @param string|null $format
     * @return string | DateTime
     */
    protected function _convertJsonDate($date, $format = 'Y-m-d H:i:s')
    {
        //$date = '/Date(1400795100000+0200)/';
        preg_match('/(\d{10})(\d{3})([\+\-]\d{4})/', $date, $matches);

        // Get the timestamp as the TS string
        $timestamp = (int) $matches[1];

        // Get the timezone name by offset
        $timezone = (int) $matches[3];
        $timezone = timezone_name_from_abbr("", $timezone / 100 * 3600, false);
        $timezone = new DateTimeZone($timezone);

        // Create a new DateTime, set the timestamp and the timezone
        $datetime = new DateTime();
        $datetime->setTimestamp($timestamp);
        $datetime->setTimezone($timezone);

        if (is_null($format)) {
            return $datetime;
        }

        return $datetime->format($format);
    }

    /**
     * Check if the date in parameter is expired or not
     * The parameter must be a json date
     *
     * @param string $jsonExpirationDate
     * @return bool
     */
    public function isDateExpired($jsonExpirationDate)
    {
        $datetime = $this->_convertJsonDate($jsonExpirationDate, null);
        $jsonDateTimestamp = $datetime->getTimestamp();

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

    /**
     * Set the credential token, useful in case of data coming from saved DB
     *
     * @param mixed $credentialToken
     * @return $this
     */
    public function setCredentialToken($credentialToken)
    {
        $this->_credentialToken = $credentialToken;
        return $this;
    }

    /**
     * Set the credential token expiration date, useful in case of data coming from saved DB
     *
     * @param mixed $credentialTokenExpirationDate
     * @return $this
     */
    public function setCredentialTokenExpirationDate($credentialTokenExpirationDate)
    {
        $this->_credentialTokenExpirationDate = $credentialTokenExpirationDate;
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
     * @return string
     */
    public function getValidationUrl()
    {
        return $this->_validationUrl;
    }
}
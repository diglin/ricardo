<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
namespace Diglin\Ricardo;

use \Diglin\Ricardo\Core\ApiInterface;
use \Diglin\Ricardo\Core\ConfigInterface;

/**
 * Class Api
 *
 * Prepare HTTP headers, send params, connect and get response from the API
 *
 * @package Diglin\Ricardo
 */
class Api implements ApiInterface
{
    protected $_config;

    protected $_username;

    protected $_partnerId;

    protected $_debug = false;

    protected $_lastDebug = array();

    protected $_shouldSetPass = true;

    public function __construct(ConfigInterface $config)
    {
        $this->_config = $config;
        $this->_username = $this->_partnerId = $config->getPartnershipId();

        if ($config->get('debug')) {
            $this->_debug = $config->get('debug');
        }
    }

    /**
     * @param string $service Ricardo API Service
     * @param string $method Ricardo API Method
     * @param array $params API Parameters
     * @return mixed $result
     */
    public function connect($service, $method, array $params)
    {
        //@todo Error Management for config host

        $curlOptions = array(
            CURLOPT_URL => 'https://' . $this->getConfig()->getHost() . '/ricardoapi/' . $service . '.Json.svc/' . $method,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => json_encode($params),
            CURLOPT_HTTPHEADER => $this->_addHeaders(),
            CURLOPT_RETURNTRANSFER => true
        );

        $ch = curl_init();
        curl_setopt_array($ch, $curlOptions);
        $return = curl_exec($ch);
        $result = json_decode($return, true);
        curl_close($ch);

        if ($this->_debug) {
            $this->_lastDebug[] = array(
                'curl_options' => $curlOptions,
                'return' => $return
            );
        }

        //@todo Error Management for curl and API response

        return $result;
    }

    /**
     * Set correct HTTP headers for the API
     *
     * @return array
     */
    protected function _addHeaders()
    {
        $headers = array(
            'Content-Type: application/json',
            'Host: ' . $this->getConfig()->getHost(),
            'Ricardo-Username: ' . $this->getUsername()
        );

        if ($this->getShouldSetPass()) {
            $headers[] = 'Ricardo-Password: ' . $this->getConfig()->getPartnershipPasswd();
        }

        return $headers;
    }

    /**
     * Get the configuration class of the API
     *
     * @return \Diglin\Ricardo\Config
     */
    public function getConfig()
    {
        return $this->_config;
    }

    /**
     * Username is in fact the API token but we call it username
     * to be homogeneous with the header naming convention
     *
     * @param mixed $username
     * @return $this
     */
    public function setUsername($username)
    {
        $this->_username = $username;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->_username;
    }

    /**
     * @param boolean $boolean
     * @return $this
     */
    public function setShouldSetPass($boolean)
    {
        $this->_shouldSetPass = $boolean;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getShouldSetPass()
    {
        return $this->_shouldSetPass;
    }

    /**
     * @return mixed
     */
    public function getLastDebug()
    {
        return $this->_lastDebug;
    }

    /**
     * The username can be a token or the partner ID.
     * In some cases it is needed to get back the partner ID
     *
     * @return string
     */
    public function revertUsername()
    {
        return ($this->_username = $this->_partnerId);
    }
}
<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

use \Diglin\Ricardo\Api;
use \Diglin\Ricardo\Config;
use \Diglin\Ricardo\Service;
use \Diglin\Ricardo\Services\ServiceAbstract;

/**
 * Class Diglin_Ricento_Model_Api_Services_Abstract
 */
abstract class Diglin_Ricento_Model_Api_Services_Abstract extends Varien_Object
{
    /**
     * @var string
     */
    private $_registryKey = 'serviceManager';

    /**
     * @var string
     */
    protected $_serviceName = '';

    /**
     * @var string
     */
    protected $_model = '';

    /**
     * @var int
     */
    protected $_currentWebsite = 0;

    /**
     * @var string
     */
    protected $_profilerPrefix = 'RICARDO_API_';

    /**
     * @var bool
     */
    protected $_canUseCache = true;

    /**
     * @param int|Mage_Core_Model_Website $currentWebsite
     * @return $this
     */
    public function setCurrentWebsite($currentWebsite)
    {
        $this->_currentWebsite = $currentWebsite;
        return $this;
    }

    /**
     * @return Mage_Core_Model_Website
     */
    public function getCurrentWebsite()
    {
        $registryProductListing = Mage::registry('products_listing');
        if ($registryProductListing instanceof Diglin_Ricento_Model_Products_Listing && $registryProductListing->getWebsiteId()) {
            $this->_currentWebsite = $registryProductListing->getWebsiteId();
        }
        return Mage::app()->getWebsite($this->_currentWebsite);
    }

    /**
     * @return ServiceAbstract
     */
    public function getServiceModel()
    {
        $key = $this->_registryKey . $this->_serviceName . $this->getCurrentWebsite()->getId();

        if (!Mage::registry($key)) {
            if (!class_exists($this->_model)) {
                Mage::throwException(Mage::helper('diglin_ricento')->__('Ricardo Service Model doesn\'t exists.'));
            }
            Mage::register($key, new $this->_model($this->getServiceManager()));
        }

        return Mage::registry($key);
    }

    /**
     * Get the Service Manager of the Ricardo PHP lib
     *
     * @return Service
     */
    public function getServiceManager()
    {
        if (!Mage::registry('ricardo_api_lang')) {
            Mage::register('ricardo_api_lang', Diglin_Ricento_Helper_Data::DEFAULT_SUPPORTED_LANG);
        }

        $website = $this->getCurrentWebsite();

        $lang = Mage::registry('ricardo_api_lang');
        $registryKey = $this->_registryKey . ucwords($lang) . $website->getId();

        if (!Mage::registry($registryKey)) {
            $helper = Mage::helper('diglin_ricento');

            if (!$helper->isConfigured($website)) {
                Mage::throwException($helper->__('Ricardo API Credentials are not configured. Please, configure the extension before to proceed.'));
            }

            if (!in_array($lang, $helper->getSupportedLang())) {
                Mage::throwException($helper->__('API language provided for the Service Manager is not supported.'));
            }

            if ($helper->isDevMode($website)) {
                $host = Mage::getStoreConfig(Diglin_Ricento_Helper_Data::CFG_API_HOST_DEV);
            } else {
                $host = Mage::getStoreConfig(Diglin_Ricento_Helper_Data::CFG_API_HOST);
            }

            $config = array(
                'host' => $host,
                'partnership_id' => $helper->getPartnerId($lang, $website),
                'partnership_passwd' => $helper->getPartnerPass($lang, $website),
                'partner_url' => $helper->getPartnerUrl($website->getId()),
                'allow_authorization_simulation' => ($helper->canSimulateAuthorization()) ? true : false,
                'customer_username' => $helper->getRicardoUsername($website),
                'customer_password' => $helper->getRicardoPass($website),
                'debug' => ($helper->isDebugEnabled($website)) ? true : false
            );

            Mage::register($registryKey, new Service(new Api(new Config($config))), false);
        }

        return Mage::registry($registryKey);
    }

    /**
     * Get last API call information
     *
     * @param bool $flush
     * @return array
     */
    public function getLastApiDebug($flush = true)
    {
        return $this->getServiceManager()
            ->getApi()
            ->getLastDebug($flush);
    }


    /**
     * Magic method to getter/setter methods from Ricardo API library, save in cache the getter
     *
     * @param string $method
     * @param array $args
     * @return mixed|Varien_Object
     * @throws \Diglin\Ricardo\Exceptions\ExceptionAbstract
     * @throws Diglin_Ricento_Exception
     */
    public function __call($method, $args)
    {
        $data = array();
        $key = $this->_underscore(substr($method,3));
        $profilerName = $this->_profilerPrefix . strtoupper($key);
        $arguments = array(
            'service_adapter' => $this,
            'service_model' => $this->getServiceModel(),
            'method' => $method,
            'parameters' => $args
        );

        try {
            switch (substr($method, 0, 3)) {
                case 'get' :
                    if (method_exists($this->getServiceModel(), $method) && is_callable(array($this->getServiceModel(), $method), true)) {

                        Varien_Profiler::start($profilerName);
                        Mage::dispatchEvent('ricardo_api_init_call', $arguments);

                        $this->_prepareCredentialToken();

                        if ($this->getCanUseCache()) {
                            $data = unserialize(Mage::app()->loadCache($key));
                        }

                        Mage::dispatchEvent('ricardo_api_call_get_before', $arguments);
                        if (empty($data) || !$this->getCanUseCache()) {
                            $data = call_user_func_array(array($this->getServiceModel(), $method), $args);
                        }
                        Mage::dispatchEvent('ricardo_api_call_get_after', array_merge($arguments, array('data' => $data)));

                        $this->setData($key, $data);

                        if ($this->getCanUseCache()) {
                            Mage::app()->saveCache(serialize($data), $key, array(Diglin_Ricento_Helper_Api::CACHE_TAG), Diglin_Ricento_Helper_Api::CACHE_LIFETIME);
                        }

                        $this->_updateCredentialToken();

                        Mage::dispatchEvent('ricardo_api_end_call', array_merge($arguments, array('data' => $data)));
                        Varien_Profiler::stop($profilerName);

                        return $data;
                    }
                    break;
                case 'set':
                    if (method_exists($this->getServiceModel(), $method) && is_callable(array($this->getServiceModel(), $method), true)) {

                        Varien_Profiler::start($profilerName);
                        Mage::dispatchEvent('ricardo_api_init_call', $arguments);

                        $this->_prepareCredentialToken();

                        Mage::dispatchEvent('ricardo_api_call_set_before', $arguments);
                        call_user_func_array(array($this->getServiceModel(), $method), $args);
                        Mage::dispatchEvent('ricardo_api_call_set_after', $arguments);

                        $this->_updateCredentialToken();

                        Mage::dispatchEvent('ricardo_api_end_call', array_merge($arguments, array('data' => $data)));
                        Varien_Profiler::stop($profilerName);

                        return $this;
                    }
                    break;
            }
        } catch (\Diglin\Ricardo\Exceptions\ExceptionAbstract $e) {
            Mage::logException($e);

            $ricentoException = new Diglin_Ricento_Exception($e->getMessage(), $e->getCode());

            if ($e->getCode() == \Diglin\Ricardo\Enums\SecurityErrors::TOKEN_AUTHORIZATION) {
                $ricentoException->setNeedAuthorization(true);
                $ricentoException->setValidationUrl($this->getServiceManager()->getSecurityManager()->getValidationUrl());
                $this->_cleanupCredentialToken();
                throw $ricentoException;
            }
            throw $e;
        }

        Varien_Profiler::stop($profilerName);

        return parent::__call($method, $args);
    }

    /**
     * Set if the cache is allow to be used
     *
     * @param boolean $canUseCache
     * @return $this
     */
    public function setCanUseCache($canUseCache)
    {
        $this->_canUseCache = (bool) $canUseCache;
        return $this;
    }

    /**
     * Allowed to use the cache or not
     *
     * @return boolean
     */
    public function getCanUseCache()
    {
        return (Mage::app()->useCache(Diglin_Ricento_Helper_Api::CACHE_TYPE) && $this->_canUseCache);
    }

    /**
     * Set the token credential from the DB before to execute the API call
     *
     * @return $this
     */
    protected function _prepareCredentialToken()
    {
        /* @var $serviceModel \Diglin\Ricardo\Managers\ManagerAbstract */
        $typeOfToken = $this->getServiceModel()->getTypeOfToken();

        if ($typeOfToken == ServiceAbstract::TOKEN_TYPE_IDENTIFIED) {

            $security = Mage::getSingleton('diglin_ricento/api_services_security');

            $apiToken = Mage::getModel('diglin_ricento/api_token')
                ->loadByWebsiteAndTokenType(ServiceAbstract::TOKEN_TYPE_IDENTIFIED, $this->getCurrentWebsite()->getId());

            if ($apiToken->getId()) {
                $security->getServiceModel()
                    ->setCredentialToken($apiToken->getToken())
                    ->setCredentialTokenExpirationDate(strtotime($apiToken->getExpirationDate()))
                    ->setCredentialTokenSessionDuration($apiToken->getSessionDuration())
                    ->setCredentialTokenSessionStart(Mage::helper('diglin_ricento/api')->calculateSessionStart($apiToken->getSessionDuration(), $apiToken->getExpirationDate()));
            }
        }
        return $this;
    }

    /**
     * Update the token credential in the database if needed after API call
     *
     * @return $this
     */
    protected function _updateCredentialToken()
    {
        /* @var $serviceModel \Diglin\Ricardo\Managers\ManagerAbstract */
        $typeOfToken = $this->getServiceModel()->getTypeOfToken();

        // we want to skip the use of __call to prevent loop, we use getServiceModel()
        $security = Mage::getSingleton('diglin_ricento/api_services_security')->getServiceModel();

        $isTokenRefreshed = $security->getIsCredentialTokenRefreshed();

        if ($typeOfToken == ServiceAbstract::TOKEN_TYPE_IDENTIFIED && $isTokenRefreshed) {

            $apiToken = Mage::getModel('diglin_ricento/api_token')
                ->loadByWebsiteAndTokenType(ServiceAbstract::TOKEN_TYPE_IDENTIFIED, $this->getCurrentWebsite()->getId());

            $apiToken
                ->setToken($security->getCredentialToken())
                ->setTokenType(ServiceAbstract::TOKEN_TYPE_IDENTIFIED)
                ->setSessionExpirationDate(
                    Mage::helper('diglin_ricento/api')->calculateSessionExpirationDate($security->getTokenCredentialSessionDuration(), $security->getTokenCredentialSessionStart())
                )
                ->save();
        }

        return $this;
    }

    /**
     * Delete a specific token from the database
     *
     * @return $this
     */
    protected function _cleanupCredentialToken()
    {
        $token = Mage::getModel('diglin_ricento/api_token')
            ->loadByWebsiteAndTokenType(ServiceAbstract::TOKEN_TYPE_IDENTIFIED, $this->getCurrentWebsite()->getId());

        if ($token->getId()) {
            $token->delete();
        }

        $security = Mage::getSingleton('diglin_ricento/api_services_security');
        Mage::getModel('diglin_ricento/api_token')
            ->loadByWebsiteAndTokenType(ServiceAbstract::TOKEN_TYPE_TEMPORARY, $this->getCurrentWebsite()->getId())
            ->setToken($security->getTemporaryToken())
            ->setWebsiteId($this->getCurrentWebsite()->getId())
            ->setTokenType(ServiceAbstract::TOKEN_TYPE_TEMPORARY)
            ->setExpirationDate(
                    Mage::helper('diglin_ricento/api')->calculateSessionExpirationDate($security->getTokenCredentialSessionDuration(), $security->getTokenCredentialSessionStart())
                )
            ->save();


        return $this;
    }
}
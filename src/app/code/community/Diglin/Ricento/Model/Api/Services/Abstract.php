<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author      Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

use \Diglin\Ricardo\Api;
use \Diglin\Ricardo\Config;
use \Diglin\Ricardo\Service;
use \Diglin\Ricardo\Services\ServiceAbstract;

/**
 * Class Diglin_Ricento_Model_Api_Services_Abstract
 *
 * @method Diglin_Ricento_Model_Api_Services_Abstract setCacheLifetime(int $lifetime)
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
    protected $_currentWebsite = Mage_Core_Model_App::ADMIN_STORE_ID;

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
        $helper = Mage::helper('diglin_ricento');

        if (!Mage::registry('ricardo_api_lang')) {
            Mage::register('ricardo_api_lang', $helper->getDefaultSupportedLang());
        }

        $website = $this->getCurrentWebsite();

        $lang = Mage::registry('ricardo_api_lang');
        $registryKey = $this->_registryKey . ucwords($lang) . $website->getId();

        if (!Mage::registry($registryKey)) {

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
                'partnership_key' => $helper->getPartnerKey($lang, $website),
                'partnership_passwd' => $helper->getPartnerPass($lang, $website),
                'partner_url' => $helper->getPartnerUrl($website->getId()),
                'allow_authorization_simulation' => ($helper->canSimulateAuthorization()) ? true : false,
                'customer_username' => $helper->getRicardoUsername($website),
                'customer_password' => $helper->getRicardoPass($website),
                'debug' => ($helper->isDebugEnabled($website)) ? true : false,
                'log_path' => Mage::getBaseDir('var') . DS . 'log' . DS . Diglin_Ricento_Helper_Data::LOG_FILE
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
     * @throws Exception
     */
    public function __call($method, $args)
    {
        $data = array();
        $helper = Mage::helper('diglin_ricento');
        $key = $this->_underscore(substr($method,3));
        $cacheKey = $key . Mage::registry('ricardo_api_lang');
        $profilerName = $this->_profilerPrefix . strtoupper($key);

        try {
            switch (substr($method, 0, 3)) {
                case 'get' :
                    if (method_exists($this->getServiceModel(), $method) && is_callable(array($this->getServiceModel(), $method), true)) {

                        Varien_Profiler::start($profilerName);

                        $this->_prepareCredentialToken();

                        if ($this->getCanUseCache()) {
                            $data = unserialize(Mage::app()->loadCache($cacheKey));
                        }

                        if (empty($data) || !$this->getCanUseCache() || array_key_exists('ErrorCodes', $data)) {
                            $data = call_user_func_array(array($this->getServiceModel(), $method), $args);
                        }

                        $this->setData($key, $data);

                        if ($this->getCanUseCache() && !array_key_exists('ErrorCodes', $data)) {
                            Mage::app()->saveCache(serialize($data), $cacheKey, array(Diglin_Ricento_Helper_Api::CACHE_TAG), $this->getCacheLifeTime());
                        }

                        $this->_updateCredentialToken();

                        Varien_Profiler::stop($profilerName);

                        return $data;
                    }
                    break;
                case 'set':
                    if (method_exists($this->getServiceModel(), $method) && is_callable(array($this->getServiceModel(), $method), true)) {

                        Varien_Profiler::start($profilerName);

                        $this->_prepareCredentialToken();

                        call_user_func_array(array($this->getServiceModel(), $method), $args);

                        $this->_updateCredentialToken();

                        Varien_Profiler::stop($profilerName);

                        return $this;
                    }
                    break;
                default:
                    if (method_exists($this->getServiceModel(), $method) && is_callable(array($this->getServiceModel(), $method), true)) {

                        Varien_Profiler::start($profilerName);

                        $this->_prepareCredentialToken();

                        $data = call_user_func_array(array($this->getServiceModel(), $method), $args);

                        $this->_updateCredentialToken();

                        Varien_Profiler::stop($profilerName);

                        return $data;
                    }
                    break;
            }
        } catch (\Diglin\Ricardo\Exceptions\CurlException $e) {
            Mage::logException($e);
            throw new Exception($helper->__('Error while trying to connect to the Ricardo API. Please, check your log files.'));
        } catch (\Diglin\Ricardo\Exceptions\ExceptionAbstract $e) {
            $this->_updateCredentialToken();
            $this->_handleSecurityException($e);
        }

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
     * @return int|mixed
     */
    public function getCacheLifeTime()
    {
        if ($this->getData('cache_lifetime')) {
            return $this->getData('cache_lifetime');
        }

        return Diglin_Ricento_Helper_Api::CACHE_LIFETIME;
    }

    /**
     * Set the token credential from the DB before to execute the API call
     *
     * @return $this
     */
    protected function _prepareCredentialToken()
    {
        /* @var $serviceModel \Diglin\Ricardo\Managers\ManagerAbstract */
        $typeOfToken = self::getServiceModel()->getTypeOfToken();

        if ($typeOfToken == ServiceAbstract::TOKEN_TYPE_IDENTIFIED) {

            $security = Mage::getSingleton('diglin_ricento/api_services_security')
                ->setCurrentWebsite($this->getCurrentWebsite()->getId());

            $apiToken = Mage::getModel('diglin_ricento/api_token')
                ->loadByWebsiteAndTokenType(ServiceAbstract::TOKEN_TYPE_IDENTIFIED, $this->getCurrentWebsite()->getId());

            if ($apiToken->getId()) {
                $security->getServiceModel()
                    ->setCredentialToken($apiToken->getToken())
                    ->setCredentialTokenExpirationDate(strtotime($apiToken->getExpirationDate()))
                    ->setCredentialTokenSessionDuration($apiToken->getSessionDuration())
                    ->setCredentialTokenSessionStart(Mage::helper('diglin_ricento/api')
                        ->calculateSessionStart($apiToken->getSessionDuration(), $apiToken->getSessionExpirationDate()));
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
        $typeOfToken = @self::getServiceModel()->getTypeOfToken();

        // we want to skip the use of magic __call to prevent loop, we use getServiceModel()
        $security = Mage::getSingleton('diglin_ricento/api_services_security')
            ->setCurrentWebsite($this->getCurrentWebsite()->getId())
            ->getServiceModel();

        if ($typeOfToken == ServiceAbstract::TOKEN_TYPE_IDENTIFIED && $security->getIsCredentialTokenRefreshed()) {

            $apiToken = Mage::getModel('diglin_ricento/api_token')
                ->loadByWebsiteAndTokenType(ServiceAbstract::TOKEN_TYPE_IDENTIFIED, $this->getCurrentWebsite()->getId());

            $apiToken
                ->setToken($security->getCredentialToken())
                ->setTokenType(ServiceAbstract::TOKEN_TYPE_IDENTIFIED)
                ->setSessionExpirationDate(
                    Mage::helper('diglin_ricento/api')
                        ->calculateSessionExpirationDate(
                            $security->getCredentialTokenSessionDuration(),
                            $security->getCredentialTokenSessionStart())
                )
                ->save();

            $security->setIsCredentialTokenRefreshed(false);
        }

        return $this;
    }

    /**
     * Delete the credential token from the database
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

        $security = Mage::getSingleton('diglin_ricento/api_services_security')
            ->setCurrentWebsite($this->getCurrentWebsite()->getId())
            ->getServiceModel();

        Mage::getModel('diglin_ricento/api_token')
            ->loadByWebsiteAndTokenType(ServiceAbstract::TOKEN_TYPE_TEMPORARY, $this->getCurrentWebsite()->getId())
            ->setToken($security->getTemporaryToken())
            ->setWebsiteId($this->getCurrentWebsite()->getId())
            ->setTokenType(ServiceAbstract::TOKEN_TYPE_TEMPORARY)
            ->setExpirationDate(
                    Mage::helper('diglin_ricento/api')->calculateSessionExpirationDate($security->getCredentialTokenSessionDuration(), $security->getCredentialTokenSessionStart())
                )
            ->save();

        return $this;
    }

    /**
     * @param Exception $e
     * @throws Exception
     * @throws Diglin_Ricento_Exception
     */
    protected function _handleSecurityException(Exception $e)
    {
        if ($e->getCode() == \Diglin\Ricardo\Enums\SecurityErrors::TOKEN_AUTHORIZATION) {

            if (Mage::helper('diglin_ricento')->isDebugEnabled()) {
                Mage::logException($e);
            }

            $ricentoException = new Diglin_Ricento_Exception($e->getMessage(), $e->getCode());
            $ricentoException->setNeedAuthorization(true);
            $ricentoException->setValidationUrl($this->getServiceManager()->getSecurityManager()->getValidationUrl());
            $this->_cleanupCredentialToken();
            throw $ricentoException;
        }
        throw $e;
    }
}
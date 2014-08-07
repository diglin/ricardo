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
     * @var string
     */
    protected $_profilerPrefix = 'RICARDO_API_';

    /**
     * @param int|Mage_Core_Model_Website $website
     * @return ServiceAbstract
     */
    public function getServiceModel($website = 0)
    {
        $websiteId = Mage::app()->getWebsite($website)->getId();
        $key = $this->_registryKey . $this->_serviceName . $websiteId;

        if (!Mage::registry($key)) {
            if (!class_exists($this->_model)) {
                Mage::throwException(Mage::helper('diglin_ricento')->__('Ricardo Service Model doesn\'t exists.'));
            }

            Mage::register($key, new $this->_model($this->getServiceManager($website)));
        }

        return Mage::registry($key);
    }

    /**
     * Get the Service Manager of the Ricardo PHP lib
     *
     * @param int $website
     * @return Service
     */
    public function getServiceManager($website = 0)
    {
        if (!Mage::registry('ricardo_api_lang')) {
            Mage::register('ricardo_api_lang', Diglin_Ricento_Helper_Data::DEFAULT_SUPPORTED_LANG);
        }

        $website = Mage::app()->getWebsite($website);
        $websiteId = $website->getId();

        $lang = Mage::registry('ricardo_api_lang');
        $registryKey = $this->_registryKey . ucwords($lang) . $websiteId;

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
                'partner_url' => $helper->getPartnerUrl($websiteId),
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
     * @param int $website
     * @param bool $flush
     * @return array
     */
    public function getLastApiDebug($website, $flush = true)
    {
        return $this->getServiceManager($website)->getApi()->getLastDebug($flush);
    }


    /**
     * Magic method to getter/setter methods from Ricardo API library, save in cache the getter
     *
     * @param string $method
     * @param array $args
     * @return mixed|Varien_Object
     */
    public function __call($method, $args)
    {
        switch (substr($method, 0, 3)) {
            case 'get' :
                if (method_exists($this->getServiceModel(), $method) && is_callable(array($this->getServiceModel(), $method), true)) {
                    $key = $this->_underscore(substr($method,3));
                    $profilerName = $this->_profilerPrefix . strtoupper($key);
                    $canUseCacheRicardoApi = Mage::app()->useCache(Diglin_Ricento_Helper_Api::CACHE_TYPE);

                    Varien_Profiler::start($profilerName);

                    if ($canUseCacheRicardoApi) {
                        $data = unserialize(Mage::app()->loadCache($key));
                    }

                    if (empty($data) || !$canUseCacheRicardoApi) {
                        $data = call_user_func_array(array($this->getServiceModel(), $method), $args);
                    }

                    $this->setData($key, $data);

                    if ($canUseCacheRicardoApi) {
                        Mage::app()->saveCache(serialize($data), $key, array(Diglin_Ricento_Helper_Api::CACHE_TAG), Diglin_Ricento_Helper_Api::CACHE_LIFETIME);
                    }

                    Varien_Profiler::stop($profilerName);

                    return $data;
                }
                break;
            case 'set':
                //@todo to implement
                break;
        }
//        throw new Varien_Exception("Invalid method ".get_class($this)."::".$method."(".print_r($args,1).")");
        return parent::__call($method, $args);
    }
}
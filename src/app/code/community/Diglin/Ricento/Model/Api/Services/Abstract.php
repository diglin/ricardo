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

abstract class Diglin_Ricento_Model_Api_Services_Abstract extends Varien_Object
{
    const CFG_API_HOST = 'ricento/api_config/api_host';
    const CFG_API_HOST_DEV = 'ricento/api_config/api_host_dev';

    /**
     * @var string
     */
    protected $_registryKey =  'serviceManager';

    /**
     * @todo lang parameter is probably not the best place, find an other way to set lang (session, configuration or similar)
     *
     * @param string $lang
     * @return Service
     */
    public function getServiceManager($lang = 'de')
    {
        if (!Mage::registry('ricardo_api_lang')) {
            $lang = Diglin_Ricento_Helper_Data::DEFAULT_SUPPORTED_LANG;
        }

        if (!Mage::registry($this->_registryKey . ucwords($lang))) {
            $helper = Mage::helper('diglin_ricento');

            if (!$helper->isConfigured()) {
                Mage::throwException($helper->__('Ricardo API Credentials are not configured. Please, configure the extension before to proceed.'));
            }

            if (!in_array($lang, $helper->getSupportedLang())) {
                Mage::throwException($helper->__('API lang provided for the Service Manager is not supported'));
            }

            if ($helper->isDevMode()) {
                $host = Mage::getStoreConfig(self::CFG_API_HOST_DEV);
            } else {
                $host = Mage::getStoreConfig(self::CFG_API_HOST);
            }


            $config = array(
                'host' => $host,
                'partnership_id' => $helper->getPartnerId($lang),
                'partnership_passwd' => $helper->getPartnerPass($lang),
                'partner_url' => Mage::helper('adminhtml')->getUrl(),
                'allow_authorization_simulation' => ($helper->canSimulateAuthorization()) ? true : false,
                'customer_username' => $helper->getRicardoUsername(),
                'customer_password' => $helper->getRicardoPass(),
                'debug' => ($helper->isDebugEnabled()) ? true : false
            );

            Mage::register($this->_registryKey . ucwords($lang), new Service(new Api(new Config($config))), false);
        }

        return Mage::registry($this->_registryKey . ucwords($lang));
    }

    /**
     * Get last API call information
     *
     * @param bool $flush
     * @return mixed
     */
    protected function getLastApiDebug($flush = true)
    {
        return print_r($this->getServiceManager()->getApi()->getLastDebug($flush), true);
    }
}
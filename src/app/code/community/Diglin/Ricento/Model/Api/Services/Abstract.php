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
    /**
     * @var string
     */
    protected $_registryKey =  'serviceManager';

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

        $lang = Mage::registry('ricardo_api_lang');
        $registryKey = $this->_registryKey . ucwords($lang);

        if (!Mage::registry($registryKey)) {
            $helper = Mage::helper('diglin_ricento');

            if (!$helper->isConfigured()) {
                Mage::throwException($helper->__('Ricardo API Credentials are not configured. Please, configure the extension before to proceed.'));
            }

            if (!in_array($lang, $helper->getSupportedLang())) {
                Mage::throwException($helper->__('API language provided for the Service Manager is not supported.'));
            }

            if ($helper->isDevMode()) {
                $host = Mage::getStoreConfig(Diglin_Ricento_Helper_Data::CFG_API_HOST_DEV);
            } else {
                $host = Mage::getStoreConfig(Diglin_Ricento_Helper_Data::CFG_API_HOST);
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
    protected function getLastApiDebug($flush = true)
    {
        return $this->getServiceManager()->getApi()->getLastDebug($flush);
    }
}
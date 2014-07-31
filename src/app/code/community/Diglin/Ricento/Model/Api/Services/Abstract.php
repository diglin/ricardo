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
     * @var Service
     */
    protected $_serviceManager;

    public function getServiceManager($lang = 'de')
    {
        $helper = Mage::helper('diglin_ricento');

        if (!$helper->isConfigured()) {
            Mage::throwException($helper->__('Ricardo API Credentials are not configured. Please, configure the extension before to proceed.'));
        }

        if (!in_array($lang, $helper->getSupportedLang())) {
            Mage::throwException($helper->__('API lang provided for the Service Manager is not supported'));
        }

        if (empty($this->_serviceManager)) {

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
            $this->_serviceManager = new Service(new Api(new Config($config)));
        }

        return $this->_serviceManager;
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
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

abstract class Diglin_Ricento_Model_Api_Abstract
{
    const CFG_API_HOST = 'ricento/config/api_host';
    const CFG_API_HOST_DEV = 'ricento/config/api_host_dev';

    public function getApi($locale = Diglin_Ricento_Helper_Data::SUPPORTED_LANG_DE)
    {
        $helper = Mage::helper('diglin_ricento');

        if (!$helper->isConfigured()) {
            Mage::throwException($helper->__('Ricardo API Credentials not configured. Please, configure the extension before to proceed.'));
        }

        if ($helper->isDevMode()) {
            $host = Mage::getStoreConfig(self::CFG_API_HOST_DEV);
        } else {
            $host = Mage::getStoreConfig(self::CFG_API_HOST);
        }

        $config = array(
            'host' => $host,
            'partnership_id' => $helper->getPartnerId($locale),
            'partnership_passwd' => $helper->getPartnerPass($locale),
            'partner_url' => Mage::helper('adminhtml')->getUrl(),
            'allow_authorization_simulation' => ($helper->canSimulateAuthorization()) ? true : false,
            'customer_username' => $helper->getRicardoUsername(),
            'customer_password' => $helper->getRicardoPass(),
            'debug' => ($helper->isDebugEnabled()) ? true : false
        );

        return new Api(new Config($config));
    }
}
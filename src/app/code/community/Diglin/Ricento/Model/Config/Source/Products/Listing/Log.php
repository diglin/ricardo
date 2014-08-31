<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

class Diglin_Ricento_Model_Config_Source_Products_Listing_Log extends Diglin_Ricento_Model_Config_Source_Abstract
{
    public function toOptionHash()
    {
        $helper = Mage::helper('diglin_ricento');

        return array(
            Diglin_Ricento_Model_Products_Listing_Log::LOG_TYPE_CHECK => $helper->__('Product item checks'),
            Diglin_Ricento_Model_Products_Listing_Log::LOG_TYPE_LIST => $helper->__('List product item to ricardo.ch'),
            Diglin_Ricento_Model_Products_Listing_Log::LOG_TYPE_STOP => $helper->__('Stop product item to ricardo.ch'),
        );
    }
}
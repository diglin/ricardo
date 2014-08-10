<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

class Diglin_Ricento_Model_Config_Source_Store extends Mage_Adminhtml_Model_System_Store
{
    /**
     * Retrieve store values for form
     *
     * @param bool $empty
     * @param bool $all
     * @return array
     */
    public function getStoreValuesForForm($empty = false, $all = false)
    {
        $websites = $this->_websiteCollection;
        $websiteId = $this->getWebsiteId();
        if ($websiteId && isset($websites[$websiteId])) {
            $this->_websiteCollection = array($websites[$websiteId]);
        }

        $stores = parent::getStoreValuesForForm($empty, false);

        if ($all) {
            array_unshift($stores, array('label' => Mage::helper('catalog')->__('Default Values'), 'value' => 0));
        }

        return $stores;
    }
}
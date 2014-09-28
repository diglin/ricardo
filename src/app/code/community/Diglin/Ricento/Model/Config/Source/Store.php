<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
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
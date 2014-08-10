<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

class Diglin_Ricento_Model_Config_Source_Website extends Mage_Adminhtml_Model_System_Store
{
    /**
     * @param bool $empty
     * @param bool $all
     * @return array
     */
    public function getWebsiteValuesForForm($empty = false, $all = false)
    {
        $helper = Mage::helper('diglin_ricento');
        $enabledRicentoWebsite = array();

        foreach( $this->_websiteCollection as $website) {
            if($helper->isConfigured($website) && $helper->isEnabled($website)) {
               $enabledRicentoWebsite[] = $website;
            }
        }

        $this->_websiteCollection = $enabledRicentoWebsite;

        return parent::getWebsiteValuesForForm($empty, $all);
    }
}
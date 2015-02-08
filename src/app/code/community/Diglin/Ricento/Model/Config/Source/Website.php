<?php
/**
 * ricardo.ch AG - Switzerland
 *
 * @author      Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2014 ricardo.ch AG (http://www.ricardo.ch)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Class Diglin_Ricento_Model_Config_Source_Website
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

        foreach ($this->_websiteCollection as $website) {
            if ($helper->isConfigured($website) && $helper->isEnabled($website)) {
                $enabledRicentoWebsite[] = $website;
            }
        }

        $this->_websiteCollection = $enabledRicentoWebsite;

        return parent::getWebsiteValuesForForm($empty, $all);
    }
}
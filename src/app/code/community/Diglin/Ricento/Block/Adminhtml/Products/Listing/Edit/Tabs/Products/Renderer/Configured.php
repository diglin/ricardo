<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

/**
 * Class Diglin_Ricento_Block_Adminhtml_Products_Listing_Edit_Tabs_Products_Renderer_Customoptions
 *
 * Renderer for column with configured rules or sales
 */
class Diglin_Ricento_Block_Adminhtml_Products_Listing_Edit_Tabs_Products_Renderer_Configured
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * @param Varien_Object $row
     * @return string
     */
    public function render(Varien_Object $row)
    {
        $isConfigured = ($row->getSalesOptionsId() || $row->getRuleId()) ? true : false;
        if ($isConfigured) {

            $append = $salesOptionsTxt = $ruleTxt = '';
            if ($row->getSalesOptionsId()) {
                $salesOptionsTxt = $this->__('the sales options');
                $append = '& ';
            }
            if ($row->getRuleId()) {
                $ruleTxt = $append . $this->__('the shipping and payment rules');
            }

            $warningMessage = $this->__('This product has been configured for %s %s', $salesOptionsTxt, $ruleTxt);
            return
<<<HTML
    <div class="diglin_ricento_settings_icon" title="{$warningMessage}">&nbsp;</div>
HTML;
        }
        return '';
    }

}
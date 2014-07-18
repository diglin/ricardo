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
 * Renderer for column with warning about custom options
 */
class Diglin_Ricento_Block_Adminhtml_Products_Listing_Edit_Tabs_Products_Renderer_Customoptions
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * @param Varien_Object $row
     * @return string
     */
    public function render(Varien_Object $row)
    {
        $hasCustomOptions = $this->_getValue($row);
        if ($hasCustomOptions) {
            $warningMessage = $this->__('The product has custom options, those will not be added to Ricardo!');
            return
<<<HTML
    <div class="diglin_ricento_warning_icon" title="{$warningMessage}">&nbsp;</div>
HTML;
        }
        return '';
    }

}
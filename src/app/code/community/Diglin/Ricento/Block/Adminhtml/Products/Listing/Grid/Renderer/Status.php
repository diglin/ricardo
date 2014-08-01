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
 * Class Diglin_Ricento_Block_Adminhtml_Products_Listing_Grid_Renderer_Status
 *
 * Renderer for detailed status information
 */
class Diglin_Ricento_Block_Adminhtml_Products_Listing_Grid_Renderer_Status
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * @param Varien_Object $row
     * @return string
     */
    public function render(Varien_Object $row)
    {
        $html = '';
        $html .= '<strong>' . $this->_getValue($row) . '</strong>';
        $html .= '<dl class="diglin_ricento_status_info">';
        $html .= '<dt>' . $this->__('Listed products:') . '</dt>';
        $html .= '<dd>' . $row->getData('total_active_products') . '</dd>';
        $html .= '<dt>' . $this->__('Not listed products:') . '</dt>';
        $html .= '<dd>' . $row->getData('total_inactive_products') . '</dd>';
        $html .= '</dl>';
        return $html;
    }
}
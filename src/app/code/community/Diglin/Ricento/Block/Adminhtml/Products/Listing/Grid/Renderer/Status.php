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
        $resourceModel = Mage::getResourceModel('diglin_ricento/products_listing_item');
        $readAdapter = $resourceModel->getReadConnection();

        $sqlListed = $readAdapter->select()
            ->from($resourceModel->getTable('diglin_ricento/products_listing_item'), new Zend_Db_Expr('COUNT(*)'))
            ->where('products_listing_id = ?', $row->getId())
            ->where('status = ?', Diglin_Ricento_Helper_Data::STATUS_LISTED)
            ->where('parent_item_id IS NULL');

        $sqlNotListed = $readAdapter->select()
            ->from($resourceModel->getTable('diglin_ricento/products_listing_item'), new Zend_Db_Expr('COUNT(*)'))
            ->where('products_listing_id = ?', $row->getId())
            ->where('status != ?', Diglin_Ricento_Helper_Data::STATUS_LISTED)
            ->where('parent_item_id IS NULL');


        $totalListed = $readAdapter->fetchOne($sqlListed);
        $totalUnlisted = $readAdapter->fetchOne($sqlNotListed);

        $html = '';
        $html .= '<strong>' . $this->_getValue($row) . '</strong>';
        $html .= '<dl class="diglin_ricento_status_info">';
        $html .= '<dt>' . $this->__('Listed products:') . '</dt>';
        $html .= '<dd>' . (int) $totalListed . '</dd>';
        $html .= '<dt>' . $this->__('Not listed products:') . '</dt>';
        $html .= '<dd>' . (int) $totalUnlisted . '</dd>';
        $html .= '</dl>';
        return $html;
    }
}
<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author      Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Class Diglin_Ricento_Block_Adminhtml_Products_Listing_Edit_Tabs_Products_Renderer_Inventory
 *
 * Renderer for column name for configurable product
 */
class Diglin_Ricento_Block_Adminhtml_Products_Listing_Edit_Tabs_Products_Renderer_Inventory
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * @param Varien_Object $row
     * @return string
     */
    public function render(Varien_Object $row)
    {
        $value = $this->_getValue($row);
        if ($row->getTypeId() == Mage_Catalog_Model_Product_Type_Configurable::TYPE_CODE) {
            $itemCollection = Mage::getResourceModel('diglin_ricento/products_listing_item_collection');
            $itemCollection->addFieldToFilter('parent_item_id', $row->getItemId());

            $inventory = array();
            foreach ($itemCollection->getItems() as $item) {
                $qtyInventory = $item->getQtyInventory();
                if (isset($qtyInventory)) {
                    $inventory[] = '<li>' . $qtyInventory . '</li>';
                }
            }

            if (count($inventory)) {
                $value = '<ul><li>&nbsp;</li>' . implode($inventory) . '</ul>';
            }
        }

        return $value;
    }

}
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
 * Class Diglin_Ricento_Block_Adminhtml_Products_Listing_Edit_Tabs_Products_Renderer_Name
 *
 * Renderer for column name for configurable product
 */
class Diglin_Ricento_Block_Adminhtml_Products_Listing_Edit_Tabs_Products_Renderer_Name
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

            $subtitles = array();
            foreach ($itemCollection->getItems() as $item) {
                $subtitle = $item->getProductSubtitle();
                if (isset($subtitle)) {
                    $subtitles[] = '<li>' . $subtitle . '</li>';
                }
            }

            $value = $value . '<ul>' . implode($subtitles) . '</ul>';
        }
        return $value;
    }

}
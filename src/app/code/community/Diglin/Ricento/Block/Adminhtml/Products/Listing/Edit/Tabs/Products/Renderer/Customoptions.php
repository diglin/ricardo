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
        if ($row instanceof Diglin_Ricento_Model_Products_Listing_Item) {
            $productId = $row->getProductId();
        } else if ($row instanceof Mage_Catalog_Model_Product) {
            $productId = $row->getId();
        } else {
            return '';
        }

        $optionsCollection = Mage::getResourceModel('catalog/product_option_collection');
        $optionsCollection
            ->getSelect()
            ->from(array('option' => $optionsCollection->getTable('catalog/product_option')))
            ->where('option.product_id = ?', $productId)
            ->where('option.option_id IS NOT NULL')
            ->group('option.product_id');

        if (count($optionsCollection->getItems())) {
            $warningMessage = $this->__('The product has custom options, those will not be added to ricardo.ch!');
            return
                <<<HTML
                    <div class="diglin_ricento_warning_icon" title="{$warningMessage}">&nbsp;</div>
HTML;
        }

        return '';
    }

}
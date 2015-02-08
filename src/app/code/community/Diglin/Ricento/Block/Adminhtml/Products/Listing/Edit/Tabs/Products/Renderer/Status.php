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
 * Class Diglin_Ricento_Block_Adminhtml_Products_Listing_Edit_Tabs_Products_Renderer_Status
 *
 * Renderer for column name for configurable product
 */
class Diglin_Ricento_Block_Adminhtml_Products_Listing_Edit_Tabs_Products_Renderer_Status
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
            $helper = Mage::helper('diglin_ricento');

            $statuses = array();
            foreach ($itemCollection->getItems() as $item) {
                $output = '';
                switch ($item->getStatus()) {
                    case 'error':
                        $output .= '<span class="message_errors">' . $helper->__('Stopped') . '</span>';
                        break;
                    case 'listed':
                        $output .= '<span class="message_success">' . $helper->__('Listed') . '</span>';
                        break;
                    default:
                        $output .= '<span class="message_warnings">' . $helper->__(ucwords($item->getStatus())) . '</span>';
                        break;
                }
                $statuses[] = '<li>' . $output . '</li>';
            }

            if (count($statuses)) {
                $value = '<ul><li>&nbsp;</li>' . implode($statuses) . '</ul>';
            } else {
                $value = $this->_decorate($value, $row);
            }
        } else {
            $value = $this->_decorate($value, $row);
        }

        return $value;
    }

    protected function _decorate($value, Varien_Object $row)
    {
        $output = '';
        $helper = Mage::helper('diglin_ricento');
        $value = htmlspecialchars_decode($value);
        switch ($row->getStatus()) {
            case 'error':
                $output .= '<div id="message-errors-' . $row->getId() . '" class="message_errors">' . $helper->__('Error') . '</div>';
                break;
            case 'listed':
                $output .= '<div id="message-success-' . $row->getId() . '" class="message_success">' . $helper->__('Listed') . '</div>';
                break;
            default:
                $output .= '<div id="message-warnings-' . $row->getId() . '" class="message_warnings">' . $helper->__(ucwords($value)) . '</div>';
                break;
        }

        return $output;
    }
}
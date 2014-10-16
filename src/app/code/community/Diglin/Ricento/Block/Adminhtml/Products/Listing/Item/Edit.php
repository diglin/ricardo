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
 * Class Diglin_Ricento_Block_Adminhtml_Products_Listing_Item_Edit
 */
class Diglin_Ricento_Block_Adminhtml_Products_Listing_Item_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_objectId = 'entity_id';
        $this->_blockGroup = 'diglin_ricento';
        $this->_controller = 'adminhtml_products_listing_item';

        parent::__construct();

        $this->updateButton('back', 'label', Mage::helper('adminhtml')->__('Back to the list "%s"', $this->getListing()->getTitle()));
    }

    /**
     * Retrieve text for header element depending on loaded listing
     *
     * @return string
     */
    public function getHeaderText()
    {
        if (count($this->getSelectedItems()) == 1) {
            return $this->__("Configure product '%s'", $this->escapeHtml($this->getSelectedItems()->getFirstItem()->getMagentoProduct()->getName()));
        } else {
            return $this->__('Configure all %s selected products', count($this->getSelectedItems()));
        }
    }

    /**
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('*/products_listing/edit', array('id' => $this->getListingId()));
    }

    /**
     * Returns items that are selected to be configured
     *
     * @return Diglin_Ricento_Model_Resource_Products_Listing_Item_Collection
     */
    public function getSelectedItems()
    {
        return Mage::registry('selected_items');
    }

    /**
     * Returns product listing
     *
     * @return Diglin_Ricento_Model_Products_Listing
     */
    public function getListing()
    {
        return Mage::registry('products_listing');
    }

    /**
     * Returns product listing id if listing loaded, null otherwise
     *
     * @return int|null
     */
    public function getListingId()
    {
        $listing = $this->getListing();
        if ($listing) {
            return $listing->getId();
        }
        return null;
    }
}

<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Class Diglin_Ricento_Block_Adminhtml_Products_Listing_Edit
 */
class Diglin_Ricento_Block_Adminhtml_Products_Listing_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'diglin_ricento';
        $this->_controller = 'adminhtml_products_listing';
        parent::__construct();

        $this->removeButton('reset');

        $this->_addButton('add_product_from_category', array(
            'label'   => $this->__('Add Product(s) from category'),
            'onclick' => "Ricento.showCategoryTreePopup('{$this->getShowCategoryTree()}')",
            'class'   => 'add'
        ), -1, -2);

        $this->_addButton('add_product', array(
            'label'   => $this->__('Add Product(s)'),
            'onclick' => "Ricento.addProductsPopup('{$this->getAddProductsPopupUrl()}')",
            'class'   => 'add'
        ), -1, -1);

        $this->_addButton('stop', array(
            'label'   => $this->__('Stop'),
            'title'   => $this->__('Remove article listed on ricardo.ch'),
            'onclick' => 'setLocation(\'' . $this->getStopListingUrl() . '\')',
            'class'   => 'cancel'
        ), -1, 2);

        $this->_addButton('check_and_list', array(
            'label' => $this->__('Check & List'),
            'title' => $this->__('Check & list only pending & error items'),
            'onclick' => 'editForm.submit(\'' . $this->getCheckAndListUrl() . '\');',
            'class' => 'list success'
        ), -1, 3);

        if (Mage::getResourceModel('diglin_ricento/products_listing_item')->coundReadyTolist($this->getListing()->getId())) {
            $this->_addButton('force_list', array(
                'label' => $this->__('List'),
                'title' => $this->__('List only "Ready to list" items'),
                'onclick' => 'editForm.submit(\'' . $this->getListUrl() . '\');',
                'class' => 'list success'
            ), -1, 4);
        }

        if ($this->getListing()->getStatus() === Diglin_Ricento_Helper_Data::STATUS_LISTED) {
            $this->_updateButton('add_product_from_category', 'disabled', true);
            $this->_updateButton('add_product', 'disabled', true);
            $this->_updateButton('delete', 'disabled', true);
            $this->_updateButton('delete', 'title', $this->__('Listed listings cannot be deleted. Stop the listing first.'));

            $this->_removeButton('save');
        }
    }

    /**
     * Retrieve text for header element depending on loaded listing
     *
     * @return string
     */
    public function getHeaderText()
    {
        if ($this->getListingId()) {
            return $this->__("Edit Product Listing '%s'", $this->escapeHtml(Mage::registry('products_listing')->getTitle()));
        }
        else {
            return $this->__('New Product Listing');
        }
    }

    /**
     * Returns URL for AJAX popup
     *
     * @return string
     */
    public function getAddProductsPopupUrl()
    {
        return $this->getUrl('ricento/products_listing/addProductsPopup', array('id' => $this->getListingId()));
    }

    /**
     * Returns URL
     *
     * @return string
     */
    public function getShowCategoryTree()
    {
        return $this->getUrl('ricento/products_category/showcategoriestree', array('id' => $this->getListingId()));
    }

    /**
     * Returns URL to stop listing
     *
     * @return string
     */
    public function getStopListingUrl()
    {
        return $this->getUrl('ricento/products_listing/stop', array('id' => $this->getListingId()));
    }

    /**
     * Returns URL for "save and list" form action
     *
     * @return string
     */
    public function getCheckAndListUrl()
    {
        return $this->getUrl('ricento/products_listing/checkAndList', array('id' => $this->getListingId()));
    }

    /**
     * Returns URL for "save and list" form action
     *
     * @return string
     */
    public function getListUrl()
    {
        return $this->getUrl('ricento/products_listing/list', array('id' => $this->getListingId()));
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
<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
class Diglin_Ricento_Block_Adminhtml_Products_Listing_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'diglin_ricento';
        $this->_controller = 'adminhtml_products_listing';

        $this->_addButton('add_product', array(
            'label' => $this->__('Add Product(s)'),
            'onclick' => "Ricento.addProductsPopup('{$this->getAddProductsPopupUrl()}')"
        ), 0, 0);
        parent::__construct();

        $this->_updateButton('save', 'label', $this->__('Save Product Listing'));
        $this->_updateButton('delete', 'label', $this->__('Delete Product Listing'));

        $this->_addButton('saveandcontinue', array(
            'label' => Mage::helper('adminhtml')->__('Save And Continue Edit') ,
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save'
        ), - 100);

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
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
     * Returns product listing id if listing loaded, null otherwise
     *
     * @return int|null
     */
    public function getListingId()
    {
        $listing = Mage::registry('products_listing');
        if ($listing) {
            return $listing->getId();
        }
        return null;
    }
}
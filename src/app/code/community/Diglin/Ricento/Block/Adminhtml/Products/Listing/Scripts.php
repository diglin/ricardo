<?php
class Diglin_Ricento_Block_Adminhtml_Products_Listing_Scripts extends Mage_Adminhtml_Block_Template
{
    public function getListing()
    {
        return Mage::registry('products_listing');
    }
    public function getListingId()
    {
        $listing = $this->getListing();
        if ($listing) {
            return $listing->getId();
        }
        return null;
    }
    public function getAddProductsPopupUrl()
    {
        return $this->getUrl('ricento/products_listing/addProductsPopup', array('id' => $this->getListingId()));
    }
}
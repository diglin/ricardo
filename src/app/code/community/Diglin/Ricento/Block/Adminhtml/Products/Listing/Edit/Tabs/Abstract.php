<?php
abstract class Diglin_Ricento_Block_Adminhtml_Products_Listing_Edit_Tabs_Abstract extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * @return Diglin_Ricento_Model_Products_Listing
     */
    protected function _getListing()
    {
        $listing = Mage::registry('products_listing');
        if (!$listing) {
            Mage::throwException('Products listing not loaded');
        }
        return $listing;
    }

    protected function _initFormValues()
    {
        if ($this->_getListing()->getStatus() === Diglin_Ricento_Helper_Data::STATUS_LISTED) {
            $this->getForm()->addField('readonly_note', 'note', array(
                'text' => '<ul class="messages"><li class="warning-msg"><span>' .
                    $this->__('Listed listings cannot be modified. Stop the listing first to make changes.') .
                    '</span></li></ul>'
            ), '^');
        }
        return parent::_initFormValues();
    }

}
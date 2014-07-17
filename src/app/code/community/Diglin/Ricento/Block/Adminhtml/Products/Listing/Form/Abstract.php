<?php
class Diglin_Ricento_Block_Adminhtml_Products_Listing_Form_Abstract extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _afterToHtml($html)
    {
        //FIXME JavaScript greift nicht, vermutlich wegen verschachteltem Mass Action Formular
        if ($this->isReadonlyForm()) {
            $html .= <<<HTML
<script type="text/javascript">
    document.forms['edit_form'].disable();
</script>
HTML;
        }
        return parent::_afterToHtml($html);
    }

    public function isReadonlyForm()
    {
        return $this->_getListing()->getStatus() === Diglin_Ricento_Helper_Data::STATUS_LISTED;
    }
    public function getReadonlyNote()
    {
        return $this->__('Listed listings cannot be modified. Stop the listing first to make changes.');
    }

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
        if ($this->isReadonlyForm()) {
            $this->getForm()->addField('readonly_note', 'note', array(
                'text' => '<ul class="messages"><li class="warning-msg"><span>' .
                    $this->getReadonlyNote() .
                    '</span></li></ul>'
            ), '^');
        }
        return parent::_initFormValues();
    }
}
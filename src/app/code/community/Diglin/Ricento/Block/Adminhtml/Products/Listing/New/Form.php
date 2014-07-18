<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

/**
 * Class Diglin_Ricento_Block_Adminhtml_Products_Listing_New_Form
 */
class Diglin_Ricento_Block_Adminhtml_Products_Listing_New_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $actionUrl = $this->getUrl('*/*/create');
        $form = new Varien_Data_Form(
            array('id' => 'diglin_ricento_create_listing_form', 'action' => $actionUrl, 'method' => 'post')
        );
        $htmlIdPrefix = 'diglin_ricento_';
        $form->setHtmlIdPrefix($htmlIdPrefix);

        $fieldset = $form->addFieldset('create_listing_fieldset', array());

        $fieldset->addField('listing_title', 'text', array(
            'name'     => 'listing_title',
            'required' => true,
            'label'    => $this->__('Give a name'),
            'title'    => $this->__('Give a name'),
        ));

        $fieldset->addField('store_id', 'select', array(
            'name'     => 'store_id',
            'required' => true,
            'label'    => $this->__('Select a store'),
            'title'    => $this->__('Select a store'),
            'values'   => Mage::getSingleton('adminhtml/system_store')
                    ->getStoreValuesForForm(true, false),
        ));

        $cancelButton = $this->getButtonHtml(
            $this->__('Cancel'),
            'Ricento.closePopup()',
            'cancel'
        );
        $submitButton = $this->getButtonHtml(
            $this->__('Create new listing'),
            'Ricento.newListingForm.submit()',
            'add'
        );
        $fieldset->addField('buttons', 'note', array(
            'container_id' => $htmlIdPrefix . 'buttons_container',
            'text'         => "{$cancelButton} {$submitButton}"
        ));

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
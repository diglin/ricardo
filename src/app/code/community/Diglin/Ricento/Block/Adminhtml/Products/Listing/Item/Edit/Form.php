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
 * Class Diglin_Ricento_Block_Adminhtml_Products_Listing_Item_Edit_Form
 */
class Diglin_Ricento_Block_Adminhtml_Products_Listing_Item_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('ricento/products/listing/item/edit/form.phtml');
    }

    /**
     * @return Mage_Core_Block_Abstract
     */
    protected function _prepareLayout()
    {
        $this->setChild('tabs',
            $this->getLayout()->createBlock('diglin_ricento/adminhtml_products_listing_item_edit_tabs', 'tabs')
        );
        return parent::_prepareLayout();
    }

    /**
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'class' => 'ricento-form',
            'action' => $this->getUrl('*/*/save', array(
                    'id' => $this->getRequest()
                            ->getParam('id')
                )), 'method' => 'post', 'enctype' => 'multipart/form-data'
        ));
        $form->addField('item_ids', 'hidden', array(
            'name' => 'item_ids'
        ));

        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }

    /**
     * @return string
     */
    public function getTabsHtml()
    {
        return $this->getChildHtml('tabs');
    }

    /**
     * @return $this|Mage_Adminhtml_Block_Widget_Form
     */
    protected function _initFormValues()
    {
        parent::_initFormValues();
        $this->getForm()->addValues(array('item_ids' => implode(',', $this->getSelectedItems()->getAllIds())));
        return $this;
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
}
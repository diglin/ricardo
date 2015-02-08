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
class Diglin_Ricento_Block_Adminhtml_Products_Category_Tree_Form extends Diglin_Ricento_Block_Adminhtml_Products_Listing_Form_Abstract
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
            'id'     => 'edit_form' ,
            'class'  => 'ricento-form',
            'action' => $this->getUrl('*/*/saveProductsToAdd', array(
                    'id' => $this->getRequest()->getParam('id')
                )),
            'method' => 'post',
            'enctype' => 'multipart/form-data'
        ));

        $form->setUseContainer(true);

        $categoriesSelection = $form->addFieldset('categories_selection', array());
        $categoriesSelection
            ->addField('category_tree', 'text', array())
            ->setRenderer($this->getLayout()->createBlock('diglin_ricento/adminhtml_products_category_form_renderer_categories'));

        $this->setForm($form);
        return parent::_prepareForm();
    }
}
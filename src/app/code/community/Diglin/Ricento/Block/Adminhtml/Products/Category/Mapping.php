<?php
class Diglin_Ricento_Block_Adminhtml_Products_Category_Mapping extends Mage_Adminhtml_Block_Template
{
    protected function _beforeToHtml()
    {
        $this->setChild('toplevel',
            $this->getLayout()
                ->createBlock('diglin_ricento/adminhtml_products_category_children')
                ->setTemplate('ricento/products/category/children.phtml')
                ->setLevel(0)
                ->setCategoryId(1)
        );
        return parent::_beforeToHtml();
    }


}
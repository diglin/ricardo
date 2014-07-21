<?php

/**
 * Class Diglin_Ricento_Block_Adminhtml_Products_Category_Children
 *
 * @method int getLevel() getLevel()
 * @method int getCategoryId() getCategoryId()
 * @method Diglin_Ricento_Block_Adminhtml_Products_Category_Children setLevel() setLevel(int $level)
 * @method Diglin_Ricento_Block_Adminhtml_Products_Category_Children setCategoryId() setCategoryId(int $categoryId)
 */
class Diglin_Ricento_Block_Adminhtml_Products_Category_Children extends Mage_Adminhtml_Block_Template
{
    /**
     * Returns child categories of current category
     *
     * @return Diglin_Ricento_Model_Products_Category[]
     */
    public function getCategories()
    {
        /* @var $mapping Diglin_Ricento_Model_Products_Category_Mapping */
        $mapping = Mage::getModel('diglin_ricento/products_category_mapping');

        return $mapping->getCategories($this->getCategoryId());
    }

}
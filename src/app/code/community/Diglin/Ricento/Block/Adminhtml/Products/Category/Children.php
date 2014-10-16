<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author      Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Class Diglin_Ricento_Block_Adminhtml_Products_Category_Children
 * Children of the Ricardo categories
 *
 * @method int getLevel() getLevel()
 * @method int getCategoryId() getCategoryId()
 * @method int getSelectedCategoryId() getSelectedCategoryId()
 * @method Diglin_Ricento_Block_Adminhtml_Products_Category_Children setLevel() setLevel(int $level)
 * @method Diglin_Ricento_Block_Adminhtml_Products_Category_Children setCategoryId() setCategoryId(int $categoryId)
 * @method Diglin_Ricento_Block_Adminhtml_Products_Category_Children setSelectedCategoryId() setSelectedCategoryId(int $categoryId)
 */
class Diglin_Ricento_Block_Adminhtml_Products_Category_Children extends Mage_Adminhtml_Block_Template
{
    protected $_template = 'ricento/products/category/children.phtml';

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
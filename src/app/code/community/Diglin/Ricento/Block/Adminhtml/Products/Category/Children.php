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
     * Root element for category tree
     *
     * @var array
     */
    protected $_categoryTree = array(
            'CategoryId' => 1,
            'Level'      => 0,
            'IsFinal'    => 0,
            'children'   => array()
        );
    /**
     * Flat array [ category_id => &category ]
     *
     * @var
     */
    protected $_categoryIndex = array();

    /**
     * Returns child categories of current category
     *
     * @return mixed
     */
    public function getCategories()
    {
        $this->buildRicardoCategoryTree();
        return $this->_categoryIndex[$this->getCategoryId()]['children'];
    }

    /**
     * @todo extract to (resource?) model, cache the tree
     */
    private function buildRicardoCategoryTree()
    {
        $categories = Mage::helper('diglin_ricento')->getRicardoCategoriesFromApi();
        usort($categories, function($a, $b) { return $a['Level'] - $b['Level'] ?: strnatcasecmp($a['CategoryName'], $b['CategoryName']); });
        $this->_categoryIndex = array();
        $this->_categoryIndex[1] =& $this->_categoryTree;
        foreach ($categories as $category) {
            $category['children'] = array();
            $this->_categoryIndex[$category['ParentId']]['children'][$category['CategoryId']] = $category;
            if (!$category['IsFinal']) {
                $this->_categoryIndex[$category['CategoryId']] =& $this->_categoryIndex[$category['ParentId']]['children'][$category['CategoryId']];
            }
        }
        return $this->_categoryTree;
    }
}
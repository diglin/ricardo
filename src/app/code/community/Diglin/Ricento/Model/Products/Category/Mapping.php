<?php

/**
 * Class Diglin_Ricento_Model_Products_Category_Mapping
 */
class Diglin_Ricento_Model_Products_Category_Mapping extends Varien_Object
{
    const ROOT_CATEGORY_ID = 1;
    /**
     * Root element for category tree
     *
     * @var Diglin_Ricento_Model_Products_Category
     */
    protected $_categoryTree;

    /**
     * Flat array [ category_id => category ]
     *
     * @var Diglin_Ricento_Model_Products_Category[]
     */
    protected $_categoryIndex = array();

    /**
     * Create root category
     */
    protected function _construct()
    {
        $this->_categoryTree = Mage::getModel('diglin_ricento/products_category', array(
            'category_id' => self::ROOT_CATEGORY_ID,
            'level'       => 0,
            'is_final'    => 0
        ));
    }

    /**
     * Returns child categories of current category
     *
     * @param int $parentId Parent category, defaults to root category
     * @return mixed
     */
    public function getCategories($parentId = self::ROOT_CATEGORY_ID)
    {
        if (empty($this->_categoryIndex)) {
            $this->_buildRicardoCategoryTree();
        }
        if (!$this->getCategory($parentId)) {
            Mage::log("Ricardo category ID {$parentId} not found.", Zend_Log::WARN, 'ricento.log');
            return array();
        }
        return $this->getCategory($parentId)->getChildren();
    }

    /**
     * Returns category object by id
     *
     * @param $categoryId
     * @return Diglin_Ricento_Model_Products_Category
     */
    public function getCategory($categoryId)
    {
        if (empty($this->_categoryIndex)) {
            $this->_buildRicardoCategoryTree();
        }
        return (isset($this->_categoryIndex[$categoryId]) ? $this->_categoryIndex[$categoryId] : false);
    }

    /**
     * Build category index and tree
     *
     * @todo cache the tree and index
     */
    protected function _buildRicardoCategoryTree()
    {
        $this->_addCategoryToIndex($this->_categoryTree);
        foreach ($this->_getRicardoCategoryData() as $_categoryData) {
            /* @var $category Diglin_Ricento_Model_Products_Category */
            $category = Mage::getModel('diglin_ricento/products_category');
            $category->setDataFromApi($_categoryData);
            if (!$this->getCategory($category->getParentId())) {
                Mage::log("Ricardo parent category ID {$category->getParentId()} as parent for category {$category->getId()} not found.", Zend_Log::WARN, Diglin_Ricento_Helper_Data::LOG_FILE);
                continue;
            }
            $this->_addCategoryToIndex($category);
        }
        return $this->_categoryTree;
    }

    /**
     * Add category to index and tree
     *
     * @param Diglin_Ricento_Model_Products_Category $category
     */
    protected function _addCategoryToIndex(Diglin_Ricento_Model_Products_Category $category)
    {
        $this->_categoryIndex[$category->getId()] = $category;
        if ((int) $category->getLevel() > 0) {
            $this->_categoryIndex[$category->getParentId()]->addChild($category);
        }
    }

    /**
     * Returns sorted API result
     *
     * @return mixed
     */
    protected function _getRicardoCategoryData()
    {
        $categories = Mage::helper('diglin_ricento')->getRicardoCategoriesFromApi();
        usort($categories, array(__CLASS__, 'sortByLevelAndName'));
        return $categories;
    }

    /**
     * Helper method to sort the API result
     *
     * @param $category1
     * @param $category2
     * @return int
     */
    private static function sortByLevelAndName($category1, $category2)
    {
        return ($category1['Level'] - $category2['Level']) ?: strnatcasecmp($category1['CategoryName'], $category2['CategoryName']);
    }
}
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
     * @var bool
     */
    protected $_canUseCache = true;

    /**
     * @var string
     */
    protected $_cacheKeyTree = 'ricardo_categories_tree';

    /**
     * @var string
     */
    protected $_cacheKeyIndex = 'ricardo_categories_index';

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
            Mage::log("Ricardo category ID {$parentId} not found.", Zend_Log::WARN, Diglin_Ricento_Helper_Data::LOG_FILE);
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
     * @return Diglin_Ricento_Model_Products_Category|mixed
     */
    protected function _buildRicardoCategoryTree()
    {
        Varien_Profiler::start('RICARDO_API_BUILD_CATEGORIES');

        if ($this->getCanUseCache()) {
            $this->_categoryTree = unserialize(Mage::app()->loadCache($this->_cacheKeyTree));
            $this->_categoryIndex = unserialize(Mage::app()->loadCache($this->_cacheKeyIndex));
        }

        if (empty($this->_categoryTree)) {

            $this->_categoryTree = Mage::getModel('diglin_ricento/products_category', array(
                'category_id' => self::ROOT_CATEGORY_ID,
                'level'       => 0,
                'is_final'    => 0
            ));

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
        }

        if ($this->getCanUseCache() && !empty($this->_categoryTree)) {
            Mage::app()->saveCache(serialize($this->_categoryTree), $this->_cacheKeyTree, array(Diglin_Ricento_Helper_Api::CACHE_TAG), Diglin_Ricento_Helper_Api::CACHE_LIFETIME);
            Mage::app()->saveCache(serialize($this->_categoryIndex), $this->_cacheKeyIndex, array(Diglin_Ricento_Helper_Api::CACHE_TAG), Diglin_Ricento_Helper_Api::CACHE_LIFETIME);
        }

        Varien_Profiler::stop('RICARDO_API_BUILD_CATEGORIES');

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
        $categories = Mage::getSingleton('diglin_ricento/config_source_categories')->toOptionHash();
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

    /**
     * Set if the cache is allow to be used
     *
     * @param boolean $canUseCache
     * @return $this
     */
    public function setCanUseCache($canUseCache)
    {
        $this->_canUseCache = (bool) $canUseCache;
        return $this;
    }

    /**
     * Allowed to use the cache or not
     *
     * @return boolean
     */
    public function getCanUseCache()
    {
        return (Mage::app()->useCache(Diglin_Ricento_Helper_Api::CACHE_TYPE) && $this->_canUseCache);
    }
}
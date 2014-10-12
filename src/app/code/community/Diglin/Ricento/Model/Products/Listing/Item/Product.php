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
 * Class Diglin_Ricento_Model_Products_Listing_Item_Product
 */
class Diglin_Ricento_Model_Products_Listing_Item_Product
{
    /**
     * @var int
     */
    private $_productId;

    /**
     * @var int
     */
    private $_storeId = Mage_Core_Model_App::ADMIN_STORE_ID;

    /**
     * @var null
     */
    private $_defaultStoreId = null; // fallback for language

    /**
     * @var Mage_Catalog_Model_Product
     */
    protected $_model;

    /**
     * @var int
     */
    protected $_productListingItemId = null;

    /**
     * @var Diglin_Ricento_Model_Products_Listing_Item
     */
    protected $_productListingItem = null;

    /**
     * For grouped products
     *
     * @var array
     */
    protected $_associatedProducts = array();

    /**
     * For grouped products
     *
     * @var array
     */
    protected $_associatedProductIds = array();

    /**
     * For configurable products
     *
     * @var array
     */
    protected $_usedProducts = array();

    /**
     * For configurable products
     *
     * @var array
     */
    protected $_usedProductIds = array();

    /**
     * For configurable products
     *
     * @var array
     */
    protected $_configurable_attributes = array();

    protected $_title;
    protected $_subtitle;
    protected $_description;
    protected $_condition;

    protected $_has_options;
    protected $_required_options;
    protected $_sku;
    protected $_typeid;
    protected $_category_ids = array();

    public function _construct($productListingItemId = null)
    {
        $this->_productListingItemId = $productListingItemId;

        if (!is_null($productListingItemId) && is_numeric($productListingItemId)) {
            $this->setProductListingItem(Mage::getModel('diglin_ricento/products_listing_item')
                ->load($productListingItemId)
                ->setLoadFallbackOptions(true));
        }
    }

    /**
     * @return $this
     */
    public function reset()
    {
        $this->_model = null;
        $this->_productListingItemId = null;
        $this->_associatedProducts = null;
        return $this;
    }

    /**
     * @return Mage_Catalog_Model_Product
     * @throws Exception
     */
    public function getMagentoProduct()
    {
        if ($this->_model) {
            return $this->_model;
        }

        if ($this->_productId > 0) {
            $this->loadProduct();
            return $this->_model;
        }

        throw new Exception('Model has not been instanciated');
    }

    /**
     * @param Mage_Catalog_Model_Product $productModel
     * @return $this
     */
    public function setProduct(Mage_Catalog_Model_Product $productModel)
    {
        $this->_model = $productModel;

        $this->setProductId($productModel->getId());
        $this->setStoreId($productModel->getStoreId());

        return $this;
    }

    /**
     * @param null|int $productId
     * @param null|int $storeId
     * @return $this
     * @throws Exception
     */
    public function loadProduct($productId = null, $storeId = null)
    {
        $productId = (is_null($productId)) ? $this->_productId : $productId;
        $storeId = (is_null($storeId)) ? $this->_storeId : $storeId;

        if (!$productId) {
            throw new Exception('Product ID is empty.');
        }

        $this->_model = Mage::getModel('catalog/product')
            ->setStoreId($storeId)
            ->load($productId);

        $this->setProductId($productId);
        $this->setStoreId($storeId);

        return $this;
    }

    /**
     * @param int $storeId
     * @return array
     */
    public function getStoresList($storeId)
    {
        return array(
            $storeId,
            $this->getDefaultStoreId(),
            Mage_Core_Model_App::ADMIN_STORE_ID
        );
    }

    /**
     * @param null $productId
     * @return array|bool
     */
    public function getProductInformation($productId = null)
    {
        (!is_null($productId)) && $this->_productId = $productId;

        $productId = (int) is_null($productId) ? $this->_productId : $productId;

        if (empty($productId)) {
            return false;
        }

        $cols = array(
            'type_id',
            'sku',
            'required_options',
        );

        $readConnection = $this->_getReadConnection();
        $coreResource = $this->_getCoreResource();

        $select = $readConnection
            ->select()
            ->from(array('cpe' => $coreResource->getTableName('catalog_product_entity')), $cols)
            ->joinLeft(array('cp' => $coreResource->getTableName('catalog/category_product')), 'cpe.entity_id = cp.product_id', "GROUP_CONCAT(category_id SEPARATOR ',') AS category_ids")
            ->joinLeft(array('co' => $coreResource->getTableName('catalog/product_option')), 'co.product_id = cpe.entity_id', new Zend_Db_Expr('IF(co.option_id > 0, 1,0) AS has_options'))
            ->where('`entity_id` = ?', $productId)
            ->group('entity_id');

        $data = $readConnection->fetchRow($select);

        if (!empty($data)) {
            $this->_typeid = $data['type_id'];
            $this->_sku = $data['sku'];
            $this->_has_options = $data['has_options'];
            $this->_required_options = $data['required_options'];
            $this->_category_ids = (array) explode(',', $data['category_ids']);
        }

        return $data;
    }

    /**
     * @param bool $singleton
     * @return Mage_Catalog_Model_Product_Type_Abstract
     */
    public function getTypeInstance($singleton = false)
    {
        $typeInstance = $this->getMagentoProduct()->getTypeInstance($singleton);
        $typeInstance->setStoreFilter( (int) $this->getStoreId(), $this->getMagentoProduct());

        return $typeInstance;
    }

    /**
     * @param null|int $productId
     * @return string
     */
    public function getTypeId($productId = null)
    {
        if (!is_null($productId) && !is_null($this->_model) && $this->_model->getId() && $this->_model->getId() == $productId) {
            return $this->_model->getTypeId();
        }

        $productId = (int) (is_null($productId) ? $this->_productId : $productId);

        if (empty($productId) && empty($this->_typeid)) {
            return false;
        }

        if (empty($this->_typeid) || $productId != $this->_productId) {
            $this->getProductInformation($productId);
        }

        return $this->_typeid;
    }

    /**
     * @param null|int $productId
     * @return bool
     */
    public function getHasOptions($productId = null)
    {
        if (!is_null($productId) && !is_null($this->_model) && $this->_model->getId() && $this->_model->getId() == $productId) {
            return $this->_model->getHasOptions();
        }

        $productId = (int) (is_null($productId) ? $this->_productId : $productId);

        if (empty($productId) && empty($this->_has_options)) {
            return false;
        }

        if (empty($this->_has_options) || $productId != $this->_productId) {
            $this->getProductInformation($productId);
        }

        return (bool) $this->_has_options;
    }

    /**
     * @param null|int $productId
     * @return bool
     */
    public function getRequiredOptions($productId = null)
    {
        if (!is_null($productId) && !is_null($this->_model) && $this->_model->getId() && $this->_model->getId() == $productId) {
            return $this->_model->getRequiredOptions();
        }

        $productId = (int) (is_null($productId) ? $this->_productId : $productId);

        if (empty($productId) && empty($this->_required_options)) {
            return false;
        }

        if (empty($this->_required_options) || $productId != $this->_productId) {
            $this->getProductInformation($productId);
        }

        return (bool) $this->_required_options;
    }

    /**
     * @param null|int $productId
     * @return bool | array
     */
    public function getCategoryIds($productId = null)
    {
        if (!is_null($productId) && !is_null($this->_model) && $this->_model->getId() && $this->_model->getId() == $productId) {
            return $this->_model->getTypeId();
        }

        $productId = (int) (is_null($productId) ? $this->_productId : $productId);

        if (empty($productId) && empty($this->_category_ids)) {
            return false;
        }

        if (empty($this->_category_ids) || $productId != $this->_productId) {
            $this->getProductInformation($productId);
        }

        return (array) $this->_category_ids;
    }

    /**
     * @param null|int $productId
     * @param int $storeId
     * @param bool $sub
     * @return string
     */
    public function getTitle($productId = null, $storeId = null, $sub = true)
    {
        $productId = (int) (is_null($productId) ? $this->_productId : $productId);
        $storeId = (int) (is_null($storeId) ? $this->_storeId : $storeId);

        $titles = array(
            'ricardo_title',
            'name'
        );

        $returnedTitle = null;

        foreach ($this->getStoresList($storeId) as $id) {
            if (is_null($id)) {
                continue;
            }
            foreach ($titles as $title) {
                $returnedTitle = $this->_getProductVarchar($title, $productId, $id);
                if ($returnedTitle) {
                    break;
                }
            }
            if ($returnedTitle) {
                break;
            }
        }

        if (empty($returnedTitle)) {
            return '';
        } else if ($sub && !empty($returnedTitle)) {
            return mb_substr($returnedTitle, 0, Diglin_Ricento_Model_Validate_Products_Item::LENGTH_PRODUCT_TITLE);
        } else {
            return $returnedTitle;
        }
    }

    /**
     * @param null|int $productId
     * @param int $storeId
     * @param bool $sub
     * @return array|string
     */
    public function getSubtitle($productId = null, $storeId = null, $sub = true)
    {
        $productId = (int) (is_null($productId) ? $this->_productId : $productId);
        $storeId = (int) (is_null($storeId) ? $this->_storeId : $storeId);

        $subtitle = '';

        if ($this->getTypeId($productId) == Mage_Catalog_Model_Product_Type_Configurable::TYPE_CODE) {
            // @todo get subtitle from the current option
        }

        foreach ($this->getStoresList($storeId) as $id) {
            $subtitle = $this->_getProductVarchar('ricardo_subtitle', $productId, $id);
            if ($subtitle) {
                break;
            }
        }

        if (empty($subtitle)) {
            return '';
        } elseif ($sub) {
            return mb_substr($subtitle, 0, Diglin_Ricento_Model_Validate_Products_Item::LENGTH_PRODUCT_SUBTITLE);
        } else {
            return $subtitle;
        }
    }

    /**
     * @param null|int $productId
     * @param int $storeId
     * @param bool $sub
     * @return mixed|string
     */
    public function getDescription($productId = null, $storeId = null, $sub = true)
    {
        $productId = (int) (is_null($productId) ? $this->_productId : $productId);
        $storeId = (int) (is_null($storeId) ? $this->_storeId : $storeId);

        $descriptions = array(
            'ricardo_description',
            'description'
        );

        $returnedDescription = null;

        foreach ($this->getStoresList($storeId) as $id) {
            if (is_null($id)) {
                continue;
            }
            foreach ($descriptions as $description) {
                $returnedDescription = $this->_getProductText($description, $productId, $id);
                if ($returnedDescription) {
                    break;
                }
            }
            if ($returnedDescription) {
                break;
            }
        }

        if (empty($returnedDescription)) {
            return '';
        } else if ($sub) {
            return mb_substr($returnedDescription, 0, Diglin_Ricento_Model_Validate_Products_Item::LENGTH_PRODUCT_DESCRIPTION);
        } else {
            return $returnedDescription;
        }
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        //@todo do the conversion from a non supported currency to the supported currency - at the moment we do not support this feature

        $salesOptions = $this->getProductListingItem()->getSalesOptions();
        $price = $this->_getProductPrice($salesOptions->getPriceSourceAttributeCode());

        return Mage::helper('diglin_ricento/price')->calculatePriceChange($price, $salesOptions->getPriceChangeType(), $salesOptions->getPriceChange());
    }

    /**
     * @param null $productId
     * @return string
     */
    public function getSku($productId = null)
    {
        if (!is_null($productId) && !is_null($this->_model) && $this->_model->getId() && $this->_model->getId() == $productId) {
            return $this->_model->getSku();
        }

        $productId = (int) (is_null($productId) ? $this->_productId : $productId);

        if (empty($productId) && empty($this->_sku)) {
            return false;
        }

        if (empty($this->_sku) || $productId != $this->_productId) {
            $this->getProductInformation($productId);
        }

        return $this->_sku;
    }

    /**
     * @param null $productId
     * @param int $storeId
     * @return array
     */
    public function getCondition($productId = null, $storeId = Mage_Core_Model_App::ADMIN_STORE_ID)
    {
        return $this->_getProductVarchar('ricardo_condition', $productId, $storeId);
    }

    /**
     * @param int|null $productId
     * @return array|bool
     */
    public function getImages($productId = null)
    {
        if (is_null($productId) && $this->_model && $this->_model->getId()) {
            $productId = $this->_model->getId();
        } elseif (is_null($productId) && $this->_productId) {
            $productId = $this->_productId;
        }

        if (!is_numeric($productId)) {
            return false;
        }

        $mediaConfig = Mage::getSingleton('catalog/product_media_config');
        $images = $this->getAssignedImages($productId);

        foreach ($images as &$image) {
            if (isset($image['filepath'])) {
                $image['filepath'] = $mediaConfig->getMediaPath($image['filepath']);
            }
        }

        return $images;
    }

    /**
     * Return assigned images for specific stores
     *
     * @param int $productId
     * @return array
     *
     */
    public function getAssignedImages($productId = null)
    {
        $productId = (int) (is_null($productId) ? $this->_productId : $productId);

        $read = $this->_getReadConnection();
        $resource = $this->_getCoreResource();

        /**
         * Get the gallery images, only those enabled
         */
        $select = $read->select()
            ->from(
                array('mg' => $resource->getTableName('catalog/product_attribute_media_gallery')),
                array(
                    'filepath' => 'mg.value'
                )
            )
            ->joinLeft(
                array('mgv' => $resource->getTableName('catalog/product_attribute_media_gallery_value')),
                '(mg.value_id = mgv.value_id AND mgv.store_id = 0)',
                array()
            )
            ->where('entity_id IN(?)', $productId)
            ->where('disabled = 0')
            ->order(array('mgv.position ASC'));

        $mediaGallery = $read->fetchAll($select);

        /**
         * Get the Base image
         */
        $mainTable = Mage::getResourceModel('catalog/product')->getAttribute('image')
            ->getBackend()
            ->getTable();

        $select = $read->select()
            ->from(
                array('images' => $mainTable),
                array('value as filepath')
            )
            ->joinLeft(
                array('attr' => $resource->getTableName('eav/attribute')),
                'images.attribute_id = attr.attribute_id',
                array('attribute_code')
            )
            ->where('entity_id = ?', $productId)
            ->where('store_id = 0')
            ->where('attribute_code = ?', 'image');

        return array_merge($mediaGallery, $read->fetchAll($select));
    }

    /**
     * @return Mage_CatalogInventory_Model_Stock_Item
     * @throws Exception
     */
    public function getStockItem()
    {
        if (is_null($this->_model) && $this->_productId < 0) {
            throw new Exception('Product Model must be init first');
        }

        $productId = !is_null($this->_model) ? $this->_model->getId() : $this->_productId;

        return Mage::getModel('cataloginventory/stock_item')->loadByProduct($productId);
    }

    /**
     * @return bool|float
     */
    public function getQty()
    {
        if ($this->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE
        || $this->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_GROUPED) {
            return false;
        }

        $stockItem = $this->getStockItem();

        if ($stockItem->getIsQtyDecimal()) {
            return false;
        }

        return $stockItem->getQty();
    }

    /**
     * Check quantity
     *
     * @param   float $qty
     * @exception Mage_Core_Exception
     * @return  bool
     */
    public function checkQty($qty)
    {
        $stockItem = $this->getStockItem();

        if (!$stockItem->getManageStock()) {
            return true;
        }

        $composite = false;
        $usedProductIds = array();

        if ($this->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE) {
            /* @var $instance Mage_Catalog_Model_Product_Type_Configurable */
            $usedProductIds = $this->getTypeInstance(true)->getUsedProductIds($this->getMagentoProduct());
            $composite = true;
        } else if ($this->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_GROUPED) {
            /* @var $instance Mage_Catalog_Model_Product_Type_Grouped */
            $usedProductIds = $this->getAssociatedProductIds();
            $composite = true;
        }

        if ($composite) {
            foreach ($usedProductIds as $id) {
                $stockItem->unsetData(); // cleanup before to proceed, we may also use the method reset but it's not relevant here
                $stockItemProd = $stockItem->loadByProduct($id);
                if ($stockItemProd->getQty() - $stockItemProd->getMinQty() - $qty < 0) {
                    switch ($stockItemProd->getBackorders()) {
                        case Mage_CatalogInventory_Model_Stock::BACKORDERS_YES_NONOTIFY:
                        case Mage_CatalogInventory_Model_Stock::BACKORDERS_YES_NOTIFY:
                            break;
                        default:
                            return false;
                            break;
                    }
                }
            }
        } else {
            if ($stockItem->getQty() - $stockItem->getMinQty() - $qty < 0) {
                switch ($stockItem->getBackorders()) {
                    case Mage_CatalogInventory_Model_Stock::BACKORDERS_YES_NONOTIFY:
                    case Mage_CatalogInventory_Model_Stock::BACKORDERS_YES_NOTIFY:
                        break;
                    default:
                        return false;
                        break;
                }
            }
        }
        return true;
    }

    /**
     * @return Mage_Core_Model_Resource
     */
    protected function _getCoreResource()
    {
        return Mage::getSingleton('core/resource');
    }

    /**
     * @return Varien_Db_Adapter_Interface
     */
    protected function _getReadConnection()
    {
        return $this->_getCoreResource()->getConnection('core_read');
    }

    /**
     * @param string $field
     * @param null $productId
     * @param int $storeId
     * @return array
     */
    protected function _getProductVarchar($field, $productId = null, $storeId = Mage_Core_Model_App::ADMIN_STORE_ID)
    {
        $readConnection = $this->_getReadConnection();
        $coreResource = $this->_getCoreResource();

        $select = $readConnection
            ->select()
            ->from(array('cpev'=> $coreResource->getTableName('catalog_product_entity_varchar')), array($field => 'value'))
            ->join(
                array('ea' => $coreResource->getTableName('eav_attribute')),
                '`cpev`.`attribute_id` = `ea`.`attribute_id` AND `ea`.`attribute_code` = \''. $field .'\'',
                array()
            )
            ->where('`cpev`.`entity_id` = ?', $productId)
            ->where('`cpev`.`store_id` = ?', $storeId);

        return $readConnection->fetchOne($select);
    }

    /**
     * @param $field
     * @param null $productId
     * @param int $storeId
     * @return string
     */
    protected function _getProductText($field, $productId = null, $storeId = Mage_Core_Model_App::ADMIN_STORE_ID)
    {
        $readConnection = $this->_getReadConnection();

        $select = $readConnection
            ->select()
            ->from(array('cpet'=> $this->_getCoreResource()->getTableName('catalog_product_entity_text')), array($field => 'value'))
            ->join(
                array('ea' => $this->_getCoreResource()->getTableName('eav_attribute')),
                '`cpet`.`attribute_id` = `ea`.`attribute_id` AND `ea`.`attribute_code` = \''. $field .'\'',
                array()
            )
            ->where('`cpet`.`entity_id` = ?', $productId)
            ->where('`cpet`.`store_id` = ?', $storeId);

        return $readConnection->fetchOne($select);
    }

    /**
     * @param $field
     * @return string
     */
    protected function _getProductPrice($field = null)
    {
        switch ($this->getTypeId()) {
            case Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE:
                $price = $this->_getSimpleProductBasePrice($field);
                return $this->_getConfigurableProductBasePrice($price);
                break;
            case Mage_Catalog_Model_Product_Type::TYPE_GROUPED:
                return $this->_getGroupedProductBasePrice();
                break;
            case Mage_Catalog_Model_Product_Type::TYPE_SIMPLE:
            default:
                // @todo implement a factory adapter to get other kind of external product type
                return $this->_getSimpleProductBasePrice($field);
                break;
        }
    }

    /**
     * @param null $field
     * @param bool $withTax
     * @return float|string
     */
    protected function _getSimpleProductBasePrice($field = null, $withTax = true)
    {
        if (is_null($field)) {
            $field = 'price';
        }

        $readConnection = $this->_getReadConnection();

        $select = $readConnection
            ->select()
            ->from(array('cped'=> $this->_getCoreResource()->getTableName('catalog_product_entity_decimal')), array($field => 'value'))
            ->join(
                array('ea' => $this->_getCoreResource()->getTableName('eav_attribute')),
                '`cped`.`attribute_id` = `ea`.`attribute_id` AND `ea`.`attribute_code` = \''. $field .'\'',
                array()
            )
            ->where('`cped`.`entity_id` = ?', (int) $this->getProductId())
            ->where('`cped`.`store_id` = ?', $this->_defaultStoreId);

        $price = $readConnection->fetchOne($select);

        if ($field == 'special_price' && empty($price)) {
            $price = $this->_getSimpleProductBasePrice('price', false);
        }

        /**
         * Calculate price with incl tax if price catalog doesn't include it
         * @todo improve performance - Loading product is bad idea
         */
        if ($withTax) {
            $price = Mage::helper('tax')->getPrice($this->getMagentoProduct(), $price, true, null, null, null, $this->_defaultStoreId);
        }

        return $price;
    }

    /**
     * Associated Products for grouped products
     *
     * @return array
     */
    public function getAssociatedProducts()
    {
        if (!$this->isGroupedType()) {
            return null;
        }

        if (empty($this->_associatedProducts)) {
            /* @var $groupedInstance Mage_Catalog_Model_Product_Type_Grouped */
            $groupedInstance = $this->getTypeInstance(true);
            $this->_associatedProducts = $groupedInstance->getAssociatedProducts($this->getMagentoProduct());
        }
        return $this->_associatedProducts;
    }

    /**
     * Associated Product IDs for grouped products
     *
     * @return array|null
     */
    public function getAssociatedProductIds()
    {
        if (!$this->isGroupedType()) {
            return null;
        }

        if (empty($this->_associatedProducts)) {
            $associatedProducts = $this->getAssociatedProducts();
            foreach ($associatedProducts as $associatedProduct) {
                $this->_associatedProductIds[] = $associatedProduct->getId();
            }
        }

        return $this->_associatedProductIds;
    }

    /**
     * Get price of total associated products with default qty
     * Special price not supported by grouped product type
     *
     * @return float|null
     */
    protected function _getGroupedProductBasePrice()
    {
        if (!$this->isGroupedType()) {
            return null;
        }

        $defaultQty = 1;
        $totalPrice = 0.0;

        $associatedProducts = $this->getAssociatedProducts();

        if (!empty($associatedProducts)) {
            foreach ($associatedProducts as $associatedProduct) {

                // Ricardo is a C2C/B2C platform, price always with tax included
                $priceInclTax = Mage::helper('tax')->getPrice($associatedProduct, $associatedProduct->getPrice(), true, null, null, null, $this->_defaultStoreId);

                // Set default qty = 1 when qty = 0
                $totalPrice += (((!$associatedProduct->getQty()) ? $associatedProduct->getQty() : $defaultQty) * $priceInclTax);
            }
        }

        return $totalPrice;
    }

    /**
     * Used Products for configurable products
     *
     * @return array|null
     */
    public function getUsedProducts()
    {
        if (!$this->isConfigurableType()) {
            return null;
        }

        if (empty($this->_usedProducts)) {
            /* @var $configurableInstance Mage_Catalog_Model_Product_Type_Configurable */
            $configurableInstance = $this->getTypeInstance(true);
            $this->_usedProducts = $configurableInstance->getUsedProducts(null, $this->getMagentoProduct());
        }
        return $this->_usedProducts;
    }

    /**
     * @return array|null
     */
    public function getUsedProductIds()
    {
        if (!$this->isConfigurableType()) {
            return null;
        }

        if (empty($this->_usedProductIds)) {
            $usedProducts = $this->getUsedProducts();
            foreach ($usedProducts as $usedProduct) {
                $this->_usedProductIds[] = $usedProduct->getId();
            }
        }

        return $this->_usedProductIds;
    }

    public function getConfigurableAttributes()
    {
        if (!$this->isConfigurableType()) {
            return null;
        }

        if (empty($this->_configurable_attributes)) {
            $this->_configurable_attributes = Mage::getResourceModel('catalog/product_type_configurable_attribute_collection')
                ->setProductFilter($this->getMagentoProduct());
        }

        return $this->_configurable_attributes;
    }

    /**
     * @param float|int $productPrice
     * @return null|float
     */
    protected function _getConfigurableProductBasePrice($productPrice)
    {
        if (!$this->isConfigurableType()) {
            return $productPrice;
        }

        $attributes = $this->getConfigurableAttributes();

        $i = 0;
        $optionPrices = array();
        $finalMinPrice = 0;

        if (count($attributes)) {
            foreach ($attributes as $attribute) {
                /* @var Mage_Catalog_Model_Product_Type_Configurable_Attribute $attribute */
                if ($attribute->getData('prices')) {
                    $prices = $attribute->getData('prices');
                    foreach ($prices as $price) {
                        if ($price['pricing_value'] != 0) {
                            $optionPrices[$i][] = $this->_calcSelectionPrice($price, $productPrice);
                        }
                    }

                    if (isset($optionPrices[$i])) {
                        $finalMinPrice += min($optionPrices[$i]);
                    }
                    $i++;
                }
            }
        }

        return ($productPrice + $finalMinPrice);
    }

    /**
     * Calculate configurable product selection price
     *
     * @param   array $priceInfo
     * @param   float $productPrice
     * @return  float
     */
    protected function _calcSelectionPrice($priceInfo, $productPrice)
    {
        if($priceInfo['is_percent']) {
            $ratio = $priceInfo['pricing_value']/100;
            $price = $productPrice * $ratio;
        } else {
            $price = $priceInfo['pricing_value'];
        }
        return $price;
    }

    /**
     * @param int $productId
     * @return $this;
     */
    public function setProductId($productId)
    {
        $this->_productId = $productId;
        return $this;
    }

    /**
     * @return int
     */
    public function getProductId()
    {
        return $this->_productId;
    }

    /**
     * @param int $storeId
     * @return $this;
     */
    public function setStoreId($storeId)
    {
        $this->_storeId = $storeId;
        return $this;
    }

    /**
     * @return int
     */
    public function getStoreId()
    {
        return $this->_storeId;
    }

    /**
     * @param null $defaultStoreId
     * @return $this
     */
    public function setDefaultStoreId($defaultStoreId)
    {
        $this->_defaultStoreId = $defaultStoreId;
        return $this;
    }

    /**
     * @return null
     */
    public function getDefaultStoreId()
    {
        return $this->_defaultStoreId;
    }

    /**
     * @param \Diglin_Ricento_Model_Products_Listing_Item $productListingItem
     * @return $this;
     */
    public function setProductListingItem(Diglin_Ricento_Model_Products_Listing_Item $productListingItem)
    {
        $this->_productListingItem = $productListingItem;
        return $this;
    }

    /**
     * @return \Diglin_Ricento_Model_Products_Listing_Item
     */
    public function getProductListingItem()
    {
        if (empty($this->_productListingItem) && $this->getProductListingItemId()) {
            $this->_productListingItem = Mage::getModel('diglin_ricento/products_listing_item')->load($this->getProductListingItemId());
        }
        return $this->_productListingItem;
    }

    /**
     * @param int $productListingItemId
     * @return $this
     */
    public function setProductListingItemId($productListingItemId)
    {
        $this->_productListingItemId = (int) $productListingItemId;
        return $this;
    }

    /**
     * @return int
     */
    public function getProductListingItemId()
    {
        return $this->_productListingItemId;
    }

    /**
     * @return bool
     */
    public function isSimpleType()
    {
        return $this->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_SIMPLE;
    }

    /**
     * @return bool
     */
    public function isConfigurableType()
    {
        return $this->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE;
    }

    /**
     * @return bool
     */
    public function isGroupedType()
    {
        return $this->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_GROUPED;
    }
}
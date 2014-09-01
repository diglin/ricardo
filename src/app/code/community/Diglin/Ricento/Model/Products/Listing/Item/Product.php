<?php

/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
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
     * @var Mage_Catalog_Model_Product
     */
    protected $_model;

    /**
     * @var int
     */
    protected $_productListingItemId = null;

    /**
     * @var array
     */
    protected $_associatedProducts = array();

    /**
     * @var Diglin_Ricento_Model_Products_Listing_Item
     */
    protected $_productListingItem = null;

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
        $this->_productId = null;
        $this->_storeId = null;
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

        $this->setProductId($this->_model->getId());
        $this->setStoreId($this->_model->getStoreId());

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
     * @param null|int $productId
     * @return string
     */
    public function getTypeId($productId = null)
    {
        if (!is_null($this->_model) && ($this->_model->getId() == $productId || is_null($productId))) {
            return $this->_model->getTypeId();
        }

        $productId = (int) (is_null($productId) ? $this->_productId : $productId);

        $readConnection = $this->_getReadConnection();

        $select = $readConnection
            ->select()
            ->from($readConnection->getTableName('catalog_product_entity'), 'type_id')
            ->where('`entity_id` = ?', $productId);

        return $readConnection->fetchOne($select);
    }

    /**
     * @param bool $singleton
     * @return Mage_Catalog_Model_Product_Type_Abstract
     */
    public function getTypeInstance($singleton = false)
    {
        $typeInstance = $this->getMagentoProduct()->getTypeInstance($singleton);
        $typeInstance->setStoreFilter($this->getStoreId());

        return $typeInstance;
    }

    /**
     * @param null|int $productId
     * @param int $storeId
     * @param bool $sub
     * @return string
     */
    public function getTitle($productId = null, $storeId = Mage_Core_Model_App::ADMIN_STORE_ID, $sub = true)
    {
        $productId = (int) (is_null($productId) ? $this->_productId : $productId);
        $storeId = (int) (is_null($storeId) ? $this->_storeId : $storeId);

        $name = $this->_getProductName('name', $productId, $storeId);
        $ricardoTitle = $this->_getProductName('ricardo_title', $productId, $storeId);

        if ($storeId == Mage_Core_Model_App::ADMIN_STORE_ID && empty($name) && empty($ricardoTitle)) {
            return '';
        }

        // Get default values if nothing found
        if (empty($name)){
            $name = $this->_getProductName('name', $productId);
        }

        if (empty($ricardoTitle)) {
            $ricardoTitle = $this->_getProductName('ricardo_title', $productId);
        }

        // If really nothing found, return empty string
        if (empty($name) && empty($ricardoTitle)) {
            return '';
        }

        $title = $ricardoTitle;

        if (empty($title)) {
            $title = $name;
        }

        if ($sub && !empty($title)) {
            return mb_substr($title, 0, Diglin_Ricento_Model_Validate_Products_Item::LENGTH_PRODUCT_TITLE);
        } else {
            return $title;
        }
    }

    /**
     * @param null|int $productId
     * @param int $storeId
     * @param bool $sub
     * @return array|string
     */
    public function getSubtitle($productId = null, $storeId = Mage_Core_Model_App::ADMIN_STORE_ID, $sub = true)
    {
        $productId = (int) (is_null($productId) ? $this->_productId : $productId);
        $storeId = (int) (is_null($storeId) ? $this->_storeId : $storeId);

        $subtitle = $this->_getProductName('ricardo_subtitle', $productId, $storeId);

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
    public function getDescription($productId = null, $storeId = Mage_Core_Model_App::ADMIN_STORE_ID, $sub = true)
    {
        $productId = (int) (is_null($productId) ? $this->_productId : $productId);
        $storeId = (int) (is_null($storeId) ? $this->_storeId : $storeId);

        $productDescription = $this->_getProductDescription('description', $productId, $storeId);
        $ricardoDescription = $this->_getProductDescription('ricardo_description', $productId, $storeId);

        if ($storeId == Mage_Core_Model_App::ADMIN_STORE_ID && empty($productDescription) && empty($ricardoDescription)) {
            return '';
        }

        // Get default values if nothing found
        if (empty($productDescription)){
            $productDescription = $this->_getProductDescription('description', $productId);
        }

        if (empty($ricardoDescription)) {
            $ricardoDescription = $this->_getProductDescription('ricardo_description', $productId);
        }

        // If really nothing found, return empty string
        if (empty($productDescription) && empty($ricardoDescription)) {
            return '';
        }

        $description = $ricardoDescription;

        if (empty($description)) {
            $description = $productDescription;
        }


        if ($sub) {
            $description = mb_substr($description, 0, Diglin_Ricento_Model_Validate_Products_Item::LENGTH_PRODUCT_DESCRIPTION);
        }

        return Mage::helper('core')->escapeHtml($description);
    }

    /**
     * @param null|int $productId
     * @param int $storeId
     * @return float
     */
    public function getPrice($productId = null, $storeId = Mage_Core_Model_App::ADMIN_STORE_ID)
    {
        $productId = (int) (is_null($productId) ? $this->_productId : $productId);
        $storeId = (int) (is_null($storeId) ? $this->_storeId : $storeId);

        $salesOptions = $this->getProductListingItem()->getSalesOptions();

        //@todo do the conversion from a non supported currency to the supported currency - at the moment we do not support this feature

        $price = $this->_getProductPrice($salesOptions->getPriceSourceAttributeCode(), $productId, $storeId);

        return Mage::helper('diglin_ricento')->calculatePriceChange($price, $salesOptions->getPriceChangeType(), $salesOptions->getPriceChange());
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
            $usedProductIds = $this->getTypeInstance(true)->getAssociatedProductIds($this->getMagentoProduct());
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
     * @return Mage_CatalogInventory_Model_Stock_Item
     * @throws Exception
     */
    public function getStockItem()
    {
        if (is_null($this->_model) && $this->_productId < 0) {
            throw new Exception('Load instance first');
        }

        $productId = !is_null($this->_model) ? $this->_model->getId() : $this->_productId;

        return Mage::getModel('cataloginventory/stock_item')->loadByProduct($productId);
    }

    /**
     * @return Varien_Db_Adapter_Interface
     */
    protected function _getReadConnection()
    {
        return Mage::getResourceModel('core/config')->getReadConnection();
    }

    /**
     * @param string $field
     * @param null $productId
     * @param int $storeId
     * @return array
     */
    protected function _getProductName($field, $productId = null, $storeId = Mage_Core_Model_App::ADMIN_STORE_ID)
    {
        $readConnection = $this->_getReadConnection();
        $coreResource = Mage::getSingleton('core/resource');

        $select = $readConnection
            ->select()
            ->from(array('cpev'=> $coreResource->getTableName('catalog_product_entity_varchar')), array($field => 'value'))
            ->join(
                array('ea' => $coreResource->getTableName('eav_attribute')),
                '`cpev`.`attribute_id` = `ea`.`attribute_id` AND `ea`.`attribute_code` = \''. $field .'\'',
                array()
            )
            ->where('`cpev`.`entity_id` = ?', $productId)->where('`cpev`.`store_id` = ?', $storeId);

        return $readConnection->fetchOne($select);
    }

    /**
     * @param $field
     * @param null $productId
     * @param int $storeId
     * @return string
     */
    protected function _getProductDescription($field, $productId = null, $storeId = Mage_Core_Model_App::ADMIN_STORE_ID)
    {
        $readConnection = $this->_getReadConnection();
        $coreResource = Mage::getSingleton('core/resource');

        $select = $readConnection
            ->select()
            ->from(array('cpet'=> $coreResource->getTableName('catalog_product_entity_text')), array($field => 'value'))
            ->join(
                array('ea' => $coreResource->getTableName('eav_attribute')),
                '`cpet`.`attribute_id` = `ea`.`attribute_id` AND `ea`.`attribute_code` = \''. $field .'\'',
                array()
            )
            ->where('`cpet`.`entity_id` = ?', $productId)->where('`cpet`.`store_id` = ?', $storeId);

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
     * @param $field
     * @return string
     */
    protected function _getSimpleProductBasePrice($field)
    {
        if (is_null($field)) {
            $field = 'price';
        }

        $productId = (int) $this->getProductId();
        $storeId = (int) $this->getStoreId();

        $readConnection = $this->_getReadConnection();
        $coreResource = Mage::getSingleton('core/resource');

        $select = $readConnection
            ->select()
            ->from(array('cped'=> $coreResource->getTableName('catalog_product_entity_decimal')), array($field => 'value'))
            ->join(
                array('ea' => $coreResource->getTableName('eav_attribute')),
                '`cped`.`attribute_id` = `ea`.`attribute_id` AND `ea`.`attribute_code` = \''. $field .'\'',
                array()
            )
            ->where('`cped`.`entity_id` = ?', $productId)
            ->where('`cped`.`store_id` = ?', $storeId);

        return $readConnection->fetchOne($select);
    }

    /**
     * Get price of total associated products with default qty
     * Special price not supported by grouped product type
     *
     * @return int|null
     */
    protected function _getGroupedProductBasePrice()
    {
        if ($this->getTypeId() != Mage_Catalog_Model_Product_Type::TYPE_GROUPED) {
            return null;
        }

        $defaultQty = 1;
        $totalPrice = 0.0;

        if (empty($this->_associatedProducts)) {
            /* @var $groupedInstance Mage_Catalog_Model_Product_Type_Grouped */
            $groupedInstance = $this->getTypeInstance(true);
            $this->_associatedProducts = $groupedInstance->getAssociatedProducts($this->getMagentoProduct());
        }

        if (!empty($this->_associatedProducts)) {
            foreach ($this->_associatedProducts as $associatedProduct) {

                // Ricardo is a C2C/B2C platform, price always with tax included
                $priceInclTax = Mage::helper('tax')->getPrice($associatedProduct, $associatedProduct->getPrice(), true, null, null, null, $this->getStoreId());

                // Set default qty = 1 when qty = 0
                $totalPrice += (((!$associatedProduct->getQty()) ? $associatedProduct->getQty() : $defaultQty) * $priceInclTax);
            }
        }

        return $totalPrice;
    }

    /**
     * @param float|int $productPrice
     * @return null
     */
    protected function _getConfigurableProductBasePrice($productPrice)
    {
        if ($this->getTypeId() != Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE) {
            return null;
        }

        /* @var $configurableInstance Mage_Catalog_Model_Product_Type_Configurable */
        $attributes = $this->getTypeInstance(true)->getConfigurableAttributes($this->getMagentoProduct());

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
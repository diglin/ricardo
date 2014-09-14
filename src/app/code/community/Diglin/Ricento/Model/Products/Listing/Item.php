<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

use Diglin\Ricardo\Core\Helper;
use Diglin\Ricardo\Enums\Article\InternalReferenceType;
use Diglin\Ricardo\Enums\Article\PromotionCode;
use Diglin\Ricardo\Enums\PictureExtension;
use Diglin\Ricardo\Enums\System\CategoryBrandingFilter;
use Diglin\Ricardo\Managers\Sell\Parameter\ArticleDeliveryParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\ArticleDescriptionParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\ArticleInformationParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\ArticleInternalReferenceParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\ArticlePictureParameter;
use Diglin\Ricardo\Managers\Sell\Parameter\InsertArticleParameter;

/**
 * Products_Listing_Item Model
 *
 * @method int    getProductId()
 * @method int    getStoreId()
 * @method int    getDefaultStoreId()
 * @method int    getRicardoArticleId()
 * @method int    getProductsListingId()
 * @method int    getSalesOptionsId()
 * @method int    getRuleId()
 * @method string getStatus()
 * @method bool   getReload()
 * @method DateTime getCreatedAt()
 * @method DateTime getUpdatedAt()
 * @method bool     getLoadFallbackOptions()
 * @method Diglin_Ricento_Model_Products_Listing_Item setProductId(int $productId)
 * @method Diglin_Ricento_Model_Products_Listing_Item setStoreId(int $storeId)
 * @method Diglin_Ricento_Model_Products_Listing_Item setDefaultStoreId(int $storeId)
 * @method Diglin_Ricento_Model_Products_Listing_Item setRicardoArticleId(int $ricardoArticleId)
 * @method Diglin_Ricento_Model_Products_Listing_Item setProductsListingId(int $productListingId)
 * @method Diglin_Ricento_Model_Products_Listing_Item setSalesOptionsId(int $salesOptionsId)
 * @method Diglin_Ricento_Model_Products_Listing_Item setRuleId(int $ruleIid)
 * @method Diglin_Ricento_Model_Products_Listing_Item setStatus(string $status)
 * @method Diglin_Ricento_Model_Products_Listing_Item setReload(bool $reload)
 * @method Diglin_Ricento_Model_Products_Listing_Item setCreatedAt(DateTime $createdAt)
 * @method Diglin_Ricento_Model_Products_Listing_Item setUpdatedAt(DateTime $updatedAt)
 * @method Diglin_Ricento_Model_Products_Listing_Item setLoadFallbackOptions(bool $loadFallbackOptions)
 */
class Diglin_Ricento_Model_Products_Listing_Item extends Mage_Core_Model_Abstract
{
    /**
     * @var Diglin_Ricento_Model_Sales_Options
     */
    protected $_salesOptions;

    /**
     * @var Diglin_Ricento_Model_Rule
     */
    protected $_shippingPaymentRule;

    /**
     * Prefix of model events names
     * @var string
     */
    protected $_eventPrefix = 'products_listing_item';

    /**
     * Parameter name in event
     * In observe method you can use $observer->getEvent()->getObject() in this case
     * @var string
     */
    protected $_eventObject = 'products_listing_item';

    /**
     * @var Diglin_Ricento_Model_Products_Listing
     */
    protected $_productsListing;

    /**
     * Products_Listing_Item Constructor
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('diglin_ricento/products_listing_item');
    }

    /**
     * @return Diglin_Ricento_Model_Products_Listing_Item
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();

        $this->setUpdatedAt(Mage::getSingleton('core/date')->gmtDate());

        if ($this->hasDataChanges() && $this->getStatus() == Diglin_Ricento_Helper_Data::STATUS_READY) {
            $this->setStatus(Diglin_Ricento_Helper_Data::STATUS_PENDING);
        }

        return $this;
    }

    /**
     * @return $this|Mage_Core_Model_Abstract
     */
    protected function _afterDeleteCommit()
    {
        if ($this->getSalesOptionsId()) {
            $this->getSalesOptions()->delete();
        }

        if ($this->getRuleId()) {
            $this->getShippingPaymentRule()->delete();
        }

        parent::_afterDeleteCommit();
        return $this;
    }

    /**
     * @return Diglin_Ricento_Model_Products_Listing_Item_Product
     */
    public function getProduct()
    {
        $reload = $this->getReload();

        $itemProduct = Mage::getSingleton('diglin_ricento/products_listing_item_product');

        if ($reload) {
            // To use with precaution - it's a bottleneck
            $itemProduct->reset();
            $this->setReload(false);
        }

        return $itemProduct
            ->setProductListingItem($this)
            ->setStoreId($this->getStoreId())
            ->setDefaultStoreId($this->getDefaultStoreId()) // fallback for language
            ->setProductId($this->getProductId());
    }

    /**
     * @return Diglin_Ricento_Model_Sales_Options
     */
    public function getSalesOptions()
    {
        if (!$this->_salesOptions) {
            $this->_salesOptions = Mage::getModel('diglin_ricento/sales_options');
            if ($this->getSalesOptionsId()) {
                $this->_salesOptions->load($this->getSalesOptionsId());
            } elseif ($this->getLoadFallbackOptions()) {
                $this->_salesOptions = $this->getProductsListing()->getSalesOptions();
            }
        }
        return $this->_salesOptions;
    }

    /**
     * @return Diglin_Ricento_Model_Rule
     */
    public function getShippingPaymentRule()
    {
        if (!$this->_shippingPaymentRule) {
            $this->_shippingPaymentRule = Mage::getModel('diglin_ricento/rule');
            if ($this->getRuleId()) {
                $this->_shippingPaymentRule->load($this->getRuleId());
            } elseif ($this->getLoadFallbackOptions()) {
                $this->_shippingPaymentRule = $this->getProductsListing()->getShippingPaymentRule();
            }
        }
        return $this->_shippingPaymentRule;
    }

    /**
     * @return Diglin_Ricento_Model_Products_Listing
     */
    public function getProductsListing()
    {
        if (empty($this->_productsListing) || !$this->_productsListing->getId()) {
            $this->_productsListing = Mage::getSingleton('diglin_ricento/products_listing')->load($this->getProductsListingId());
        }
        return $this->_productsListing;
    }

    /**
     * @return int
     */
    public function getCategory()
    {
        $ricardoCategoryId = $this->getSalesOptions()->getRicardoCategory();
        if ($ricardoCategoryId < 0) {
            $catIds = $this->getMagentoProduct()->getCategoryIds();
            foreach ($catIds as $id) {
                $category = Mage::getModel('catalog/category')->load($id);
                $ricardoCategoryId = $category->getRicardoCategory();
                if ($ricardoCategoryId) {
                    break;
                }
            }
        }

        return (int) $ricardoCategoryId;
    }

    /**
     * @return Mage_Catalog_Model_Product
     */
    public function getMagentoProduct()
    {
        return $this->getProduct()->getMagentoProduct();
    }

    /**
     * @param bool $sub
     * @return string
     */
    public function getProductTitle($sub = true)
    {
        return $this->getProduct()->getTitle($this->getProductId(), $this->getStoreId(), $sub);
    }

    /**
     * @param bool $sub
     * @return array|string
     */
    public function getProductSubtitle($sub = true)
    {
        return $this->getProduct()->getSubtitle($this->getProductId(), $this->getStoreId(), $sub);
    }

    /**
     * @param bool $sub
     * @return mixed|string
     */
    public function getProductDescription($sub = true)
    {
        return $this->getProduct()->getDescription($this->getProductId(), $this->getStoreId(), $sub);
    }

    /**
     * @return float
     */
    public function getProductPrice()
    {
        // We take the price from default store view
        return $this->getProduct()
            ->setProductId($this->getProductId())
            ->setStoreId($this->getDefaultStoreId())
            ->getPrice();
    }

    /**
     * @return int
     */
    public function getProductQty()
    {
        if ($this->getSalesOptions()->getStockManagement() == -1) {
            return $this->getProduct()->getQty();
        } else {
            return $this->getSalesOptions()->getStockManagement();
        }
    }

    /**
     * @return int
     */
    public function getProductSku()
    {
        return $this->getProduct()->getSku($this->getProductId());
    }

    /**
     * @return string
     */
    public function getProductCondition()
    {
        $salesOptions = $this->getSalesOptions();
        $sourceCondition = $salesOptions->getProductConditionSourceAttributeCode();

        if (!empty($sourceCondition)) {
            $condition = $this->getProduct()->getCondition();
            if (!empty($condition)) {
                return $condition;
            }
        }

        return $salesOptions->getProductCondition();
    }

    /**
     * Define a list of store IDs for each supported and expected language
     * Define a default one in case of accept all languages
     *
     * @return array
     */
    protected function _prepareStoresLanguage()
    {
        // Prepare language and store id for each language
        $storesLang = array();
        $defaultLang = null;
        $publishLanguages = $this->getProductsListing()->getPublishLanguages();

        if ($publishLanguages == 'all') {
            $languages = Mage::helper('diglin_ricento')->getSupportedLang();
            $defaultLang = $this->getProductsListing()->getDefaultLanguage();
            foreach ($languages as $language) {
                $method = 'getLangStoreId' . ucwords($language);
                $storesLang[$language] = $this->$method();
                if ($defaultLang == $language) {
                    $this->setDefaultStoreId($storesLang[$language]);
                }
            }
        } else {
            $method = 'getLangStoreId' . ucwords($publishLanguages);
            $storesLang[$publishLanguages] = $this->$method();
        }

        return $storesLang;
    }

    /**
     * @return InsertArticleParameter
     */
    public function getInsertArticleParameter()
    {
        $insertArticleParameter = new InsertArticleParameter();

        $this->_shippingPaymentRule = $this->getShippingPaymentRule();
        $this->_salesOptions = $this->getSalesOptions();

        //** Article Description

        foreach ($this->_prepareStoresLanguage() as $language => $storeId) {
            $this->setStoreId($storeId);
            $insertArticleParameter->setDescriptions($this->_getArticleDescriptions($language));
        }

        //** Article Images

        $images = $this->getProduct()->getAssignedImages($this->getProductId());
        $i = 0;
        foreach ($images as $image) {
            if (isset($image['filepath']) && file_exists($image['filepath'])) {

                // Prepare picture to set the content as byte array for the webservice
                $imageContent = array_values(unpack('C*', file_get_contents($image['filepath'])));

                $picture = new ArticlePictureParameter();
                $picture
                    ->setPictureBytes($imageContent)
                    ->setPictureExtension(Helper::getPictureExtension($image['filepath']))
                    ->setPictureIndex(++$i);

                $insertArticleParameter->setPictures($picture);
                $imageContent = null;
            }
        }

        $securtiy = Mage::getSingleton('diglin_ricento/api_services_security');
        $antiforgeryToken = $securtiy->getServiceModel()->getAntiforgeryToken();

        $insertArticleParameter
            ->setAntiforgeryToken($antiforgeryToken)
            ->setArticleInformation($this->_getArticleInformation())
            ->setIsUpdateArticle(false);

        return $insertArticleParameter;
    }

    /**
     * @return ArticleDeliveryParameter
     */
    protected function _getArticleDeliveryParameter()
    {
        $shippingPrice = $this->_shippingPaymentRule->getShippingPrice();
        $freeShipping = false;
        if (floatval($shippingPrice) <= 0) {
            $freeShipping = true;
        }

        $delivery = new ArticleDeliveryParameter();

        $delivery
            // required
            ->setDeliveryCost($this->_shippingPaymentRule->getShippingPrice())
            ->setIsDeliveryFree($freeShipping)
            ->setDeliveryId($this->_shippingPaymentRule->getShippingMethod())
            ->setIsCumulativeShipping($this->_shippingPaymentRule->getShippingCumulativeFee())
            // optional
            ->setDeliveryPackageSizeId($this->_shippingPaymentRule->getShippingPackage());

        return $delivery;
    }

    /**
     * @return ArticleInternalReferenceParameter
     */
    protected function _getInternalReferences()
    {
        $internalReferences = new ArticleInternalReferenceParameter();

        $internalReferences
            ->setInternalReferenceTypeId(InternalReferenceType::SELLERSPECIFIC)
            ->setInternalReferenceValue($this->getProductSku());

        return $internalReferences;
    }

    /**
     * @return ArticleInformationParameter
     */
    protected function _getArticleInformation()
    {
        $promotionIds = array();
        $paymentConditions = array();

        $system = Mage::getSingleton('diglin_ricento/api_services_system');
        $paymentMethods = (array) $this->_shippingPaymentRule->getPaymentMethods();

        // @todo fix the data returned from payment conditions below
        /**
         * PaymentConditionIds] => Array
        (
        [0] => Array
        (
        [0] => Array
        (
        [PaymentConditionId] => 5
        [PaymentConditionText] => im Voraus
        [PaymentMethods] =>
        )

        [1] => Array
        (
        [PaymentConditionId] => 1
        [PaymentConditionText] => bei Abholung
        [PaymentMethods] =>
        )

        [2] => Array
        (
        [PaymentConditionId] => 0
        [PaymentConditionText] => Gemäss Beschreibung
        [PaymentMethods] =>
        )

        )

        )

         */

        foreach ($paymentMethods as $paymentMethod) {
            $paymentConditions[] = $system->getPaymentConditions($paymentMethod);
        }

        $customTemplate = ($this->_salesOptions->getCustomizationTemplate()) ? $this->_salesOptions->getCustomizationTemplate() : null;

        $articleInformation = new ArticleInformationParameter();
        $articleInformation
            // required
            ->setArticleConditionId($this->getProductCondition())
            ->setArticleDuration($this->_salesOptions->getSchedulePeriodDays())
            ->setAvailabilityId($this->_shippingPaymentRule->getShippingAvailaibility())
            ->setCategoryId($this->getCategory())
            ->setInitialQuantity($this->getProductQty())
            ->setIsCustomerTemplate(false)
            ->setIsRelistSoldOut(false)
            ->setMainPictureId(1)
            ->setMaxRelistCount($this->_salesOptions->getScheduleReactivation())
            ->setWarrantyId($this->_salesOptions->getProductWarranty())
            ->setDeliveries($this->_getArticleDeliveryParameter())
            // optional
            ->setInternalReferences($this->_getInternalReferences())
            ->setPaymentConditionIds($paymentConditions)
            ->setPaymentMethodIds($paymentMethods)
            ->setTemplateId($customTemplate);

        if (!is_null($startDate)) {
            $startDate = strtotime($startDate);

            if ($startDate < (time() + 60*60)) {
                $startDate = time() + 60*60;
            }

            $articleInformation
                ->setStartDate(Helper::getJsonDate($startDate));
        }

        if ($this->_salesOptions->getSalesType() == Diglin_Ricento_Model_Config_Source_Sales_Type::AUCTION) {
            $articleInformation
                ->setIncrement($this->_salesOptions->getSalesAuctionIncrement())
                ->setStartPrice($this->_salesOptions->getSalesAuctionStartPrice());

            if ($this->_salesOptions->getSalesAuctionDirectBuy()) {
            $promotionIds[] = PromotionCode::BUYNOW;
            }
        }

        if ($this->_salesOptions->getSalesType() == Diglin_Ricento_Model_Config_Source_Sales_Type::BUYNOW || $this->_salesOptions->getSalesAuctionDirectBuy()) {
            $articleInformation->setBuyNowPrice($this->getProductPrice());
        }

        //** Promotions

        $space = $this->_salesOptions->getPromotionSpace();
        if ($space) {
            $promotionIds[] = (int) $space;
        }

        $startSpace = $this->_salesOptions->getPromotionStartPage();
        if ($startSpace) {
            $promotionIds[] = (int) $startSpace;
        }

        // required
        $articleInformation->setPromotionIds($promotionIds);

        return $articleInformation;
    }

    /**
     * @param string $lang
     * @return ArticleDescriptionParameter
     */
    protected function _getArticleDescriptions($lang = Diglin_Ricento_Helper_Data::DEFAULT_SUPPORTED_LANG)
    {
        $descriptions = new ArticleDescriptionParameter();

        $descriptions
            // required
            ->setArticleTitle($this->getProductTitle())
            ->setArticleDescription($this->getProductDescription())
            ->setLanguageId(Mage::helper('diglin_ricento')->getRicardoLanguageIdFromLocaleCode($lang))
            // optional
            ->setArticleSubtitle($this->getProductSubtitle())
            ->setDeliveryDescription($this->_shippingPaymentRule->getShippingDescription($lang))
            ->setPaymentDescription($this->_shippingPaymentRule->getPaymentDescription($lang))
            ->setWarrantyDescription($this->_salesOptions->getProductWarrantyDescription($lang));

        return $descriptions;
    }
}
<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <support at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Class Diglin_Ricento_Model_Sales_Transaction
 *
 * @method int getBidId()
 * @method int getOrderId()
 * @method int getWebsiteId()
 * @method int getAddressId()
 * @method int getCustomerId()
 * @method int getLanguageId()
 * @method int getRicardoCustomerId()
 * @method int getRicardoArticleId()
 * @method int getProductId()
 * @method int getQty()
 * @method int getViewCount()
 * @method int getTotalBidPrice()
 * @method string   getPaymentMethods()
 * @method string   getPaymentDescription()
 * @method int      getShippingFee()
 * @method int      getShippingMethod()
 * @method string   getShippingText()
 * @method string   getShippingDescription()
 * @method int      getShippingCumulativeFee()
 * @method string getRawData()
 * @method string getSoldAt()
 * @method string getUpdateAt()
 * @method string getCreatedAt()
 *
 * @method Diglin_Ricento_Model_Sales_Transaction setBidId(int $bidId)
 * @method Diglin_Ricento_Model_Sales_Transaction setOrderId(int $orderId)
 * @method Diglin_Ricento_Model_Sales_Transaction setWebsiteId(int $websiteId)
 * @method Diglin_Ricento_Model_Sales_Transaction setAddressId(int $addressId)
 * @method Diglin_Ricento_Model_Sales_Transaction setCustomerId(int $customerId)
 * @method Diglin_Ricento_Model_Sales_Transaction setLanguageId(int $languageId)
 * @method Diglin_Ricento_Model_Sales_Transaction setRicardoCustomerId(int $ricardoCustomerId)
 * @method Diglin_Ricento_Model_Sales_Transaction setRicardoArticleId(int $ricardoArticleId)
 * @method Diglin_Ricento_Model_Sales_Transaction setProductId(int $productId)
 * @method Diglin_Ricento_Model_Sales_Transaction setQty(int $qty)
 * @method Diglin_Ricento_Model_Sales_Transaction setViewCount(int $viewCount)
 * @method Diglin_Ricento_Model_Sales_Transaction setTotalBidPrice(float $totalBidPrice)
 * @method Diglin_Ricento_Model_Sales_Transaction setPaymentMethods(string $paymentMethods)
 * @method Diglin_Ricento_Model_Sales_Transaction setPaymentDescription(string $paymentDescription)
 * @method Diglin_Ricento_Model_Sales_Transaction setShippingFee(int $shippingFee)
 * @method Diglin_Ricento_Model_Sales_Transaction setShippingMethod(string $shippingMethod)
 * @method Diglin_Ricento_Model_Sales_Transaction setShippingText(string $shippingText)
 * @method Diglin_Ricento_Model_Sales_Transaction setShippingDescription(string $shippingDescription)
 * @method Diglin_Ricento_Model_Sales_Transaction setShippingCumulativeFee(string $cumulativeShippingFee)
 * @method Diglin_Ricento_Model_Sales_Transaction setRawData(string $rawData)
 * @method Diglin_Ricento_Model_Sales_Transaction setSoldAt(string $soldAt)
 * @method Diglin_Ricento_Model_Sales_Transaction setUpdateAt(string $updatedAt)
 * @method Diglin_Ricento_Model_Sales_Transaction setCreatedAt(string $createdAt)
 */
class Diglin_Ricento_Model_Sales_Transaction extends Mage_Core_Model_Abstract
{

    protected function _construct()
    {
        $this->_init('diglin_ricento/sales_transaction');
    }

    /**
     * Set date of last update
     *
     * @return Diglin_Ricento_Model_Sales_Options
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        $this->setUpdatedAt(Mage::getSingleton('core/date')->gmtDate());
        return $this;
    }
}
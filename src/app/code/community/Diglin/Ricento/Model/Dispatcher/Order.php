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

use \Diglin\Ricardo\Managers\SellerAccount\Parameter\SoldArticlesParameter;

/**
 * Class Diglin_Ricento_Model_Dispatcher_Order
 */
class Diglin_Ricento_Model_Dispatcher_Order extends Diglin_Ricento_Model_Dispatcher_Abstract
{
    /**
     * @var int
     */
    protected $_logType = Diglin_Ricento_Model_Products_Listing_Log::LOG_TYPE_ORDER;

    /**
     * @var string
     */
    protected $_jobType = Diglin_Ricento_Model_Sync_Job::TYPE_ORDER;

    /**
     * @return $this
     */
    public function proceed()
    {
        $jobType = Diglin_Ricento_Model_Sync_Job::TYPE_ORDER;

        $productsListingResource = Mage::getResourceModel('diglin_ricento/products_listing');
        $readListingConnection = $productsListingResource->getReadConnection();
        $select = $readListingConnection->select()
            ->from($productsListingResource->getTable('diglin_ricento/products_listing'), 'entity_id');

        $listingIds = $readListingConnection->fetchCol($select);

        foreach ($listingIds as $listingId) {
            $itemResource = Mage::getResourceModel('diglin_ricento/products_listing_item');
            $readConnection = $itemResource->getReadConnection();
            $select = $readConnection->select()
                ->from($itemResource->getTable('diglin_ricento/products_listing_item'), 'item_id')
                ->where('products_listing_id = :id')
                ->where('status = :status');

            $binds = array('id' => $listingId, 'status' => Diglin_Ricento_Helper_Data::STATUS_LISTED);
            $countListedItems = count($readConnection->fetchAll($select, $binds));

            if ($countListedItems == 0) {
                continue;
            }

            $job = Mage::getModel('diglin_ricento/sync_job');
            $job
                ->setJobType($jobType)
                ->setProgress(Diglin_Ricento_Model_Sync_Job::PROGRESS_PENDING)
                ->setJobMessage(array($job->getJobMessage()))
                ->save();

            $jobListing = Mage::getModel('diglin_ricento/sync_job_listing');
            $jobListing
                ->setProductsListingId($listingId)
                ->setTotalCount($countListedItems)
                ->setTotalProceed(0)
                ->setJobId($job->getId())
                ->save();
        }

        return parent::proceed();
    }

    /**
     * @return mixed|void
     */
    protected function _proceed()
    {
        $itemCollection = $this->_getItemCollection(array(Diglin_Ricento_Helper_Data::STATUS_LISTED));
        $itemCollection
            ->addFieldToFilter('is_planned', 0);

        /* @var $item Diglin_Ricento_Model_Products_Listing_Item */
        foreach ($itemCollection->getItems() as $item) {

            try {
                $this->getSoldArticles(array($item->getRicardoArticleId()), $item);
            } catch (Exception $e) {
                $this->_handleException($e, Mage::getSingleton('diglin_ricento/api_services_selleraccount'));
                $e = null;
                // keep going for the next item - no break
            }

            /**
             * Save item information and eventual error messages
             */
            if (!is_null($this->_itemMessage)) {
                $this->_getListingLog()->saveLog(array(
                    'job_id' => $this->_currentJob->getId(),
                    'product_title' => $item->getProductTitle(),
                    'products_listing_id' => $this->_productsListingId,
                    'product_id' => $item->getProductId(),
                    'message' => $this->_jsonEncode($this->_itemMessage),
                    'log_status' => $this->_itemStatus,
                    'log_type' => $this->_logType
                ));
            }

            /**
             * Save the current information of the process to allow live display via ajax call
             */
            $this->_currentJobListing->saveCurrentJob(array(
                'total_proceed' => ++$this->_totalProceed,
                'last_item_id' => $item->getId()
            ));

            $this->_itemMessage = null;
            $this->_itemStatus = null;
        }
    }

    /**
     * @param array $articleIds
     * @param Diglin_Ricento_Model_Products_Listing_Item $productItem
     * @return bool
     * @throws Exception
     */
    public function getSoldArticles($articleIds = array(), Diglin_Ricento_Model_Products_Listing_Item $productItem = null)
    {
        $soldArticlesParameter = new SoldArticlesParameter();

        /**
         * Set minimum end date to filter e.g. last day. Do not use a higher value as the minimum sales duration is 1 day,
         * we prevent to have conflict with several sold articles having similar internal reference
         */
        $soldArticlesParameter
            ->setPageSize($this->_limit) // if not defined, default is 10
            ->setArticleIdsFilter($articleIds)
            ->setMinimumEndDate($this->_getHelper()->getJsonDate(time() - (3 * 24 * 60 * 60)));

        $sellerAccountService = Mage::getSingleton('diglin_ricento/api_services_selleraccount')->setCanUseCache(false);
        $sellerAccountService->setCurrentWebsite($this->_getListing()->getWebsiteId());

        $soldArticles = $sellerAccountService
            ->getSoldArticles($soldArticlesParameter);

        $soldArticles = array_reverse($soldArticles);

        foreach ($soldArticles as $soldArticle) {

            $rawData = $soldArticle;
            $soldArticle = $this->_getHelper()->extractData($soldArticle);
            $transaction = $soldArticle->getTransaction();

            $listing = $this->_getListing();

            if ($transaction && count($transaction) > 0) {

                /**
                 * 1. Check that the transaction doesn't already exists
                 */
                $salesTransaction = Mage::getModel('diglin_ricento/sales_transaction')
                    ->load($transaction->getBidId(), 'bid_id');

                if ($salesTransaction->getId()) {
                    continue;
                }

                /**
                 * 2. Check if the products listing item exists and is listed
                 */
                $references = $soldArticle->getArticleInternalReferences();
                if (!isset($references[0]['InternalReferenceValue'])) {
                    continue;
                }
                $extractedInternReference = Mage::helper('diglin_ricento')->extractInternalReference($references[0]['InternalReferenceValue']);

                if (!($extractedInternReference instanceof Varien_Object)) {
                    continue;
                }

                if (is_null($productItem) || $productItem->getId() != $extractedInternReference->getItemId()) {
                    $productItem = Mage::getModel('diglin_ricento/products_listing_item')->load($extractedInternReference->getItemId());
                }

                if (!$productItem->getId() || $productItem->getStatus() != Diglin_Ricento_Helper_Data::STATUS_LISTED) {
                    continue;
                }

                /**
                 * 3. Create customer if not exist and set his default billing address
                 */
                $customer = $this->_getCustomer($transaction->getBuyer(), $listing->getWebsiteId());
                $buyerAddress = $transaction->getBuyer()->getAddresses();

                if ($customer) {

                    $address = $customer->getDefaultBillingAddress();

                    $street = $buyerAddress->getAddress1() . "\n" . $buyerAddress->getAddress2() . "\n" . $buyerAddress->getPostalBox();
                    $postCode = $buyerAddress->getZipCode();
                    $city = $buyerAddress->getCity();

                    if (!$address || ($address->getCity() != $city && $address->getPostcode() != $postCode && $address->getStreet() != $street)) {

                        /**
                         * Ricardo API doesn't provide the region and Magento 1.6 doesn't allow to make region optional
                         * We use the first region found for the current country but it's far to be good
                         * @todo add a "other" region into each country having required region
                         */
                        $countryId = $this->_getCountryId($buyerAddress->getCountry());
                        $regionId = null;
                        if (Mage::helper('directory')->isRegionRequired($countryId)) {
                            $regionId = Mage::getModel('directory/region')->getCollection()
                                ->addFieldToFilter('country_id', $countryId)
                                ->getFirstItem()
                                ->getId();
                        }

                        $phone = ($transaction->getBuyer()->getPhone()) ? $transaction->getBuyer()->getPhone() : $transaction->getBuyer()->getMobile();

                        $address = Mage::getModel('customer/address');
                        $address
                            ->setCustomerId($customer->getId())
                            ->setCompany($transaction->getBuyer()->getCompanyName())
                            ->setLastname($customer->getLastname())
                            ->setFirstname($customer->getFirstname())
                            ->setStreet($street)
                            ->setPostcode($postCode)
                            ->setCity($city)
                            ->setRegionId($regionId)
                            ->setCountryId($countryId)
                            ->setTelephone($phone)
                            ->setIsDefaultBilling(true)
                            ->setIsDefaultShipping(true)
                            ->setSaveInAddressBook(1)
                            ->save();

                        $customer->addAddress($address);
                    }
                } else {
                    throw new Exception($this->_getHelper()->__('Customer creation failed! Ricardo transaction cannot be added.'));
                }

                /**
                 * 4. Prepare data transaction
                 */
                $lang = $this->_getHelper()->getLocalCodeFromRicardoLanguageId($soldArticle->getMainLanguageId());
                $shippingDescription = $productItem->getShippingPaymentRule()->getShippingDescription($lang);
                $paymentDescription = $productItem->getShippingPaymentRule()->getPaymentDescription($lang);

                /**
                 * 5. Insert transaction into DB for future use
                 */
                $salesTransaction = Mage::getModel('diglin_ricento/sales_transaction');
                $salesTransaction
                    ->setBidId($transaction->getBidId())
                    ->setWebsiteId($listing->getWebsiteId())
                    ->setCustomerId($customer->getId())
                    ->setAddressId($address->getId())
                    ->setRicardoCustomerId($customer->getRicardoId())
                    ->setRicardoArticleId($soldArticle->getArticleId())
                    ->setQty($transaction->getBuyerQuantity())
                    ->setViewCount($soldArticle->getViewCount())
                    ->setShippingFee($soldArticle->getDeliveryCost())
                    ->setShippingText($soldArticle->getDeliveryText()) // @fixme - if bought in FR and the API use the DE key, text will in DE. I have no efficient solution now
                    ->setShippingMethod($soldArticle->getDeliveryId())
                    ->setShippingCumulativeFee((int)$soldArticle->getIsCumulativeShipping())
                    ->setLanguageId($soldArticle->getMainLanguageId())
                    ->setShippingDescription($shippingDescription)
                    ->setPaymentMethods(implode(',', $soldArticle->getPaymentMethodIds()->getData()))
                    ->setPaymentDescription($paymentDescription)
                    ->setTotalBidPrice($soldArticle->getWinningBidPrice())
                    ->setProductId($extractedInternReference->getProductId())
                    ->setRawData(Mage::helper('core')->jsonEncode($rawData))
                    ->setSoldAt($this->_getHelper()->getJsonTimestamp($soldArticle->getEndDate()))
                    ->save();

                /**
                 * 6. Decrease the quantity at products listing item level
                 */
                $productItem
                    ->setQtyInventory($productItem->getQtyInventory() - $salesTransaction->getQty())
                    ->save();

                $this->_itemStatus = Diglin_Ricento_Model_Products_Listing_Log::STATUS_SUCCESS;
                $this->_itemMessage = array('success' => $this->_getHelper()->__('The product has been sold'));
            }
        }

        return true; // @todo it returns true also when no transaction have been done
    }

    /**
     * Find or create customer if needed based on ricardo data
     *
     * @param Varien_Object $buyer
     * @param int $websiteId
     * @return bool|Mage_Customer_Model_Customer
     */
    protected function _getCustomer(Varien_Object $buyer, $websiteId = Mage_Core_Model_App::ADMIN_STORE_ID)
    {
        if (!$buyer->getBuyerId()) {
            return false;
        }

        $storeId = $this->_getStoreId($websiteId);

        /* @var $customer Mage_Customer_Model_Customer */
        $customer = Mage::getModel('customer/customer')
            ->setWebsiteId($websiteId)
            ->loadByEmail($buyer->getEmail());

        if (!$customer->getId()) {
            $customer
                ->setFirstname($buyer->getFirstName())
                ->setLastname($buyer->getLastName())
                ->setEmail($buyer->getEmail())
                ->setPassword($customer->generatePassword())
                ->setStoreId($storeId)
                ->setConfirmation(null);
        }

        if (!$customer->getRicardoId()) {
            $customer
                ->setRicardoId($buyer->getBuyerId())
                ->setRicardoUsername($buyer->getNickName());
        }

        if ($customer->hasDataChanges()) {
            $customer->save();
        }

        if ($customer->isObjectNew() && Mage::getStoreConfigFlag(Diglin_Ricento_Helper_Data::CFG_ACCOUNT_CREATION_EMAIL, $storeId)) {
            if ($customer->isConfirmationRequired()) {
                $typeEmail = 'confirmation';
            } else {
                $typeEmail = 'registered';
            }
            $customer->sendNewAccountEmail($typeEmail, '', $storeId);
        }

        return $customer;
    }

    /**
     * Create new orders for transactions done more than 30 min in past
     *
     * @return $this
     */
    protected function _proceedAfter()
    {
        $customerTransactions = array();
        $mergeOrder = Mage::getStoreConfigFlag(Diglin_Ricento_Helper_Data::CFG_MERGE_ORDER);

        $delay = ($mergeOrder) ? 30 : 1;

        /**
         * Get transaction older than 30 minutes and when no order was created
         * Those will be merged in one order if the customer is the same
         */
        $transactionCollection = Mage::getResourceModel('diglin_ricento/sales_transaction_collection');
        $transactionCollection
            ->getSelect()
            ->where('order_id IS NULL')
            ->where('UNIX_TIMESTAMP(sold_at) + ( ? * 60) < UNIX_TIMESTAMP(now())', (int) $delay); // 30 or 1 min past

        $inc = 0;
        foreach ($transactionCollection->getItems() as $transactionItem) {
            if ($mergeOrder) {
                $customerTransactions[$transactionItem->getCustomerId()][] = $transactionItem;
            } else {
                $customerTransactions[++$inc] = $transactionItem;
            }
        }

        /**
         * Create new order
         */
        if (count($customerTransactions) > 0) {
            foreach ($customerTransactions as $transactions) {
                if (!is_array($transactions)) {
                    $transactions = array($transactions);
                }
                $this->createNewOrder($transactions);
            }
        }

        return $this;
    }

    /**
     * @param array $transactions
     */
    public function createNewOrder($transactions)
    {
        $quote = null;
        $dispatchedTransactions = array();
        $storeId = $shippingTransactionMethod = $shippingMethodFee = $highestShippingFee = 0;
        $shippingText = $shippingDescription = '';
        $paymentMethod = $shippingMethod = Diglin_Ricento_Model_Sales_Method_Payment::PAYMENT_CODE;

        try {
            /**
             * If a customer ordered several articles of the same seller in a short period of time
             * the order will merge all articles.
             */

            /* @var $transaction Diglin_Ricento_Model_Sales_Transaction */
            foreach ($transactions as $transaction) {

                $storeId = $this->_getStoreId($transaction->getWebsiteId());

                Mage::app()->getLocale()->emulate($storeId);

                /**
                 * 1. Init Quote and define customer and his address
                 */
                if (is_null($quote)) {
                    $quote = Mage::getModel('sales/quote');
                    $quote->setStoreId($storeId);

                    $customerId = $transaction->getCustomerId();
                    $customer = Mage::getModel('customer/customer')->load($customerId);

                    $address = Mage::getModel('customer/address')->load($transaction->getAddressId());
                    $address->setCustomer($customer);

                    $quoteAddress = Mage::getModel('sales/quote_address');
                    $quoteAddress->importCustomerAddress($address);

                    $quote->assignCustomerWithAddressChange($customer, $quoteAddress, $quoteAddress);
                    $quote->getBillingAddress()->setPaymentMethod($paymentMethod);
                }

                /**
                 * 2. Add product and its information to the quote
                 */
                $infoBuyRequest = new Varien_Object();
                $infoBuyRequest
                    ->setQty($transaction->getQty())
                    ->setIsRicardo(true)
                    ->setRicardoTransactionId($transaction->getId())
                    ->setShippingCumulativeFee($transaction->getShippingCumulativeFee())
                    ->setShippingFee($transaction->getShippingFee());


                $product = Mage::getModel('catalog/product')
                    ->setStoreId($storeId)
                    ->load($transaction->getProductId())
                    ->setSkipCheckRequiredOption(true);

                $quoteItem = $quote->addProduct($product, $infoBuyRequest)
                    // Set unit custom price
                    ->setCustomPrice($transaction->getTotalBidPrice())
                    ->setOriginalCustomPrice($transaction->getTotalBidPrice());

                // Error with a product which is missing or have required options
                if (is_string($quoteItem)) {
                    Mage::throwException($quoteItem); // @todo - do we want really block the process at this level? Other solution to inform about the error?
                }

                /**
                 * 3. Set shipping information, price, etc
                 */
                $shippingText = $transaction->getShippingText(); // @todo provide correct language
                $shippingDescription = $transaction->getShippingDescription(); // @todo get shipping description in correct language

                /**
                 * 4. Keep the complete transactions list for later use
                 */
                $dispatchedTransactions[$transaction->getBidId()] = $transaction->getId();
            }

            if ($quote) {
                /**
                 * Define payment method and related information
                 */
                $quote
                    ->setIsRicardo(1)
                    ->setQuoteCurrencyCode(Mage::getStoreConfig(Diglin_Ricento_Helper_Data::PAYMENT_CURRENCY));

                $payment = $quote->getPayment();
                $payment->importData(array(
                    'method' => $paymentMethod,
                    'additional_data' => Mage::helper('core')->jsonEncode(array(
                        'is_ricardo' => true,
                        'ricardo_payment_methods' => $transaction->getPaymentMethods(),
                        'ricardo_transaction_ids' => implode(',', $dispatchedTransactions),
                        'ricardo_bid_ids' => implode(',', array_keys($dispatchedTransactions)),
                        )
                    )));

                /**
                 * Set Shipping information and price
                 * @see Diglin_Ricento_Model_Sales_Method_Shipping::collectRates
                 */
                Mage::getSingleton('core/session')
                    ->setRicardoShippingDescription($shippingText . "\n" . $shippingDescription)
                    ->setRicardoShippingMethod($shippingTransactionMethod);

                $shipping = $quote->getShippingAddress();
                $shipping->setShippingMethod($shippingMethod . '_' . $shippingTransactionMethod);

                $sendCustomerNotification = Mage::getStoreConfigFlag(Diglin_Ricento_Helper_Data::CFG_ORDER_CREATION_EMAIL, $storeId);

                $quote->addData(array(
                        'customer_note_notify' => false,
                        'customer_note' => $this->_getHelper()->__('Order automatically generated by the Ricardo Extension.'))
                );

                $quote->collectTotals()->save();

                if ($quote->getId()) {
                    // Session variables needed to create order
                    $this->_getSession()
                        ->setQuoteId($quote->getId())
                        ->setStoreId($quote->getStoreId())
                        ->setCustomer($quote->getCustomer())
                        ->setCustomerId($quote->getCustomer()->getId());

                    /* @var $order Mage_Sales_Model_Order */
                    $order = $this->_getOrderCreateModel()
                        ->initRuleData()
                        ->collectShippingRates()
                        ->setSendConfirmation($sendCustomerNotification)
                        ->createOrder();

                    /**
                     * Define order status
                     */
                    $order->setState(Mage_Sales_Model_Order::STATE_PENDING_PAYMENT, Diglin_Ricento_Helper_Data::ORDER_STATUS_PENDING, $this->_getHelper()->__('Payment is pending'), false);

                    // @fixme getIsTransactionCompleted or getIsTransactionCancelled has no value from ricardo.ch side at the moment, wait API update
//                    $rawData = $this->_getHelper()->extractData(Mage::helper('core')->jsonDecode($transaction->getRawData()));
//                    if ($rawData->getTransaction()->getIsTransactionCompleted()) {
//                        $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true, $this->_getHelper()->__('Payment has been completed on ricardo.ch side'), false);
//                    }
//
//                    if ($rawData->getTransaction()->getIsTransactionCancelled()) {
//                        $order->setState(Mage_Sales_Model_Order::STATE_CANCELED, Diglin_Ricento_Helper_Data::ORDER_STATUS_CANCEL, $this->_getHelper()->__('Order canceled on ricardo.ch side'), false);
//                    }

                    $quote->setIsActive(false)
                        ->save();

                    /**
                     * Save the new order id to the ricardo transaction
                     */
                    foreach ($transactions as $transaction) {
                        $transaction
                            ->setOrderId($order->getId())
                            ->save();
                    }
                }

                Mage::app()->getLocale()->revert();
            }
        } catch (Exception $e) {
            // We store and send the exception but don't block the rest of the process
            Mage::log("\n" . $e->__toString(), Zend_Log::ERR, Diglin_Ricento_Helper_Data::LOG_FILE);
            Mage::helper('diglin_ricento/tools')->sendAdminNotification($e->__toString());

            // Deactivate the last quote if a problem occur to prevent cart display in frontend to the customer
            $quote = $this->_getSession()->getQuote();
            $quote->setIsActive(false)
                ->setReservedOrderId(NULL)
                ->save();

            if (Mage::app()->getStore()->isAdmin()) {
                Mage::app()->getLocale()->revert();
            }
        }

        // force to cleanup model, session and rule_data for the next orders to generate otherwise conflicts will occur
        Mage::unregister('_singleton/adminhtml/sales_order_create');
        Mage::unregister('_singleton/adminhtml/session_quote');
        Mage::unregister('rule_data');
    }

    /**
     * Retrieve session object
     *
     * @return Mage_Adminhtml_Model_Session_Quote
     */
    protected function _getSession()
    {
        return Mage::getSingleton('adminhtml/session_quote');
    }

    /**
     * Retrieve quote object
     *
     * @return Mage_Sales_Model_Quote
     */
    protected function _getQuote()
    {
        return $this->_getSession()->getQuote();
    }

    /**
     * Retrieve order create model
     *
     * @return Mage_Adminhtml_Model_Sales_Order_Create
     */
    protected function _getOrderCreateModel()
    {
        return Mage::getSingleton('adminhtml/sales_order_create');
    }

    /**
     * @param $countryRicardoId
     * @return string
     * @throws Exception
     */
    protected function _getCountryId($countryRicardoId)
    {
        $countryName = '';
        $countries = Mage::getSingleton('diglin_ricento/api_services_system')
            ->setCurrentWebsite($this->_getListing()->getWebsiteId())
            ->getCountries();

        foreach ($countries as $country) {
            if ($country['CountryId'] == $countryRicardoId) {
                $countryName = $country['CountryName'];
                break;
            }
        }

        $code = $this->_translateCountryNameToCode($countryName);
        if (!$code) {
            throw new Exception(Mage::helper('diglin_ricento')->__('Country Code is not available. Please contact the author of this extension or support.'));
        }
        $directory = Mage::getModel('directory/country')->loadByCode($code);
        return $directory->getCountryId();
    }

    /**
     * VERY TEMPORARY SOLUTION until ricardo provide an API method to get the correct value
     * @todo remove it as soon the API has implemented the method to get it
     *
     * @param $countryName
     * @return string
     */
    protected function _translateCountryNameToCode($countryName)
    {
        $countryCode = array(
            'Schweiz' => 'CH',
            'Suisse' => 'CH',
            'Liechtenstein' => 'LI', // ok for both lang
            'Österreich' => 'AT',
            'Autriche' => 'AT',
            'Deutschland' => 'DE',
            'Allemagne' => 'DE',
            'Frankreich' => 'FR',
            'France' => 'FR',
            'Italien' => 'IT',
            'Italie' => 'IT',
        );

        return (isset($countryCode[$countryName])) ? $countryCode[$countryName] : false;
    }

    /**
     * @param int $websiteId
     * @return int
     */
    protected function _getStoreId($websiteId)
    {
        return Mage::app()->getWebsite($websiteId)->getDefaultStore()->getId();
    }
}

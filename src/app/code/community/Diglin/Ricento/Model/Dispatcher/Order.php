<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <support at diglin.com>
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

        foreach ($listingIds as $listingId)
        {
            $itemResource = Mage::getResourceModel('diglin_ricento/products_listing_item');
            $readConnection = $itemResource->getReadConnection();
            $select = $readConnection->select()
                ->from($itemResource->getTable('diglin_ricento/products_listing_item'), 'item_id')
                ->where('products_listing_id = :id')
                ->where('status = :status');

            $binds  = array('id' => $listingId, 'status' => Diglin_Ricento_Helper_Data::STATUS_LISTED);
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
        //$job = $this->_currentJob;
        $jobListing = $this->_currentJobListing;

        $itemCollection = $this->_getItemCollection(array(Diglin_Ricento_Helper_Data::STATUS_LISTED));
        $itemCollection
            ->addFieldToFilter('is_planned = 0');

        $articleIds = $itemCollection->getColumnValues('ricardo_article_id');

        $this->getSoldArticles($articleIds);


        $jobListing->saveCurrentJob(array(
            'total_proceed' => count($articleIds),
            'last_item_id' => null
        ));

    }

    /**
     * @param array $articleIds
     * @return mixed
     * @throws Exception
     */
    public function getSoldArticles($articleIds = array())
    {
        $helper = Mage::helper('diglin_ricento');

        $soldArticlesParameter = new SoldArticlesParameter();

        /**
         * Set date to filter e.g. last day. Do not use a higher value as the minimum sales duration is 1 day,
         * we prevent to have conflict with several sold articles having similar internal reference
         */
        $soldArticlesParameter
            ->setArticleIdsFilter($articleIds)
            ->setMinimumEndDate($helper->getJsonDate(time() - (1 * 24 * 60 * 60)));

        $sellerAccountService = Mage::getSingleton('diglin_ricento/api_services_selleraccount');
        $soldArticles = $sellerAccountService->getServiceModel()->getSoldArticles($soldArticlesParameter);

        foreach($soldArticles as $soldArticle) {

            $rawData = $soldArticle;
            $soldArticle = $helper->extractData($soldArticle);
            $transaction = $soldArticle->getTransaction();

            if ($transaction && count($transaction) > 0) {

                /**
                 * 1. Check that the transaction doesn't already exists
                 */
                $salesTransaction = Mage::getModel('diglin_ricento/sales_transaction');
                $salesTransaction->load($transaction->getBidId(), 'bid_id');

                if ($salesTransaction->getId()) {
                    continue;
                }

                /**
                 * 2. Check if the products listing item exists and listed
                 */
                if (!isset($soldArticle->getArticleInternalReferences()[0]['InternalReferenceValue'])) {
                    continue;
                }
                $internalReferenceValue = $soldArticle->getArticleInternalReferences()[0]['InternalReferenceValue'];
                $extractedInternReference = Mage::helper('diglin_ricento')->extractInternalReference($internalReferenceValue);
                $productItem = Mage::getModel('diglin_ricento/products_listing_item')->load($extractedInternReference->getItemId());

                if (!$productItem->getId() || $productItem->getStatus() != Diglin_Ricento_Helper_Data::STATUS_LISTED ) {
                    continue;
                }

                /**
                 * 3. Create customer if not exist and set his default billing address
                 */
                $customer = $this->_getCustomer($transaction->getBuyer());
                $buyerAddress = $transaction->getBuyer()->getAddresses();

                if ($customer) {

                    $address = $customer->getDefaultBillingAddress();

                    $street = $buyerAddress->getAddress1() . "\n" . $buyerAddress->getAddress2() . "\n" . $buyerAddress->getPostalBox();
                    $postCode = $buyerAddress->getZipCode();
                    $city = $buyerAddress->getCity();

                    if (!$address || ($address->getCity() != $city && $address->getPostcode() != $postCode && $address->getStreet() != $street )) {

                        $address = Mage::getModel('customer/address');
                        $address
                            ->setCustomerId($customer->getId())
                            ->setCompany($transaction->getBuyer()->getCompanyName())
                            ->setLastname($customer->getLastname())
                            ->setFirstname($customer->getFirstname())
                            ->setStreet($street)
                            ->setPostcode($postCode)
                            ->setCity($city)
                            ->setRegionId(null)
                            ->setCountryId($this->_getCountryId($buyerAddress->getCountry()))
                            ->setTelephone($transaction->getBuyer()->getPhone())
                            ->setIsDefaultBilling(true)
                            ->setIsDefaultShipping(true)
                            ->setSaveInAddressBook(1)
                            ->save();

                        $customer->addAddress($address);
                    }
                } else {
                    throw new Exception($helper->__('Customer creation failed! Ricardo transaction cannot be added.'));
                }

                /**
                 * 4. Insert transaction into DB for future use
                 */
                $salesTransaction = Mage::getModel('diglin_ricento/sales_transaction');
                $salesTransaction
                    ->setBidId($transaction->getBidId())
                    ->setCustomerId($customer->getId())
                    ->setAddressId($address->getId())
                    ->setRicardoCustomerId($customer->getRicardoCustomerId())
                    ->setRicardoArticleId($soldArticle->getArticleId())
                    ->setQty($transaction->getBuyerQuantity())
                    ->setViewCount($soldArticle->getViewCount())
                    ->setShippingFee($soldArticle->getDeliveryCost())
                    ->setShippingMethod($soldArticle->getDeliveryId())
                    ->setShippingCumulativeFee((int) $soldArticle->getIsCumulativeShipping())
                    ->setPaymentMethod($soldArticle->getPaymentMethodIds()[0])
                    ->setTotalBidPrice($soldArticle->getWinningBidPrice())
                    ->setProductId($extractedInternReference->getProductId())
                    ->setRawData(Mage::helper('core')->jsonEncode($rawData))
                    ->setSoldAt($helper->getJsonTimestamp($soldArticle->getEndDate()))
//                    ->save()
                ;

                print_r($salesTransaction->getData());
            }
        }
    }

    /**
     * Find or create customer if needed based on ricardo data
     *
     * @param Varien_Object $buyer
     * @return bool|Mage_Customer_Model_Customer
     */
    protected function _getCustomer(Varien_Object $buyer)
    {
        if (!$buyer->getBuyerId()) {
            return false;
        }

        $isNew = false;
        $listing = $this->_getListing();
        $storeId = $this->_getStoreId($listing);

        /* @var $customer Mage_Customer_Model_Customer */
        $customer = Mage::getModel('customer/customer')
            ->setWebsiteId($listing->getWebsiteId())
            ->loadByEmail($buyer->getEmail());

        if (!$customer->getId()) {
            $customer
                ->setFirstname($buyer->getFirstName())
                ->setLastname($buyer->getLastName())
                ->setEmail($buyer->getEmail())
                ->setPassword($customer->generatePassword())
                ->setStoreId($storeId)
                ->setConfirmation(null);

            $isNew = true;
        }

        if (!$customer->getRicardoCustomerId()) {
            $customer
                ->setRicardoCustomerId($buyer->getBuyerId())
                ->setRicardoUsername($buyer->getNickName());
        }

        if ($customer->hasDataChanges()) {
            $customer->save();
        }

        if ($isNew && Mage::getStoreConfigFlag(Diglin_Ricento_Helper_Data::CFG_ACCOUNT_CREATION_EMAIL)) {
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
     * @param $countryName
     * @return string
     * @throws Exception
     */
    protected function _getCountryId($countryName)
    {
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
            'Schweiz'       => 'CH',
            'Suisse'        => 'CH',
            'Liechtenstein' => 'LI', // ok for both lang
            'Österreich'    => 'AT',
            'Autriche'      => 'AT',
            'Deutschland'   => 'DE',
            'Allemagne'     => 'DE',
            'Frankreich'    => 'FR',
            'France'        => 'FR',
            'Italien'       => 'IT',
            'Italie'        => 'IT',
        );

        return (isset($countryCode[$countryName])) ? $countryCode[$countryName] : false;
    }

    /**
     * @param $listing
     * @return int
     */
    protected function _getStoreId($listing)
    {
        return Mage::app()->getWebsite($listing->getWebsiteId())->getDefaultStore()->getId();
    }

    public function createNewOrder ()
    {
        $autoRenewSubscribers = array();
        foreach ($autoRenewSubscribers as $autoRenewSubscriber) {
            $quote = null;
            try {
                foreach ($autoRenewSubscriber as $subscription) {

                    Mage::app()->getLocale()->emulate($subscription->getStoreId());

                    $paymentMethod = Mage::getStoreConfig('subscription/configuration/payment_auto_renew', $subscription->getStoreId());

                    // Init quote
                    if (is_null($quote)) {
                        $quote = Mage::getModel ('sales/quote');
                        $quote->setStoreId ($subscription->getStoreId());

                        $customerId = Mage::getModel('sales/order')->load ($subscription->getOrderId())->getCustomerId();
                        if (!$customerId) {
                            $customerId = $subscription->getSubscriberId();
                            $quote->setCustomerIsGuest(true);
                        }

                        $customer = Mage::getModel('customer/customer')->load($customerId);
                        if (!$customer->getDefaultBillingAddress()) {
                            // Get Address Collection and if not empty set the first address as a default billing address
                            $additionalAddresses = $customer->getAdditionalAddresses();
                            if (count($additionalAddresses) > 0) {
                                $additionalAddresses[0]->setIsDefaultBilling(true)->save();
                                $customer = Mage::getModel('customer/customer')->load($customerId); // reload customer after changes
                            }
                        }

                        $quote->assignCustomer ($customer);
                        $quote->getBillingAddress()->setPaymentMethod($paymentMethod);
                    }

                    // Get subscription product items and provide same options as the old order
                    $orderItem = Mage::getModel('sales/order_item')->load($subscription->getOrderItemId());
                    if (!empty($orderItem)) {

                        // Get children items (e.g. configurable product)
                        $orderItemChildren = $orderItem->getCollection()
                            ->addFieldToFilter('parent_item_id', $subscription->getOrderItemId());

                        $addBuyRequest = array();
                        foreach ($orderItemChildren->getItems() as $item) {
                            $addBuyRequest[] = $item->getProductOptionByCode ('info_buyRequest');
                        }

                        $infoBuyRequest = new Varien_Object($orderItem->getProductOptionByCode ('info_buyRequest'));
                        $infoBuyRequest->unsetData('uenc')
                            ->setSubscriptionDateStart($subscription->getDateEnd())
                            ->setSubscriptionRenewal(true)
                            ->setSubscriptionId($subscription->getId())
                            ->setChildrenItems($addBuyRequest);

                        $product = Mage::getModel ( 'catalog/product' )
                            ->setStoreId($subscription->getStoreId())
                            ->load ( $subscription->getProductId () )
                            ->setSkipCheckRequiredOption(true);

                        $item = $quote->addProduct ( $product, $infoBuyRequest );

                        // Error with a product which is missing or have required options
                        if (is_string($item)) {
                            Mage::throwException($item);
                        }
                    }
                }

                if ($quote) {
                    $payment = $quote->getPayment();
                    $payment->importData(array('method' => $paymentMethod));

                    $quote->addData(array(
                            'customer_note_notify' => false, // FIXME - Change to 'true' if the confirmation order is sent below
                            'customer_note' => Mage::helper('diglin_ricento')->__('This order is automatically generated by our system because your subscription is going to end.'))
                    );

                    $quote->collectTotals()->save();

                    if ($quote->getId()) {
                        // Session variables needed to create order
                        $this->_getSession ()
                            ->setQuoteId ($quote->getId())
                            ->setStoreId ($quote->getStoreId())
                            ->setCustomer ($quote->getCustomer())
                            ->setCustomerId ($quote->getCustomer()->getId());

                        $this->_getOrderCreateModel ()
                            ->initRuleData()
                            ->setSendConfirmation(false) // FIXME Do not send confirmation to the customer because the email are similar to a normal order - temporary solution
                            ->createOrder();

                        $quote->setIsActive(false)
                            ->save();
                    }

                    foreach ($autoRenewSubscriber as $subscription) {
                        // block sending notification, a notification will be send after order is created
                        $subscription->setNoUpdateNotification(true)
                            ->setEndingEmailNotified(1)
                            ->save();
                    }

                    Mage::app()->getLocale()->revert();
                }

            } catch (Exception $e) {
                // We store and send the exception but don't block the rest of the process
                Mage::logException($e);
                Mage::helper('diglin_ricento/tools')->sendAdminNotification($e->__toString());

                // Deactivate the last quote if a problem occur to prevent cart display in frontend to the customer
                $quote = $this->_getSession()->getQuote();
                $quote->setIsActive(false)
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
     * @return Diglin_Ricento_Model_Products_Listing
     */
    protected function _getListing()
    {
        return Mage::getModel('diglin_ricento/products_listing')->load($this->_productsListingId);
    }
}
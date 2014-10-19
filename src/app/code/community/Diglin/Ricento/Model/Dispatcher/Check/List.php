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
 * Class Diglin_Ricento_Model_Dispatcher_Check_List
 */
class Diglin_Ricento_Model_Dispatcher_Check_List extends Diglin_Ricento_Model_Dispatcher_Abstract
{
    /**
     * @var int
     */
    protected $_logType = Diglin_Ricento_Model_Products_Listing_Log::LOG_TYPE_CHECK;

    /**
     * @var string
     */
    protected $_jobType = Diglin_Ricento_Model_Sync_Job::TYPE_CHECK_LIST;

    /**
     * @var int
     */
    protected $_totalSuccess = 0;

    /**
     * @var int
     */
    protected $_totalWarning = 0;

    /**
     * @var int
     */
    protected $_totalError = 0;

    /**
     * @return $this
     */
    protected function _proceed()
    {
        $job = $this->_currentJob;
        $jobListing = $this->_currentJobListing;
        $this->_totalSuccess = $this->_totalWarning = $this->_totalError = 0;

        /**
         * Status of the collection must be the same as Diglin_Ricento_Model_Resource_Products_Listing_Item::countPendingItems
         */
        $itemCollection = $this->_getItemCollection(
            array(
                Diglin_Ricento_Helper_Data::STATUS_PENDING,
                Diglin_Ricento_Helper_Data::STATUS_ERROR,
                Diglin_Ricento_Helper_Data::STATUS_STOPPED
            ),
            $jobListing->getLastItemId()
        );

        if ($itemCollection->count() == 0) {
            $job->setJobMessage(array($this->_getNoItemMessage()));
            $this->_progressStatus = Diglin_Ricento_Model_Sync_Job::PROGRESS_COMPLETED;
            return $this;
        }

        /* @var $item Diglin_Ricento_Model_Products_Listing_Item */
        foreach ($itemCollection->getItems() as $item) {

            try {
                $itemValidator = new Diglin_Ricento_Model_Validate_Products_Item();

                // Detect which stores to use for each language defined at products listing level

                $stores = Mage::helper('diglin_ricento')->getStoresFromListing($item->getProductsListingId());

                /**
                 * Profiler on dev environment
                 *
                 * ~ 56ms / simple product
                 * ~ 230ms / configurable product
                 * ~ 176ms / grouped product
                 */

                $itemValidator->isValid($item, $stores);

                $this->_itemMessage = $itemValidator->getMessages();
                $this->_itemStatus = Diglin_Ricento_Model_Products_Listing_Log::STATUS_SUCCESS;

                // item validator doesn't send back success at the moment
                $warnings = $itemValidator->getWarnings();
                $errors = $itemValidator->getErrors();
                if (empty($warnings) && empty($errors)) {
                    $this->_itemStatus = Diglin_Ricento_Model_Products_Listing_Log::STATUS_SUCCESS;
                    $this->_jobHasSuccess = true;
                    ++$this->_totalSuccess;
                }

                if (!empty($warnings)) {
                    $this->_itemStatus = Diglin_Ricento_Model_Products_Listing_Log::STATUS_WARNING;
                    $this->_jobHasWarning = true;
                    ++$this->_totalWarning;
                }

                if (!empty($errors)) {
                    $this->_itemStatus = Diglin_Ricento_Model_Products_Listing_Log::STATUS_ERROR;
                    $this->_jobHasError = true;
                    $item->getResource()->saveCurrentItem($item->getId(), array('status' => Diglin_Ricento_Helper_Data::STATUS_ERROR));
                    ++$this->_totalError;
                }

                if ($this->_itemStatus != Diglin_Ricento_Model_Products_Listing_Log::STATUS_ERROR) {
                    $item->getResource()->saveCurrentItem($item->getId(), array('status' => Diglin_Ricento_Helper_Data::STATUS_READY));
                }

            } catch (Exception $e) {
                $this->_handleException($e);
                $e = null;
                // keep going for the next item - no break
            }

            // Save item information and eventual error messages

            $this->_getListingLog()->saveLog(array(
                'job_id' => $job->getId(),
                'product_title' => $item->getProductTitle(),
                'products_listing_id' => $this->_productsListingId,
                'product_id' => $item->getProductId(),
                'message' => $this->_jsonEncode($this->_itemMessage),
                'log_status' => $this->_itemStatus,
                'log_type' => $this->_logType
            ));

            // Save the current information of the process to allow live display via ajax call

            $jobListing->saveCurrentJob(array(
                'total_proceed' => ++$this->_totalProceed,
                'last_item_id' => $item->getId()
            ));

            $this->_itemMessage = null;
            $this->_itemStatus = null;
            unset($itemValidator);
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function _proceedAfter()
    {
        // Ready to list the product automatically which are ready for that

        /**
         * Prepare products listing item for configurable products
         */
        $this->_prepareConfigurableProduct();

        if ($this->_jobHasSuccess && $this->_progressStatus == Diglin_Ricento_Model_Sync_Job::PROGRESS_COMPLETED) {

            $countReadyItems = Mage::getResourceModel('diglin_ricento/products_listing_item')->coundReadyTolist($this->_productsListingId);

            $jobList = Mage::getModel('diglin_ricento/sync_job');
            $jobList
                ->setProgress(Diglin_Ricento_Model_Sync_Job::PROGRESS_PENDING)
                ->setJobType(Diglin_Ricento_Model_Sync_Job::TYPE_LIST)
                ->setJobMessage(array($jobList->getJobMessage()))
                ->save();

            $jobListingList = Mage::getModel('diglin_ricento/sync_job_listing');
            $jobListingList
                ->setProductsListingId($this->_productsListingId)
                ->setTotalCount($countReadyItems)
                ->setTotalProceed(0)
                ->setJobId($jobList->getId())
                ->save();

            unset($jobList);
            unset($jobListingList);
        }

        return $this;
    }

    /**
     * Create products listing items for configurable product
     *
     * @return $this
     */
    protected function _prepareConfigurableProduct()
    {
        /**
         * Get children products of configurable product
         */
        $collectionListingItemBis = Mage::getResourceModel('diglin_ricento/products_listing_item_collection');
        $collectionListingItemBis
            ->addFieldToFilter('parent_product_id', array('notnull' => 1))
            ->addFieldToFilter('status', Diglin_Ricento_Helper_Data::STATUS_READY)
            ->addFieldToFilter('products_listing_id', $this->_productsListingId)
            ->getSelect()
            ->group('parent_product_id');

        $parentProductIds = $collectionListingItemBis->getColumnValues('parent_product_id');

        /**
         * Get the list of configurable products
         */
        $collectionListingItem = Mage::getResourceModel('diglin_ricento/products_listing_item_collection');
        $collectionListingItem
            ->addFieldToFilter('products_listing_id', $this->_productsListingId)
            ->addFieldToFilter('status', Diglin_Ricento_Helper_Data::STATUS_READY)
            ->getConfigurableProducts();

        if (count($parentProductIds)) {
            $collectionListingItem->addFieldToFilter('product_id', array('nin' => $parentProductIds));
        }

        /**
         * Get all configurable product of a list
         *
         * @var $item Diglin_Ricento_Model_Products_Listing_Item
         */
        foreach ($collectionListingItem->getItems() as $item) {

            /**
             * Get all children products
             */
            $collection = Mage::getResourceModel('catalog/product_collection')
                ->addAttributeToSelect('sku')
                ->addFilterByRequiredOptions()
                ->addFieldToFilter('entity_id', array('in' => $item->getProduct()->getUsedProductIds()));

            $attributes = $item->getProduct()->getConfigurableAttributes();

            foreach ($attributes as $attribute) {
                $collection->addAttributeToSelect($attribute->getProductAttribute()->getAttributeCode());
                $collection->addAttributeToFilter($attribute->getProductAttribute()->getAttributeCode(), array('notnull' => 1));
            }

            foreach ($collection->getItems() as $childProduct) {

                $configurableChild = array();

                foreach ($attributes as $attribute) {

                    $productAttribute = $attribute->getProductAttribute();
                    $attributeValueId = $childProduct->getData($productAttribute->getAttributeCode());
                    if ($attributeValueId) {

                        $priceVariation = array();
                        $subtitle = $productAttribute->getFrontendLabel() . ': ';

                        /**
                         * Get price variation
                         */
                        $prices = $attribute->getData('prices');
                        foreach ($prices as $price) {
                            if ($price['pricing_value'] != 0 && $price['value_index'] == $attributeValueId) {
                                $priceVariation = array('pricing_value' => $price['pricing_value'], 'is_percent' => $price['is_percent']);
                                break;
                            }
                        }

                        /**
                         * Get attribute label to be used as subtitle
                         */
                        foreach ($productAttribute->getSource()->getAllOptions() as $option) {
                            if ($attributeValueId == $option['value']) {
                                $subtitle .= $option['label'];
                            }
                        }

                        $configurableChild['options'][$attributeValueId] = array_merge(array('subtitle' => $subtitle), $priceVariation);
                    }
                }

                $configurableChild['stock_qty'] = Mage::getModel('cataloginventory/stock_item')->loadByProduct($childProduct->getId())->getQty();

                /**
                 * Save as new products listing item
                 */
                $itemChild = clone $item;
                $itemChild
                    ->setId(null)
                    ->setCreatedAt(Mage::getSingleton('core/date')->gmtDate())
                    ->setUpdatedAt(null)
                    ->setProductId($childProduct->getId())
                    ->setAdditionalData(Mage::helper('core')->jsonEncode($configurableChild))
                    ->setParentItemId($item->getId())
                    ->setParentProductId($item->getProductId())
                    ->save();
            }
        }

        return $this;
    }

    /**
     * @param string $jobStatus
     * @return string
     */
    protected function _getStatusMessage($jobStatus)
    {
        $message = '';
        $helper = Mage::helper('diglin_ricento');
        if ($jobStatus != Diglin_Ricento_Model_Sync_Job::STATUS_SUCCESS) {
            $message = $helper->__('Report: %d success, %d warning(s), %d error(s)', $this->_totalSuccess, $this->_totalWarning, $this->_totalError);
            $message .= '<br>';
            $message .= $helper->__('Successful products checked are going to be listed. To force to list products having a warning, please <a href="%s">click here</a>. Products with an error won\'t be synchronized, you have to fix the problem first.', $this->_getListUrl());
        }
        return $message;
    }
}

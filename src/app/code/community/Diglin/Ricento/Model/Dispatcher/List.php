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
 * Class Diglin_Ricento_Model_Dispatcher_List
 */
class Diglin_Ricento_Model_Dispatcher_List extends Diglin_Ricento_Model_Dispatcher_Abstract
{
    /**
     * @var int
     */
    protected $_logType = Diglin_Ricento_Model_Products_Listing_Log::LOG_TYPE_LIST;

    /**
     * @var string
     */
    protected $_jobType = Diglin_Ricento_Model_Sync_Job::TYPE_LIST;

    /**
     * @return $this
     */
    protected function _proceed()
    {
        $job = $this->_currentJob;
        $jobListing = $this->_currentJobListing;

        $sell = Mage::getSingleton('diglin_ricento/api_services_sell');
        $sell->setCurrentWebsite($this->_getListing()->getWebsiteId());

        $insertedArticle = null;
        $hasSuccess = false;

        /**
         * Status of the collection must be the same as Diglin_Ricento_Model_Resource_Products_Listing_Item::coundReadyTolist
         */
        $itemCollection = $this->_getItemCollection(array(Diglin_Ricento_Helper_Data::STATUS_READY), $jobListing->getLastItemId());

        if ($itemCollection->count() == 0) {
            $job->setJobMessage(array($this->_getNoItemMessage()));
            $this->_progressStatus = Diglin_Ricento_Model_Sync_Job::PROGRESS_COMPLETED;
            return $this;
        }

        /* @var $item Diglin_Ricento_Model_Products_Listing_Item */
        foreach ($itemCollection->getItems() as $item) {

            try {
                $articleId = null;
                $isPlanned = false;

                // We skip insert article with configurable product as we push its associated products
                if ($item->getProduct()->isConfigurableType()) {
                    $jobListing->saveCurrentJob(array(
                        'total_proceed' => ++$this->_totalProceed,
                        'last_item_id' => $item->getId()
                    ));
                    continue;
                }

                if (!$item->getRicardoArticleId()) {
                    $insertedArticle = $sell->insertArticle($item);
                }

                if (!empty($insertedArticle['PlannedArticleId'])) {
                    $articleId = $insertedArticle['PlannedArticleId'];
                    $isPlanned = true;
                } else if (!empty($insertedArticle['ArticleId'])) {
                    $articleId = $insertedArticle['ArticleId'];
                    $isPlanned = false;
                }

                if (!empty($articleId)) {
                    // Must be set at first in case of error
                    $item->getResource()->saveCurrentItem($item->getId(), array(
                        'ricardo_article_id' => $articleId,
                        'status' => Diglin_Ricento_Helper_Data::STATUS_LISTED,
                        'is_planned' => (int) $isPlanned,
                        'qty_inventory' => $item->getProductQty()
                    ));
                    $this->_itemStatus = Diglin_Ricento_Model_Products_Listing_Log::STATUS_SUCCESS;
                    $this->_itemMessage = array('inserted_article' => $insertedArticle);
                    $hasSuccess = true;
                    $this->_jobHasSuccess = true;
                    ++$this->_totalSuccess;
                } else if ($item->getRicardoArticleId()) {
                    $this->_itemStatus = Diglin_Ricento_Model_Products_Listing_Log::STATUS_NOTICE;
                    $this->_itemMessage = array('notice' => $this->_getHelper()->__('This item is already listed or has already a ricardo article Id. No insert done to ricardo.ch'));
                    $this->_jobHasSuccess = true;
                    ++$this->_totalSuccess;
                    // no change needed for the item status
                } else {
                    ++$this->_totalError;
                    $this->_jobHasError = true;
                    $this->_itemStatus = Diglin_Ricento_Model_Products_Listing_Log::STATUS_ERROR;
                    $item->getResource()->saveCurrentItem($item->getId(), array('status' => Diglin_Ricento_Helper_Data::STATUS_ERROR));
                }
            } catch (Exception $e) {
                $this->_handleException($e, $sell);
                $e = null;
                // keep going for the next item - no break
            }

            /**
             * Save item information and eventual error messages
             */
            $this->_getListingLog()->saveLog(array(
                'job_id' => $job->getId(),
                'product_title' => $item->getProductTitle(),
                'products_listing_id' => $this->_productsListingId,
                'product_id' => $item->getProductId(),
                'message' => (is_array($this->_itemMessage)) ? $this->_jsonEncode($this->_itemMessage) : $this->_itemMessage,
                'log_status' => $this->_itemStatus,
                'log_type' => $this->_logType,
                'created_at' => Mage::getSingleton('core/date')->gmtDate()
            ));

            /**
             * Save the current information of the process to allow live display via ajax call
             */
            $jobListing->saveCurrentJob(array(
                'total_proceed' => ++$this->_totalProceed,
                'total_success' => $this->_totalSuccess, //@todo update with the previous total_success in case of chunk_running
                'total_error' => $this->_totalError,//@todo update with the previous total_success in case of chunk_running
                'last_item_id' => $item->getId()
            ));

            $this->_itemMessage = null;
            $this->_itemStatus = null;
        }

        if ($hasSuccess) {
            $listing = Mage::getModel('diglin_ricento/products_listing')->load($this->_productsListingId);
            $listing
                ->setStatus(Diglin_Ricento_Helper_Data::STATUS_LISTED)
                ->save();
        }

        unset($itemCollection);

        return $this;
    }

    /**
     * @param string $jobStatus
     * @return string
     */
    protected function _getStatusMessage($jobStatus)
    {
        return Mage::helper('diglin_ricento')->__('Report: %d success, %d error(s)', $this->_totalSuccess, $this->_totalError);
    }

    /**
     * @return $this
     */
//    protected function _proceedAfter()
//    {
//        /**
//         * Get the children of product listing item to set the correct status of the parent
//         */
//        $itemCollection = Mage::getResourceModel('diglin_ricento/products_listing_item_collection');
//        $itemCollection
//            ->addFieldToFilter('status', array('in' => Diglin_Ricento_Helper_Data::STATUS_LISTED))
//            ->addFieldToFilter('parent_item_id', array('notnull' => 1))
//            ->addFieldToFilter('products_listing_id', array('eq' => $this->_productsListingId))
//            ->getSelect()->group('parent_item_id');
//
//        $hash = array();
//        foreach ($itemCollection->getItems() as $item) {
//            if (empty($hash[$item->getParentItemId()])) {
//                Mage::getModel('diglin_ricento/products_listing_item')
//                    ->load($item->getParentItemId())
//                    ->setStatus(Diglin_Ricento_Helper_Data::STATUS_LISTED)
//                    ->save();
//
//                $hash[$item->getParentItemId()] = true;
//            }
//        }
//
//        unset($itemCollection);
//        unset($hash);
//
//        return parent::_proceedAfter();
//    }
}
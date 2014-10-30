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
 * Class Diglin_Ricento_Model_Dispatcher_Stop
 */
class Diglin_Ricento_Model_Dispatcher_Stop extends Diglin_Ricento_Model_Dispatcher_Abstract
{
    /**
     * @var int
     */
    protected $_logType = Diglin_Ricento_Model_Products_Listing_Log::LOG_TYPE_STOP;

    /**
     * @var string
     */
    protected $_jobType = Diglin_Ricento_Model_Sync_Job::TYPE_STOP;

    /**
     * @return $this
     */
    protected function _proceed()
    {
        $job = $this->_currentJob;
        $jobListing = $this->_currentJobListing;

        $stoppedArticle = null;
        $articleId = null;
        $hasSuccess = false;

        /**
         * Status of the collection must be the same as Diglin_Ricento_Model_Resource_Products_Listing_Item::countListedItems
         */
        $itemCollection = $this->_getItemCollection(array(Diglin_Ricento_Helper_Data::STATUS_LISTED), $jobListing->getLastItemId());

        if ($itemCollection->count() == 0) {
            $job->setJobMessage(array($this->_getNoItemMessage()));
            $this->_progressStatus = Diglin_Ricento_Model_Sync_Job::PROGRESS_COMPLETED;
            return $this;
        }

        $sell = Mage::getSingleton('diglin_ricento/api_services_sell');
        $sell->setCurrentWebsite($this->_getListing()->getWebsiteId());

        /* @var $item Diglin_Ricento_Model_Products_Listing_Item */
        foreach ($itemCollection->getItems() as $item) {

            try {
                $stoppedArticle = $sell->stopArticle($item);

                if ($stoppedArticle) {
                    $this->_itemStatus = Diglin_Ricento_Model_Products_Listing_Log::STATUS_SUCCESS;
                    $this->_itemMessage = array('success' => $this->_getHelper()->__('The product has been removed from ricardo.ch'));
                    $hasSuccess = true;
                    ++$this->_totalSuccess;
                    $item->getResource()->saveCurrentItem($item->getId(), array('ricardo_article_id' => null, 'is_planned' => null, 'qty_inventory' => null, 'status' => Diglin_Ricento_Helper_Data::STATUS_STOPPED));
                } else {
                    ++$this->_totalError;
                    $this->_jobHasError = true;
                    $this->_itemStatus = Diglin_Ricento_Model_Products_Listing_Log::STATUS_ERROR;
                    $this->_itemMessage = array('errors' => $this->_getHelper()->__('The product has not been removed from ricardo.ch. Probably because someone bid the product or bought it.'));
                    // do not change the status of the item itself, the problem can be that the auction is still running and the article cannot be stopped
                }
            } catch (Exception $e) {
                $this->_handleException($e);
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
                'message' => $this->_jsonEncode($this->_itemMessage),
                'log_status' => $this->_itemStatus,
                'log_type' => $this->_logType,
                'created_at' => Mage::getSingleton('core/date')->gmtDate()
            ));

            /**
             * Save the current information of the process to allow live display via ajax call
             */
            $jobListing->saveCurrentJob(array(
                'total_proceed' => ++$this->_totalProceed,
                'last_item_id' => $item->getId()
            ));

            $this->_itemMessage = null;
            $this->_itemStatus = null;
        }

        if ($hasSuccess) {
            $countListedItem = Mage::getResourceModel('diglin_ricento/products_listing_item')
                ->countListedItems($this->_productsListingId);

            if ($countListedItem == 0) {
                $listing = Mage::getModel('diglin_ricento/products_listing')->load($this->_productsListingId);
                $listing
                    ->setStatus(Diglin_Ricento_Helper_Data::STATUS_STOPPED)
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
        return Mage::helper('diglin_ricento')->__('Report: %d success, %d error(s)', $this->_totalSuccess, $this->_totalError);
    }
}
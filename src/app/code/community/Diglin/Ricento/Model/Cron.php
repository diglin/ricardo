<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

/**
 * Class Diglin_Ricento_Model_Cron
 */
class Diglin_Ricento_Model_Cron
{
    /**
     * @var int
     */
    protected $_limit = 250;

    /**
     * @var string
     */
    protected $_progressStatus = Diglin_Ricento_Model_Sync_Job::PROGRESS_CHUNK_RUNNING;

    /**
     * @var Diglin_Ricento_Model_Resource_Products_Listing_Log
     */
    protected $_listingLog;

    /**
     * @var bool
     */
    protected $_jobHasError = false;

    /**
     * @var bool
     */
    protected $_jobHasWarning = false;

    /**
     * @var string
     */
    protected $_itemStatus;

    /**
     * @var string
     */
    protected $_itemMessage;

    /**
     * @var int
     */
    protected $_productsListingId;

    /**
     * @var int
     */
    protected $_totalProceed = 0;

    /**
     * Process Cron tasks
     */
    public function process()
    {
        //** Launch Pending Jobs

        $this->_runJobs(Diglin_Ricento_Model_Sync_Job::TYPE_CHECK_LIST);
        $this->_runJobs(Diglin_Ricento_Model_Sync_Job::TYPE_LIST);
        $this->_runJobs(Diglin_Ricento_Model_Sync_Job::TYPE_STOP);

        //** Cleanup

        $this->_processCleanupJobs();
        $this->_processCleanupListingLog();
    }

    protected function _getHelper()
    {
        return Mage::helper('diglin_ricento');
    }

    /**
     * @param string $type
     * @param array $progress
     * @return Diglin_Ricento_Model_Resource_Sync_Job_Collection
     */
    protected function _getJobCollection($type, $progress)
    {
        $jobsCollection = Mage::getResourceModel('diglin_ricento/sync_job_collection');
        $jobsCollection
            ->addFieldToFilter('job_type', $type)
            ->addFieldToFilter('progress', array('in' => (array) $progress));

        return $jobsCollection;
    }

    /**
     * @param Diglin_Ricento_Model_Sync_Job $job
     * @return $this
     */
    protected function _startJob(Diglin_Ricento_Model_Sync_Job $job)
    {
        // Set job to running to prevent conflicts of multiple instances

        $job
            ->setStartedAt(Mage::getSingleton('core/date')->gmtDate())
//            ->setLocked(1)
            ->save();

        return $this;
    }

    /**
     * @return Diglin_Ricento_Model_Resource_Products_Listing_Log
     */
    protected function _getListingLog()
    {
        if (!$this->_listingLog) {
            $this->_listingLog = Mage::getResourceModel('diglin_ricento/products_listing_log');
        }
        return $this->_listingLog;
    }

    /**
     * @param string $method
     * @return string
     */
    protected function _getProceedMethod($method)
    {
        return '_proceed' . str_replace(' ', '', ucwords(str_replace('_', ' ', $method)));
    }

    /**
     * @param string $jobType
     * @return int|null
     */
    protected function _getLogType($jobType)
    {
        $logType = null;

        switch ($jobType) {
            case Diglin_Ricento_Model_Sync_Job::TYPE_CHECK_LIST:
                $logType = Diglin_Ricento_Model_Products_Listing_Log::LOG_TYPE_CHECK;
                break;
            case Diglin_Ricento_Model_Sync_Job::TYPE_LIST:
                $logType = Diglin_Ricento_Model_Products_Listing_Log::LOG_TYPE_LIST;
                break;
            case Diglin_Ricento_Model_Sync_Job::TYPE_STOP:
                $logType = Diglin_Ricento_Model_Products_Listing_Log::LOG_TYPE_STOP;
                break;
            case Diglin_Ricento_Model_Sync_Job::TYPE_RELIST:
                $logType = Diglin_Ricento_Model_Products_Listing_Log::LOG_TYPE_RELIST;
                break;
        }

        return $logType;
    }

    /**
     * @param mixed $content
     * @return string
     */
    protected function _jsonEncode($content)
    {
        return Mage::helper('core')->jsonEncode($content);
    }

    /**
     * We build an url which will be replaced during its display in the log grid cause of issue with secure url key
     *
     * @param Diglin_Ricento_Model_Sync_Job $job
     * @return string
     */
    protected function _getLogListingUrl(Diglin_Ricento_Model_Sync_Job $job = null)
    {
        return '{{adminhtml url="ricento/log/listing/" _query_id=' . $this->_productsListingId . ' _query_job_id=' . $job->getId() . '}}';
    }

    /**
     * We build an url which will be replaced during its display in the log grid cause of issue with secure url key
     *
     * @return string
     */
    protected function _getProductListingEditUrl()
    {
        return '{{adminhtml url="ricento/products_listing/edit/" _query_id=' . $this->_productsListingId . '}}';
    }

    /**
     * We build an url which will be replaced during its display in the log grid cause of issue with secure url key
     *
     * @return string
     */
    protected function _getForceListUrl()
    {
        return '{{adminhtml url="ricento/products_listing/forceList/" _query_id=' . $this->_productsListingId . '}}';
    }

    /**
     * @param array $statuses
     * @param null $lastItemId
     * @return Diglin_Ricento_Model_Resource_Products_Listing_Item_Collection
     */
    protected function _getItemCollection(array $statuses, $lastItemId = null)
    {
        $itemCollection = Mage::getResourceModel('diglin_ricento/products_listing_item_collection');
        $itemCollection
            ->setPageSize($this->_limit)
            ->addFieldToFilter('status', array('in' => $statuses))
            ->addFieldToFilter('products_listing_id', array('eq' => $this->_productsListingId))
            ->addFieldToFilter('item_id', array('gt' => (int) $lastItemId));

        return $itemCollection;
    }

    /**
     * @param string $jobType
     * @return $this
     */
    protected function _runJobs($jobType)
    {
        // Get all pending jobs of type "check" - the risk is very low to have for this collection a big quantity of data

        $jobsCollection = $this->_getJobCollection($jobType,
            array(Diglin_Ricento_Model_Sync_Job::PROGRESS_PENDING, Diglin_Ricento_Model_Sync_Job::PROGRESS_CHUNK_RUNNING));

        $helper = $this->_getHelper();

        try {
            /* @var $job Diglin_Ricento_Model_Sync_Job */
            foreach ($jobsCollection->getItems() as $job) {

                $jobMethod = $this->_getProceedMethod($jobType);
                $this->_progressStatus = $job->getProgress();

                $jobListing = Mage::getModel('diglin_ricento/sync_job_listing')->load($job->getId(), 'job_id');
                $this->_productsListingId = (int) $jobListing->getProductsListingId();
                $this->_totalProceed = (int) $jobListing->getTotalProceed();

                if (!$this->_productsListingId) {
                    return $this;
                }

                // Cleanup in case of running again the same job

                if (!$jobListing->getLastItemId()) {
                    $this->_getListingLog()->cleanSpecificJob($job->getId());
                }

                $start = microtime(true);

                if (method_exists($this, $jobMethod)) {
                    $this->$jobMethod($job, $jobListing);
                }

                $end = microtime(true);
                Mage::log('Time to run the job id ' . $job->getId() . ' in ' . ($end - $start) . ' sec', Zend_Log::DEBUG, Diglin_Ricento_Helper_Data::LOG_FILE);

                if ($this->_jobHasError || $job->getJobStatus() == Diglin_Ricento_Model_Sync_Job::STATUS_ERROR) {
                    $typeError = $helper->__('errors');
                    $jobStatus = Diglin_Ricento_Model_Sync_Job::STATUS_ERROR;
                } else if ($this->_jobHasWarning || $job->getJobStatus() == Diglin_Ricento_Model_Sync_Job::STATUS_WARNING) {
                    $typeError = $helper->__('warnings');
                    $jobStatus = Diglin_Ricento_Model_Sync_Job::STATUS_WARNING;
                } else {
                    $typeError = $helper->__('success');
                    $jobStatus = Diglin_Ricento_Model_Sync_Job::STATUS_SUCCESS;
                }

                $additionalMessage = '';
                $statusMessageMethod = $jobMethod . 'StatusMessage';
                if (method_exists($this, $statusMessageMethod)) {
                    $additionalMessage = $this->$statusMessageMethod($jobStatus);
                }

                // In case we proceed chunk of data or not
                $endedAt = null;

                if ($jobListing->getTotalCount() == $this->_totalProceed) {
                    $this->_progressStatus = Diglin_Ricento_Model_Sync_Job::PROGRESS_COMPLETED;
                    $endedAt = Mage::getSingleton('core/date')->gmtDate();

                    $jobListing
                        ->setLastItemId(null)
                        ->setTotalProceed($this->_totalProceed)
                        ->save();

                    $message = $helper->__('The Job "%s" has finished with %s for the <a href="%s" target="_blank">products listing %d</a>. Please, view the <a href="%s">log</a> for details.',
                        $job->getJobTypeLabel(),
                        $typeError,
                        $this->_getProductListingEditUrl(),
                        $this->_productsListingId,
                        $this->_getLogListingUrl($job)
                    );

                } else {
                    $message = $job->getJobMessage();
                }

                $message .= $additionalMessage;

                $job->getResource()->saveCurrentJob($job->getId(), array(
                    'job_message' => $message,
                    'job_status' => $jobStatus,
                    'ended_at' => $endedAt,
                    'progress' => $this->_progressStatus,
//                    'locked' => ($this->_progressStatus == Diglin_Ricento_Model_Sync_Job::PROGRESS_COMPLETED) ? 0 : 1
                ));

                $afterMethod = $jobMethod . 'After';
                if (method_exists($this, $afterMethod)) {
                    $this->$afterMethod($jobListing);
                }

                unset($job);
                unset($jobList);
                unset($jobsCollection);
                unset($jobListing);
                unset($jobListingList);
                unset($itemCollection);
            }
        } catch (Exception $e) {
            Mage::logException($e);
            $job = (isset($job)) ? $job : null;
            $this->_setJobError($job, $e);
        }

        unset($jobsCollection);

        return $this;
    }

    /**
     * @param Diglin_Ricento_Model_Sync_Job $job
     * @param Diglin_Ricento_Model_Sync_Job_Listing $jobListing
     * @return $this
     */
    protected function _proceedCheckList(Diglin_Ricento_Model_Sync_Job $job, Diglin_Ricento_Model_Sync_Job_Listing $jobListing)
    {
        $itemCollection = $this->_getItemCollection(
            array(Diglin_Ricento_Helper_Data::STATUS_PENDING, Diglin_Ricento_Helper_Data::STATUS_STOPPED),
            $jobListing->getLastItemId()
        );

        if ($job->getProgress() == Diglin_Ricento_Model_Sync_Job::PROGRESS_PENDING && $itemCollection->count() > 0) {
            $this->_startJob($job);
        }

        if ($itemCollection->count() == 0) {
            $job->setJobMessage($this->_getHelper()->__('No item is ready for this job. No action has been done.'));
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

                if (!empty($itemValidator->getErrors())) {
                    $this->_itemStatus = Diglin_Ricento_Model_Products_Listing_Log::STATUS_ERROR;
                    $this->_jobHasError = true;
                    $item->getResource()->saveCurrentItem($item->getId(), array('status' => Diglin_Ricento_Helper_Data::STATUS_ERROR));
                } elseif (!empty($itemValidator->getWarnings())) {
                    $this->_itemStatus = Diglin_Ricento_Model_Products_Listing_Log::STATUS_WARNING;
                    $this->_jobHasWarning = true;
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
                'log_type' => $this->_getLogType(Diglin_Ricento_Model_Sync_Job::TYPE_CHECK_LIST)
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
     * @param Diglin_Ricento_Model_Sync_Job_Listing $jobListing
     * @return $this
     */
    protected function _proceedCheckListAfter(Diglin_Ricento_Model_Sync_Job_Listing $jobListing)
    {
        // Ready to list the product automatically only if no warning or error exists.

        if (!$this->_jobHasError && !$this->_jobHasWarning && $this->_progressStatus == Diglin_Ricento_Model_Sync_Job::PROGRESS_COMPLETED) {
            $jobList = Mage::getModel('diglin_ricento/sync_job');
            $jobList
                ->setProgress(Diglin_Ricento_Model_Sync_Job::PROGRESS_PENDING)
                ->setJobType(Diglin_Ricento_Model_Sync_Job::TYPE_LIST)
                ->setJobMessage($jobList->getJobMessage())
                ->save();

            $jobListingList = Mage::getModel('diglin_ricento/sync_job_listing');
            $jobListingList
                ->setProductsListingId($this->_productsListingId)
                ->setTotalCount($jobListing->getTotalCount())
                ->setTotalProceed(0)
                ->setJobId($jobList->getId())
                ->save();

            unset($jobList);
            unset($jobListingList);
        }

        return $this;
    }

    /**
     * @param string $jobStatus
     * @return string
     */
    protected function _proceedCheckListStatusMessage($jobStatus)
    {
        $adminhtmlHelper = Mage::helper('adminhtml');

        $message = '';
        if ($jobStatus != Diglin_Ricento_Model_Sync_Job::STATUS_SUCCESS) {
            $message = Mage::helper('diglin_ricento')->__('You can ignore the warnings or errors and list your products on ricardo.ch by <a href="%s">clicking here</a>. However products having an error won\'t be saved on ricardo.ch', $this->_getForceListUrl() );
        }
        return $message;
    }

    /**
     * @param Diglin_Ricento_Model_Sync_Job $job
     * @param Diglin_Ricento_Model_Sync_Job_Listing $jobListing
     * @return $this
     */
    protected function _proceedList(Diglin_Ricento_Model_Sync_Job $job, Diglin_Ricento_Model_Sync_Job_Listing $jobListing)
    {
        $sell = Mage::getSingleton('diglin_ricento/api_services_sell');

        $insertedArticle = null;
        $hasSuccess = false;

        $itemCollection = $this->_getItemCollection(array(Diglin_Ricento_Helper_Data::STATUS_READY), $jobListing->getLastItemId());

        if ($job->getProgress() == Diglin_Ricento_Model_Sync_Job::PROGRESS_PENDING && $itemCollection->count() > 0) {
            $this->_startJob($job);
        }

        if ($itemCollection->count() == 0) {
            $job->setJobMessage($this->_getHelper()->__('No item is ready for this job. No action has been done.'));
            $this->_progressStatus = Diglin_Ricento_Model_Sync_Job::PROGRESS_COMPLETED;
            return $this;
        }

        /* @var $item Diglin_Ricento_Model_Products_Listing_Item */
        foreach ($itemCollection->getItems() as $item) {

            try {
                $articleId = null;

                $insertedArticle = $sell->insertArticle($item);

                !empty($insertedArticle['PlannedArticleId']) && $articleId = $insertedArticle['PlannedArticleId'] && $isPlanned = true;
                !empty($insertedArticle['ArticleId']) && $articleId = $insertedArticle['ArticleId'] && $isPlanned = false;

                if (!empty($articleId)) {
                    $this->_itemStatus = Diglin_Ricento_Model_Products_Listing_Log::STATUS_SUCCESS;
                    $this->_itemMessage = array('inserted_article' => $insertedArticle);
                    $hasSuccess = true;
                    $item->getResource()->saveCurrentItem($item->getId(), array('ricardo_article_id' => $articleId, 'status' => Diglin_Ricento_Helper_Data::STATUS_LISTED));
                } else {
                    $this->_jobHasError = true;
                    $this->_itemStatus = Diglin_Ricento_Model_Products_Listing_Log::STATUS_ERROR;
                    $item->getResource()->saveCurrentItem($item->getId(), array('status' => Diglin_Ricento_Helper_Data::STATUS_ERROR));
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
                'log_type' => $this->_getLogType(Diglin_Ricento_Model_Sync_Job::TYPE_LIST)
            ));

            // Save the current information of the process to allow live display via ajax call

            $jobListing->saveCurrentJob(array(
                'total_proceed' => ++$this->_totalProceed,
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

        return $this;
    }

    /**
     * @param Diglin_Ricento_Model_Sync_Job $job
     * @param Diglin_Ricento_Model_Sync_Job_Listing $jobListing
     * @return $this
     */
    protected function _proceedRelist(Diglin_Ricento_Model_Sync_Job $job, Diglin_Ricento_Model_Sync_Job_Listing $jobListing)
    {
        $sell = Mage::getSingleton('diglin_ricento/api_services_sell');

        $relistedArticle = null;
        $articleId = null;
        $hasSuccess = false;

        $itemCollection = $this->_getItemCollection(array(Diglin_Ricento_Helper_Data::STATUS_SOLD), $jobListing->getLastItemId());

        if ($job->getProgress() == Diglin_Ricento_Model_Sync_Job::PROGRESS_PENDING && $itemCollection->count() > 0) {
            $this->_startJob($job);
        }

        if ($itemCollection->count() == 0) {
            $job->setJobMessage($this->_getHelper()->__('No item is ready for this job. No action has been done.'));
            $this->_progressStatus = Diglin_Ricento_Model_Sync_Job::PROGRESS_COMPLETED;
            return $this;
        }

        /* @var $item Diglin_Ricento_Model_Products_Listing_Item */
        foreach ($itemCollection->getItems() as $item) {

            try {
                $relistedArticle = $sell->relistArticle($item);

                if (!empty($articleId)) {
                    $this->_itemStatus = Diglin_Ricento_Model_Products_Listing_Log::STATUS_SUCCESS;
                    $this->_itemMessage = array('inserted_article' => $relistedArticle);
                    $hasSuccess = true;
                    $item->getResource()->saveCurrentItem($item->getId(), array('ricardo_article_id' => $articleId, 'status' => Diglin_Ricento_Helper_Data::STATUS_LISTED));
                } else {
                    $this->_jobHasError = true;
                    $this->_itemStatus = Diglin_Ricento_Model_Products_Listing_Log::STATUS_ERROR;
                    $item->getResource()->saveCurrentItem($item->getId(), array('status' => Diglin_Ricento_Helper_Data::STATUS_ERROR));
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
                'log_type' => $this->_getLogType(Diglin_Ricento_Model_Sync_Job::TYPE_RELIST)
            ));

            // Save the current information of the process to allow live display via ajax call

            $jobListing->saveCurrentJob(array(
                'total_proceed' => ++$this->_totalProceed,
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

        return $this;
    }

    /**
     * @param Diglin_Ricento_Model_Sync_Job $job
     * @param Diglin_Ricento_Model_Sync_Job_Listing $jobListing
     * @return $this
     */
    protected function _proceedStop(Diglin_Ricento_Model_Sync_Job $job, Diglin_Ricento_Model_Sync_Job_Listing $jobListing)
    {
        $sell = Mage::getSingleton('diglin_ricento/api_services_sell');

        $stoppedArticle = null;
        $articleId = null;
        $hasSuccess = false;

        $itemCollection = $this->_getItemCollection(array(Diglin_Ricento_Helper_Data::STATUS_LISTED), $jobListing->getLastItemId());

        if ($job->getProgress() == Diglin_Ricento_Model_Sync_Job::PROGRESS_PENDING && $itemCollection->count() > 0) {
            $this->_startJob($job);
        }

        if ($itemCollection->count() == 0) {
            $job->setJobMessage($this->_getHelper()->__('No item is ready for this job. No action has been done.'));
            $this->_progressStatus = Diglin_Ricento_Model_Sync_Job::PROGRESS_COMPLETED;
            return $this;
        }

        /* @var $item Diglin_Ricento_Model_Products_Listing_Item */
        foreach ($itemCollection->getItems() as $item) {

            try {
                $stoppedArticle = $sell->stopArticle($item);

                if ($stoppedArticle) {
                    $this->_itemStatus = Diglin_Ricento_Model_Products_Listing_Log::STATUS_SUCCESS;
                    $this->_itemMessage = array('success' => $this->_getHelper()->__('The product has been removed from ricardo.ch'));
                    $hasSuccess = true;
                    $item->getResource()->saveCurrentItem($item->getId(), array('ricardo_article_id' => null, 'status' => Diglin_Ricento_Helper_Data::STATUS_STOPPED));
                } else {
                    $this->_jobHasError = true;
                    $this->_itemStatus = Diglin_Ricento_Model_Products_Listing_Log::STATUS_ERROR;
                    $this->_itemMessage = array('error' => $this->_getHelper()->__('The product has not been removed from ricardo.ch'));
                    // do not change the status of the item itself, the problem can be that the auction is still running and the article cannot be stopped
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
                'log_type' => $this->_getLogType(Diglin_Ricento_Model_Sync_Job::TYPE_STOP)
            ));

            // Save the current information of the process to allow live display via ajax call

            $jobListing->saveCurrentJob(array(
                'total_proceed' => ++$this->_totalProceed,
                'last_item_id' => $item->getId()
            ));

            $this->_itemMessage = null;
            $this->_itemStatus = null;
        }

        if ($hasSuccess) {
            $itemResource = Mage::getResourceModel('diglin_ricento/products_listing_item');
            $countListedItem = $itemResource->countListedItems($this->_productsListingId);

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
     * @param null|Diglin_Ricento_Model_Sync_Job $job
     * @param Exception $e
     * @return $this
     */
    protected function _setJobError($job, Exception $e)
    {
        if (isset($job) && $job instanceof Diglin_Ricento_Model_Sync_Job && $job->getId()) {
            $job
                ->setProgress(Diglin_Ricento_Model_Sync_Job::PROGRESS_CHUNK_RUNNING)
                ->setJobStatus(Diglin_Ricento_Model_Sync_Job::STATUS_ERROR)
                ->setJobMessage($e->__toString())
                ->save();
        }
        return $this;
    }

    /**
     * Clean old jobs passed on the last X days
     *
     * @return $this
     */
    protected function _processCleanupJobs()
    {
        if (Mage::getStoreConfigFlag(Diglin_Ricento_Helper_Data::CFG_CLEAN_JOBS_ENABLED)) {
            $daysKeep = (int)Mage::getStoreConfig(Diglin_Ricento_Helper_Data::CFG_CLEAN_JOBS_KEEP_DAYS);

            $jobsCollection = Mage::getResourceModel('diglin_ricento/sync_job_collection');
            $jobsCollection
                ->getSelect()
                ->from(array('job' => $jobsCollection->getMainTable()), '')
                ->where('((TO_DAYS(job.created_at) + ? < TO_DAYS(now())))', $daysKeep);

            $jobsCollection->walk('delete');
        }
        return $this;
    }

    /**
     * Cleanup Product Listing Log after X days
     * @return $this
     */
    protected function _processCleanupListingLog()
    {
        if (Mage::getStoreConfigFlag(Diglin_Ricento_Helper_Data::CFG_CLEAN_JOBS_ENABLED)) {
            $daysKeep = (int)Mage::getStoreConfig(Diglin_Ricento_Helper_Data::CFG_CLEAN_JOBS_KEEP_DAYS);

            $jobsCollection = Mage::getResourceModel('diglin_ricento/products_listing_log_collection');
            $jobsCollection
                ->getSelect()
                ->from(array('log' => $jobsCollection->getMainTable()), '')
                ->where('((TO_DAYS(log.created_at) + ? < TO_DAYS(now())))', $daysKeep);

            $jobsCollection->walk('delete');
        }
        return $this;
    }

    /**
     * @param Exception $e
     * @return $this
     */
    protected function _handleException(Exception $e)
    {
        if ($this->_getHelper()->isDebugEnabled()) {
            Mage::log(Mage::getSingleton('diglin_ricento/api_services_sell')->getLastApiDebug(), Zend_Log::DEBUG, Diglin_Ricento_Helper_Data::LOG_FILE);
        }
        Mage::logException($e);

        $this->_itemMessage = array('errors' =>
            array(
                $this->_getHelper()->__('Error Code: %d', $e->getCode()),
                $this->_getHelper()->__($e->getMessage())
            ));

        $this->_jobHasError = true;
        $this->_itemStatus = Diglin_Ricento_Model_Products_Listing_Log::STATUS_ERROR;

        return $this;
    }
}
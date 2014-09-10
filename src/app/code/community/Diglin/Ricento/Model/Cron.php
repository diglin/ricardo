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
    protected $_limit = 1000;

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
     * Process Cron tasks
     */
    public function process()
    {
        //** Launch Pending Jobs

        $this->_runJobs(Diglin_Ricento_Model_Sync_Job::TYPE_CHECK_LIST);
        $this->_runJobs(Diglin_Ricento_Model_Sync_Job::TYPE_LIST);

        //** Cleanup

        $this->_processCleanupJobs();
        $this->_processCleanupListingLog();
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
            ->setProgress($this->_progressStatus) // @todo replace with a flag "locked"
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
        }

        return $logType;
    }

    /**
     * @param string $jobType
     * @return $this
     */
    protected function _runJobs($jobType)
    {
        // Get all pending jobs of type "check" - the risk is very low to have for this collection a big quantity of data

        $jobsCollection = $this->_getJobCollection($jobType, array(Diglin_Ricento_Model_Sync_Job::PROGRESS_PENDING, Diglin_Ricento_Model_Sync_Job::PROGRESS_CHUNK_RUNNING));

        $helper = Mage::helper('diglin_ricento');
        $adminhtmlHelper = Mage::helper('adminhtml');

        try {
            /* @var $job Diglin_Ricento_Model_Sync_Job */
            foreach ($jobsCollection->getItems() as $job) {

                $progressStatus = $this->_progressStatus;

                $jobListing = Mage::getModel('diglin_ricento/sync_job_listing')->load($job->getId(), 'job_id');
                $this->_productsListingId = (int) $jobListing->getProductsListingId();

                if (!$this->_productsListingId) {
                    return $this;
                }

                if ($job->getProgress() == Diglin_Ricento_Model_Sync_Job::PROGRESS_PENDING) {
                    $this->_startJob($job);
                }

                // Cleanup in case of running the same job

                $this->_getListingLog()->cleanSpecificJob($job->getId());

                $start = microtime(true);

                // Get all pending products listing item to be checked

                $itemCollection = Mage::getResourceModel('diglin_ricento/products_listing_item_collection');
                $itemCollection
                    ->setPageSize($this->_limit)
                    ->addFieldToFilter('status', array('in' => Diglin_Ricento_Helper_Data::STATUS_PENDING))
                    ->addFieldToFilter('products_listing_id', array('eq' => $this->_productsListingId));

                if ($jobListing->getLastItemId()) {
                    $itemCollection->addFieldToFilter('item_id', array('gt' => (int) $jobListing->getLastItemId()));
                }

                $totalProceed = (int) $jobListing->getTotalProceed();

                $jobMethod = $this->_getProceedMethod($jobType);

                foreach ($itemCollection->getItems() as $item) {

                    try {
                        if (method_exists($this, $jobMethod)) {
                            $this->$jobMethod($item, $job);
                        }
                    } catch (Exception $e) {
                        Mage::logException($e);
                        $this->_jobHasError = true;
                        $this->_itemMessage = $helper->__('An error occurred while executing a process for this item. Here is the error message %s', $e->__toString());
                        // keep going for the next items - no break
                    }

                    // Save item information and eventual error messages

                    $this->_getListingLog()->saveLog(array(
                        'job_id' => $job->getId(),
                        'product_title' => $item->getProductTitle(),
                        'products_listing_id' => $this->_productsListingId,
                        'product_id' => $item->getProductId(),
                        'message' => $this->_itemMessage,
                        'log_status' => $this->_itemStatus,
                        'log_type' => $this->_getLogType($jobType)
                    ));

                    // Save the current information of the process to allow live display via ajax call

                    $jobListing->saveCurrentJob(array(
                        'total_proceed' => ++$totalProceed,
                        'last_item_id' => $item->getId()
                    ));
                }

                $end = microtime(true);
                Mage::log('Time to run the job id ' . $job->getId() . ' in ' . ($end-$start) . ' sec', Zend_Log::DEBUG, Diglin_Ricento_Helper_Data::LOG_FILE);

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

                if ($jobListing->getTotalCount() == $totalProceed) {
                    $jobListing
                        ->setLastItemId(null)
                        ->setTotalProceed($totalProceed)
                        ->save();
                    $progressStatus = Diglin_Ricento_Model_Sync_Job::PROGRESS_COMPLETED;
                    $endedAt = Mage::getSingleton('core/date')->gmtDate();

                    $listingLogUrl = $adminhtmlHelper->getUrl('ricento/log/listing', array('id' => $this->_productsListingId));
                    $listinggUrl = $adminhtmlHelper->getUrl('ricento/products_listing/edit', array('id' => $this->_productsListingId));
                    $message = $helper->__('The Job "%s" has finished with %s for the <a href="%s">products listing %d</a>. Please, view the <a href="%s">log</a> for details.', $job->getJobTypeLabel(), $typeError, $this->_productsListingId, $listinggUrl, $listingLogUrl);
                    $message .= $additionalMessage;
                } else {
                    $message = $job->getJobMessage();
                    $endedAt = null;
                }

                $job->getResource()->saveCurrentJob($job->getId(), array(
                    'job_message' => $message,
                    'job_status' => $jobStatus,
                    'ended_at' => $endedAt,
                    'progress' => $progressStatus
                ));

                $afterMethod = $jobMethod . 'After';
                if (method_exists($this, $afterMethod)) {
                    $this->$afterMethod($jobListing, $progressStatus);
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
     * @param Diglin_Ricento_Model_Products_Listing_Item $item
     * @param Diglin_Ricento_Model_Sync_Job $job
     * @return $this
     */
    protected function _proceedCheckList(Diglin_Ricento_Model_Products_Listing_Item $item, Diglin_Ricento_Model_Sync_Job $job)
    {
        // Detect which stores to use for each language defined at products listing level

        $stores = Mage::helper('diglin_ricento')->getStoresFromListing($item->getProductsListingId());

        // Profiler ~ 56ms / product on dev environment

        $itemValidator = new Diglin_Ricento_Model_Validate_Products_Item();

        $itemValidator->isValid($item, $stores);

        $this->_itemMessage = Mage::helper('core')->jsonEncode($itemValidator->getMessages());
        $this->_itemStatus = Diglin_Ricento_Model_Products_Listing_Log::STATUS_SUCCESS;

        if (count($itemValidator->getErrors())) {
            $this->_itemStatus = Diglin_Ricento_Model_Products_Listing_Log::STATUS_ERROR;
            $this->_jobHasError = true;
        } elseif (count($itemValidator->getWarnings())) {
            $this->_itemStatus = Diglin_Ricento_Model_Products_Listing_Log::STATUS_WARNING;
            $this->_jobHasWarning = true;
        }

        return $this;
    }

    /**
     * @param Diglin_Ricento_Model_Sync_Job_Listing $jobListing
     * @param string $progressStatus
     * @return $this
     */
    protected function _proceedCheckListAfter(Diglin_Ricento_Model_Sync_Job_Listing $jobListing, $progressStatus)
    {
        // Ready to list the product automatically

        if (!$this->_jobHasError && !$this->_jobHasWarning && $progressStatus == Diglin_Ricento_Model_Sync_Job::PROGRESS_COMPLETED) {
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
        if ($jobStatus == Diglin_Ricento_Model_Sync_Job::STATUS_WARNING) {
            $message = Mage::helper('diglin_ricento')->__('You can ignore the warnings and list your products on ricardo.ch by <a href="%s">clicking here</a>', $adminhtmlHelper->getUrl('ricento/products_listing/forceList', array('id' => $this->_productsListingId)));
        }
        return $message;
    }

    /**
     * @param Diglin_Ricento_Model_Products_Listing_Item $item
     * @param Diglin_Ricento_Model_Sync_Job $job
     * @return $this
     */
    protected function _processList(Diglin_Ricento_Model_Products_Listing_Item $item, Diglin_Ricento_Model_Sync_Job $job)
    {
        $sell = Mage::getSingleton('diglin_ricento/api_services_sell');

        $insertedArticle = $sell->insertArticle($item);

        Mage::log($insertedArticle);

        // @todo handle inserted article data.

        return $this;
    }

    /**
     * @param Diglin_Ricento_Model_Sync_Job_Listing $jobListing
     * @param $progressStatus
     * @return $this
     */
    protected function _proceedListAfter(Diglin_Ricento_Model_Sync_Job_Listing $jobListing, $progressStatus)
    {
        return $this;
    }

    /**
     * @param string $jobStatus
     * @return string
     */
    protected function _proceedListStatusMessage($jobStatus)
    {
        return '';
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
            $daysKeep = (int) Mage::getStoreConfig(Diglin_Ricento_Helper_Data::CFG_CLEAN_JOBS_KEEP_DAYS);

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
            $daysKeep = (int) Mage::getStoreConfig(Diglin_Ricento_Helper_Data::CFG_CLEAN_JOBS_KEEP_DAYS);

            $jobsCollection = Mage::getResourceModel('diglin_ricento/products_listing_log_collection');
            $jobsCollection
                ->getSelect()
                ->from(array('log' => $jobsCollection->getMainTable()), '')
                ->where('((TO_DAYS(log.created_at) + ? < TO_DAYS(now())))', $daysKeep);

            $jobsCollection->walk('delete');
        }
        return $this;
    }
}
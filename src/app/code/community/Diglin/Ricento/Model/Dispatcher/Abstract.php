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
abstract class Diglin_Ricento_Model_Dispatcher_Abstract
{
    /**
     * @var int
     */
    protected $_limit = 250;

    /**
     * @var string
     */
    protected $_progressStatus = Diglin_Ricento_Model_Sync_Job::PROGRESS_RUNNING;

    /**
     * @var Diglin_Ricento_Model_Resource_Products_Listing_Log
     */
    protected $_listingLog;

    /**
     * @var Diglin_Ricento_Model_Sync_Job
     */
    protected $_currentJob;

    /**
     * @var Diglin_Ricento_Model_Sync_Job_Listing
     */
    protected $_currentJobListing;

    /**
     * @var bool
     */
    protected $_jobHasSuccess = false;

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
    protected $_itemMessage = null;

    /**
     * @var int
     */
    protected $_productsListingId;

    /**
     * @var null
     */
    protected $_jobType = null;

    /**
     * @var int
     */
    protected $_logType = 0;

    /**
     * @var int
     */
    protected $_totalProceed = 0;

    /**
     * @var int
     */
    protected $_totalSuccess = 0;

    /**
     * @var int
     */
    protected $_totalError = 0;

    /**
     * @return $this
     */
    public function proceed()
    {
        $this->_runJobs();
        return $this;
    }

    /**
     * @return mixed
     */
    abstract protected function _proceed();

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
     * @return $this
     */
    protected function _startJob()
    {
        $this->_currentJob
            ->setStartedAt(Mage::getSingleton('core/date')->gmtDate())
            ->setProgress($this->_progressStatus)
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
     * @return $this
     */
    protected function _runJobs()
    {
        /**
         * Get all pending jobs of specified type - the risk is very low to have for this collection a big quantity of data
         */
        $jobsCollection = $this->_getJobCollection($this->_jobType,
            array(Diglin_Ricento_Model_Sync_Job::PROGRESS_PENDING, Diglin_Ricento_Model_Sync_Job::PROGRESS_CHUNK_RUNNING));

        $helper = $this->_getHelper();

        try {
            /* @var $job Diglin_Ricento_Model_Sync_Job */
            foreach ($jobsCollection->getItems() as $this->_currentJob) {

                $message = array();

                $this->_currentJobListing = Mage::getModel('diglin_ricento/sync_job_listing')->load($this->_currentJob->getId(), 'job_id');
                $this->_productsListingId = (int) $this->_currentJobListing->getProductsListingId();
                $this->_totalProceed = (int) $this->_currentJobListing->getTotalProceed();

                if (!$this->_productsListingId) {
                    return $this;
                }

                /**
                 * We set the status to block any parallel process to execute the same job. In case of recoverable error, the job status is reverted
                 */
                if ($this->_currentJob->getProgress() == Diglin_Ricento_Model_Sync_Job::PROGRESS_PENDING) {
                    $this->_startJob();
                }

                /**
                 * Cleanup in case of running again the same job
                 */
                if (!$this->_currentJobListing->getLastItemId()) {
                    $this->_getListingLog()->cleanSpecificJob($this->_currentJob->getId());
                }

                $start = microtime(true);

                /**
                 * All the Magic is here ...
                 */
                $this->_proceed();

                $end = microtime(true);
                Mage::log('Time to run the job id ' . $this->_currentJob->getId() . ' in ' . ($end - $start) . ' sec', Zend_Log::DEBUG, Diglin_Ricento_Helper_Data::LOG_FILE);

                if ($this->_jobHasError || $this->_currentJob->getJobStatus() == Diglin_Ricento_Model_Sync_Job::STATUS_ERROR) {
                    $typeError = $helper->__('errors');
                    $jobStatus = Diglin_Ricento_Model_Sync_Job::STATUS_ERROR;
                } else if ($this->_jobHasWarning || $this->_currentJob->getJobStatus() == Diglin_Ricento_Model_Sync_Job::STATUS_WARNING) {
                    $typeError = $helper->__('warnings');
                    $jobStatus = Diglin_Ricento_Model_Sync_Job::STATUS_WARNING;
                } else {
                    $typeError = $helper->__('success');
                    $jobStatus = Diglin_Ricento_Model_Sync_Job::STATUS_SUCCESS;
                }

                /**
                 * In case we proceed chunk of data or not
                 */
                $endedAt = null;

                if ($this->_currentJobListing->getTotalCount() == $this->_totalProceed) {
                    $this->_progressStatus = Diglin_Ricento_Model_Sync_Job::PROGRESS_COMPLETED;
                    $endedAt = Mage::getSingleton('core/date')->gmtDate();

                    $this->_currentJobListing
                        ->setLastItemId(null)
                        ->setTotalProceed($this->_totalProceed)
                        ->save();

                    $message[] = $helper->__('The Job has finished with %s for the <a href="%s" target="_blank">products listing %d</a>. Please, view the <a href="%s">log</a> for details.',
                        $typeError,
                        $this->_getProductListingEditUrl(),
                        $this->_productsListingId,
                        $this->_getLogListingUrl()
                    );

                } else {
                    $message = array_merge($message, (is_array($this->_currentJob->getJobMessage()) ? $this->_currentJob->getJobMessage() : array($this->_currentJob->getJobMessage())));
                    $this->_progressStatus = Diglin_Ricento_Model_Sync_Job::PROGRESS_CHUNK_RUNNING;
                }

                $statusMessage = $this->_getStatusMessage($jobStatus);
                if (!empty($statusMessage)) {
                    $message[] = $statusMessage;
                }

                $this->_currentJob->getResource()->saveCurrentJob($this->_currentJob->getId(), array(
                    'job_message' => $this->_jsonEncode($message),
                    'job_status' => $jobStatus,
                    'ended_at' => $endedAt,
                    'progress' => $this->_progressStatus,
                ));

                $this->_proceedAfter();

                $this->_currentJob = null;
                $this->_currentJobListing = null;
            }
        } catch (Exception $e) {
            Mage::log("\n" . $e->__toString(), Zend_Log::ERR, Diglin_Ricento_Helper_Data::LOG_FILE);
            $this->_currentJob = (isset($this->_currentJob)) ? $this->_currentJob : null;
            $this->_setJobError($e);
        }

        unset($jobsCollection);

        return $this;
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
     * @return Diglin_Ricento_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('diglin_ricento');
    }

    /**
     * @return string
     */
    protected function _getNoItemMessage()
    {
        return $this->_getHelper()->__('No item is ready for this job. No action has been done.');
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
     * @return string
     */
    protected function _getLogListingUrl()
    {
        return '{{adminhtml url="ricento/log/listing/" _query_id=' . $this->_productsListingId . ' _query_job_id=' . $this->_currentJob->getId() . '}}';
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
    protected function _getListUrl()
    {
        return '{{adminhtml url="ricento/products_listing/list/" _query_id=' . $this->_productsListingId . '}}';
    }

    /**
     * @param $jobStatus
     * @return $this
     */
    protected function _getStatusMessage($jobStatus)
    {
        return '';
    }

    /**
     * @return $this
     */
    protected function _proceedAfter()
    {
        return $this;
    }

    /**
     * @param Exception $e
     * @return $this
     */
    protected function _setJobError(Exception $e)
    {
        if (isset($this->_currentJob) && $this->_currentJob instanceof Diglin_Ricento_Model_Sync_Job && $this->_currentJob->getId()) {
            $this->_currentJob
                ->setProgress(Diglin_Ricento_Model_Sync_Job::PROGRESS_CHUNK_RUNNING)
                ->setJobStatus(Diglin_Ricento_Model_Sync_Job::STATUS_ERROR)
                ->setJobMessage(array($e->__toString()))
                ->save();
        }
        return $this;
    }

    /**
     * @param Exception $e
     * @param null|Diglin_Ricento_Model_Api_Services_Abstract $lastService
     * @return $this
     */
    protected function _handleException(Exception $e, $lastService = null)
    {
        if ($this->_getHelper()->isDebugEnabled() && $lastService instanceof Diglin_Ricento_Model_Api_Services_Abstract) {
            Mage::log($lastService->getLastApiDebug(), Zend_Log::DEBUG, Diglin_Ricento_Helper_Data::LOG_FILE);
        }

        Mage::log("\n" . $e->__toString(), Zend_Log::ERR, Diglin_Ricento_Helper_Data::LOG_FILE);

        $this->_itemMessage = array('errors' =>
            array(
                $this->_getHelper()->__('Error Code: %d', $e->getCode()),
                $this->_getHelper()->__($e->getMessage())
            ));

        $this->_jobHasError = true;
        $this->_itemStatus = Diglin_Ricento_Model_Products_Listing_Log::STATUS_ERROR;
        ++$this->_totalError;

        return $this;
    }

    /**
     * @param int $productsListingId
     * @return $this
     */
    public function setProductsListingId($productsListingId)
    {
        $this->_productsListingId = $productsListingId;
        return $this;
    }

    /**
     * @return int
     */
    public function getProductsListingId()
    {
        return $this->_productsListingId;
    }

    /**
     * @return Diglin_Ricento_Model_Products_Listing
     */
    protected function _getListing()
    {
        return Mage::getModel('diglin_ricento/products_listing')->load($this->_productsListingId);
    }
}
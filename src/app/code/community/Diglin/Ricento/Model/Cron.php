<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
class Diglin_Ricento_Model_Cron
{
    public function process()
    {
        $this->_processCheckItems();
        $this->_processCleanupJobs();
        $this->_processCleanupListingLog();
    }

    /**
     * Check items before a synchronization
     *
     * @return $this
     * @throws Exception
     */
    protected function _processCheckItems()
    {
        // Get all pending jobs of type "check"
        $jobsCollection = Mage::getResourceModel('diglin_ricento/sync_job_collection');
        $jobsCollection
            ->addFieldToFilter('job_type', Diglin_Ricento_Model_Sync_Job::TYPE_CHECK_LIST) //@todo add other check job types
            ->addFieldToFilter('progress', Diglin_Ricento_Model_Sync_Job::PROGRESS_PENDING);

        $listingLog = Mage::getResourceModel('diglin_ricento/products_listing_log');

        $helper = Mage::helper('diglin_ricento');

        try {
            foreach ($jobsCollection->getItems() as $job) {

                $start = microtime(true);

                $message = '';
                $jobHasError = false;
                $jobHasWarning = false;

                // Set job to running to prevent conflict of multiple instance

                $job
                    ->setStartedAt(Mage::getSingleton('core/date')->gmtDate())
                    ->setProgress(Diglin_Ricento_Model_Sync_Job::PROGRESS_RUNNING)
                    ->save();

                $jobListing = Mage::getModel('diglin_ricento/sync_job_listing')->load($job->getId(), 'job_id');

                if ($jobListing->getProductsListingId()) {

                    // Get all pending products listing item to be checked

                    $itemCollection = Mage::getResourceModel('diglin_ricento/products_listing_item_collection');
                    $itemCollection
                        ->addFieldToFilter('status', array('in' => Diglin_Ricento_Helper_Data::STATUS_PENDING))
                        ->addFieldToFilter('products_listing_id', array('eq' => $jobListing->getProductsListingId()))
                        ->setOrder('item_id', 'DESC');

                    $totalProceed = $jobListing->getTotalProceed();

                    foreach ($itemCollection->getItems() as $item) {

                        // Check if the items are valid

                        $itemValidator = new Diglin_Ricento_Model_Validate_Products_Item();
                        $itemValidator->isValid($item);

                        $status = Diglin_Ricento_Model_Products_Listing_Log::STATUS_SUCCESS;
                        if (count($itemValidator->getErrors())) {
                            $status = Diglin_Ricento_Model_Products_Listing_Log::STATUS_ERROR;
                            $jobHasError = true;
                        } elseif (count($itemValidator->getWarnings())) {
                            $status = Diglin_Ricento_Model_Products_Listing_Log::STATUS_WARNING;
                            $jobHasWarning = true;
                        }

                        // Save the current information of the process to allow live display via ajax

                        $jobListing->saveCurrentJob(array(
                            'total_proceed' => ++$totalProceed,
                            'last_item_id' => $item->getId()
                        ));

                        // Save item information and eventual error messages

                        $listingLog->saveLog(array(
                            'product_title' => $item->getProductTitle(),
                            'products_listing_id' => $jobListing->getProductsListingId(),
                            'product_id' => $item->getProductId(),
                            'message' => Mage::helper('core')->jsonEncode($itemValidator->getMessages()),
                            'log_status' => $status,
                            'log_type' => Diglin_Ricento_Model_Products_Listing_Log::LOG_TYPE_CHECK
                        ));
                    }
                }

                $listingLogUrl = Mage::helper('adminhtml')->getUrl('ricento/log/listing', array('id' => $jobListing->getProductsListingId()));
                $listinggUrl = Mage::helper('adminhtml')->getUrl('ricento/products_listing/edit', array('id' => $jobListing->getProductsListingId()));

                if ($jobHasError) {
                    $typeError = $helper->__('errors');
                    $jobStatus = Diglin_Ricento_Model_Sync_Job::STATUS_ERROR;
                } else if ($jobHasWarning) {
                    $typeError = $helper->__('warnings');
                    $jobStatus = Diglin_Ricento_Model_Sync_Job::STATUS_WARNING;
                } else {
                    $typeError = $helper->__('success');
                    $jobStatus = Diglin_Ricento_Model_Sync_Job::STATUS_SUCCESS;
                }

                $message = $helper->__('The Job "%s" has finished with %s for <a href="%s">the products listing</a>. Please, view <a href="%s">products listing log</a> for details', $job->getJobTypeLabel(), $typeError, $listinggUrl, $listingLogUrl);

                $job->getResource()->saveCurrentJob($job->getId(), array(
                    'job_message' => $message,
                    'job_status' => $jobStatus,
                    'ended_at' => Mage::getSingleton('core/date')->gmtDate(),
                    'progress' => Diglin_Ricento_Model_Sync_Job::PROGRESS_COMPLETED
                ));

                // Ready to list the product

                if (!$jobHasError && !$jobHasWarning) {
                    $jobList = Mage::getModel('diglin_ricento/sync_job');
                    $jobList
                        ->setProgress(Diglin_Ricento_Model_Sync_Job::PROGRESS_PENDING)
                        ->setJobType(Diglin_Ricento_Model_Sync_Job::TYPE_LIST)
                        ->setJobMessage($job->getJobMessage())
                        ->save();

                    $jobListingList = Mage::getModel('diglin_ricento/sync_job_listing');
                    $jobListingList
                        ->setProductsListingId($jobListing->getProductsListingId())
                        ->setTotalCount($jobListing->getTotalCount())
                        ->setTotalProceed(0)
                        ->setJobId($jobList->getId())
                        ->save();
                }

                $end = microtime(true);
                Mage::log('Time to check the job id ' . $job->getId() . ' in ' . ($end-$start) . ' sec', Zend_Log::DEBUG, Diglin_Ricento_Helper_Data::LOG_FILE);
            }
        } catch (Exception $e) {
            if (isset($job) && $job instanceof Diglin_Ricento_Model_Sync_Job && $job->getId()) {
                $job
                    ->setProgress(Diglin_Ricento_Model_Sync_Job::PROGRESS_PENDING)
                    ->save();
            }
            throw $e;
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
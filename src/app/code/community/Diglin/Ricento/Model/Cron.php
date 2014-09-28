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

/**
 * Class Diglin_Ricento_Model_Cron
 */
class Diglin_Ricento_Model_Cron
{
    /**
     * Process Cron tasks - should be run in a short period of time
     */
    public function process()
    {
        //** Launch Pending Jobs

        //** Check list before to sync to ricardo.ch

        $this->dispatch(Diglin_Ricento_Model_Sync_Job::TYPE_CHECK_LIST);

        //** List to ricardo.ch

        $this->dispatch(Diglin_Ricento_Model_Sync_Job::TYPE_LIST);

        //** Stop the list on ricardo.ch if needed

        $this->dispatch(Diglin_Ricento_Model_Sync_Job::TYPE_STOP);

        //** Relist to ricardo.ch

        $this->dispatch(Diglin_Ricento_Model_Sync_Job::TYPE_RELIST);
    }

    /**
     * Process Cron tasks which needs to be run on a longer period of time
     */
    public function async()
    {
        //** Sync List before getting orders

        $this->dispatch(Diglin_Ricento_Model_Sync_Job::TYPE_SYNCLIST);

        //** Get new orders

        $this->dispatch(Diglin_Ricento_Model_Sync_Job::TYPE_ORDER);

        //** Cleanup

        $this->_processCleanupJobs();
        $this->_processCleanupListingLog();
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
     * @return Diglin_Ricento_Model_Dispatcher
     */
    protected function _getDisptacher()
    {
        return Mage::getSingleton('diglin_ricento/dispatcher');
    }

    /**
     * @param int $type
     * @return $this
     */
    protected function dispatch ($type)
    {
        return $this->_getDisptacher()->dispatch($type)->proceed();
    }
}
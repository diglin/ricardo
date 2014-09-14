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
 * Class Diglin_Ricento_Model_Sync_Job
 *
 * @method string getJobType()
 * @method string getJobStatus()
 * @method string getProgress()
 * @method bool    getLocked()
 * @method DateTime getStartedAt()
 * @method DateTime getEndedAt()
 * @method DateTime getCreatedAt()
 * @method DateTime getUpdatedAt()
 * @method Diglin_Ricento_Model_Sync_Job setJobMessage(string $message)
 * @method Diglin_Ricento_Model_Sync_Job setJobType(string $type)
 * @method Diglin_Ricento_Model_Sync_Job setJobStatus(string $status)
 * @method Diglin_Ricento_Model_Sync_Job setProgress(string $progress)
 * @method Diglin_Ricento_Model_Sync_Job setLocked(bool $locked)
 * @method Diglin_Ricento_Model_Sync_Job setStartedAt(DateTime $starteddAt)
 * @method Diglin_Ricento_Model_Sync_Job setEndedAt(DateTime $endedAt)
 * @method Diglin_Ricento_Model_Sync_Job setCreatedAt(DateTime $createdAt)
 * @method Diglin_Ricento_Model_Sync_Job setUpdatedAt(DateTime $updatedAt)
 */
class Diglin_Ricento_Model_Sync_Job extends Diglin_Ricento_Model_Sync_Abstract
{
    // TYPES OF JOB
    const TYPE_CHECK_LIST   = 'check_list';
    const TYPE_LIST         = 'list';
    const TYPE_RELIST       = 'relist';
    const TYPE_STOP         = 'stop';
    const TYPE_UPDATE       = 'update';

    // PROGRESS
    const PROGRESS_PENDING      = 'pending';
    const PROGRESS_RUNNING      = 'running';
    const PROGRESS_CHUNK_RUNNING = 'chunk_running';
    const PROGRESS_COMPLETED    = 'completed';
    const PROGRESS_READY        = 'ready';

    // STATUSES
    const STATUS_NOTICE     = 'notice';
    const STATUS_WARNING    = 'warning';
    const STATUS_ERROR      = 'error';
    const STATUS_SUCCESS    = 'success';

    /**
     * Prefix of model events names
     * @var string
     */
    protected $_eventPrefix = 'sync_job';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'sync_job';

    protected function _construct()
    {
        $this->_init('diglin_ricento/sync_job');
    }

    /**
     * Load the job by Job Type and Products Listing Id
     *
     * @param string $type
     * @param int $productsListingId
     * @return $this|bool
     */
    public function loadByJobTypeAndProductsListingId($type, $productsListingId)
    {
        $jobId = $this->getResource()->getSyncByTypeProductsListing($type, $productsListingId);
        if ($jobId) {
            $this->load($jobId);
            return $this;
        }

        return false;
    }

    /**
     * @return array
     */
    public function getJobMessage()
    {
        $helper = Mage::helper('diglin_ricento');

        if ($this->getData('job_message')) {
            return $this->getData('job_message');
        } else {
            switch ($this->getJobType()) {
                case self::TYPE_CHECK_LIST:
                    if (in_array($this->getProgress(), array(self::PROGRESS_PENDING, self::PROGRESS_CHUNK_RUNNING))) {
                        return $helper->__('Check in progress...');
                    }
                break;
                case self::TYPE_LIST:
                    if (in_array($this->getProgress(), array(self::PROGRESS_PENDING, self::PROGRESS_CHUNK_RUNNING))) {
                        return $helper->__('List in progress...');
                    }
                    break;
            }
        }
    }

    /**
     * @return string
     */
    public function getJobTypeLabel()
    {
        $logTypes = Mage::getModel('diglin_ricento/config_source_sync_type')->toOptionHash();

        return $logTypes[$this->getJobType()];
    }
}
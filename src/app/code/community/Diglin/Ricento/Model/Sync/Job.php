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
 * @method string getJobMessage()
 * @method string getJobType()
 * @method int    getProductsListingId()
 * @method string getStatus()
 * @method int    getTotalCount()
 * @method int    getTotalProceed()
 * @method int    getLastItemId()
 * @method DateTime getStartedAt()
 * @method DateTime getCreatedAt()
 * @method DateTime getUpdatedAt()
 * @method Diglin_Ricento_Model_Sync_Job setJobMessage(string $message)
 * @method Diglin_Ricento_Model_Sync_Job setJobType(string $type)
 * @method Diglin_Ricento_Model_Sync_Job setProductsListingId(int $id)
 * @method Diglin_Ricento_Model_Sync_Job setStatus(string $status)
 * @method Diglin_Ricento_Model_Sync_Job setTotalCount(int $totalCount)
 * @method Diglin_Ricento_Model_Sync_Job setTotalProceed(int $totalProceed)
 * @method Diglin_Ricento_Model_Sync_Job setLastItemId(int $lastItemId)
 * @method Diglin_Ricento_Model_Sync_Job setStartedAt(DateTime $starteddAt)
 * @method Diglin_Ricento_Model_Sync_Job setCreatedAt(DateTime $createdAt)
 * @method Diglin_Ricento_Model_Sync_Job setUpdatedAt(DateTime $updatedAt)
 */
class Diglin_Ricento_Model_Sync_Job extends Mage_Core_Model_Abstract
{
    // TYPES OF SYNC
    const TYPE_CHECK = 'check';
    const TYPE_SYNC = 'sync';

    // STATUSES
    const STATUS_PENDING = 'pending';
    const STATUS_RUNNING = 'running';
    const STATUS_CHUNK_RUNNING = 'chunk_running';
    const STATUS_COMPLETED = 'completed';

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
     * Set date of last update, convert payment method array to string
     *
     * @return Diglin_Ricento_Model_Sync_Job
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        $this->setUpdatedAt(Mage::getSingleton('core/date')->gmtDate());
        return $this;
    }

    /**
     * Load the job by Job Type and Products Listing Id
     *
     * @param $type
     * @param $productsListingId
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
     * Save the current status of a job
     *
     * @param $status
     * @param $totalProceed
     * @param int $lastItemId
     * @param null $jobId
     * @return int|bool
     */
    public function saveCurrentJob($status, $totalProceed, $lastItemId = 0, $jobId = null)
    {
        if (is_null($jobId)) {
            $jobId = $this->getId();
        }

        if (is_null($jobId)) {
            return false;
        }

        return $this->getResource()->saveCurrent($jobId, $status, $totalProceed, $lastItemId);
    }
}
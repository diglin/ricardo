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
 * Class Diglin_Ricento_Model_Sync_Job
 *
 * @method string getJobStatus()
 * @method string getJobType()
 * @method string getProgress()
 * @method DateTime getStartedAt()
 * @method DateTime getEndedAt()
 * @method DateTime getCreatedAt()
 * @method DateTime getUpdatedAt()
 * @method Diglin_Ricento_Model_Sync_Job setJobMessage(string $message)
 * @method Diglin_Ricento_Model_Sync_Job setJobType(string $type)
 * @method Diglin_Ricento_Model_Sync_Job setJobStatus(string $status)
 * @method Diglin_Ricento_Model_Sync_Job setProgress(string $progress)
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
    const TYPE_ORDER        = 'order';
    const TYPE_SYNCLIST     = 'sync_list';

    // PROGRESS
    const PROGRESS_PENDING          = 'pending';
    const PROGRESS_RUNNING          = 'running';
    const PROGRESS_CHUNK_RUNNING    = 'chunk_running';
    const PROGRESS_COMPLETED        = 'completed';
    const PROGRESS_READY            = 'ready';

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

    protected function _afterLoad()
    {
        try {
            $jobMessage = $this->getData('job_message');
            if (!is_array($jobMessage)) {
                $this->setJobMessage(Mage::helper('core')->jsonDecode($jobMessage));
            }
        } catch (Exception $e) {
            // keep going, do not block the script
            Mage::logException($e);
        }

        return parent::_afterLoad();
    }

    protected function _beforeSave()
    {
        $message = $this->getData('job_message');
        if (is_array($message)) {
            $this->setJobMessage(Mage::helper('core')->jsonEncode($message));
        }
        return parent::_beforeSave();
    }

    /**
     * Load the job by Job Type and Products Listing Id
     *
     * @param string $type
     * @param int $productsListingId
     * @param string|null $progress
     * @return $this|bool
     */
    public function loadByTypeListingIdProgress($type, $productsListingId, $progress = null)
    {
        $jobId = $this->getResource()->loadByTypeListingIdProgress($type, $productsListingId, $progress);
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
                default:
                    if (in_array($this->getProgress(), array(self::PROGRESS_PENDING, self::PROGRESS_CHUNK_RUNNING))) {
                        return $helper->__('Job in progress...');
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
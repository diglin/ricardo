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
 * Class Diglin_Ricento_Model_Resource_Sync_Job
 */
class Diglin_Ricento_Model_Resource_Sync_Job extends Diglin_Ricento_Model_Resource_Sync_Abstract
{
    /**
     * Sync Resource Constructor
     * @return void
     */
    protected function _construct()
    {
        $this->_init('diglin_ricento/sync_job', 'job_id');
    }

    /**
     * @param $type
     * @param $productsListingId
     * @param null $progress
     * @return string
     */
    public function loadByTypeListingIdProgress($type, $productsListingId, $progress = null)
    {
        $readConnection = $this->_getReadAdapter();

        $select = $readConnection
            ->select(array('job_id'))
            ->from(array('sj' => $this->getTable('diglin_ricento/sync_job')))
            ->joinLeft(array('sjl' => $this->getTable('diglin_ricento/sync_job_listing')), 'sjl.job_id = sj.job_id')
            ->where('sj.job_type = :job_type AND sjl.products_listing_id = :products_listing_id');

        $bind = array('job_type' => $type, 'products_listing_id' => (int) $productsListingId);

        if (!is_null($progress)) {
            if (!is_array($progress)) {
                $progress = array($progress);
            }
            $select->where('sj.progress IN (?)', $progress);
        }

        return $readConnection->fetchOne($select, $bind);
    }

    /**
     * @param $jobType
     * @param $productListingId
     * @return $this
     */
    public function cleanupPendingJob($jobType, $productListingId)
    {
        $readConnection = $this->_getReadAdapter();

        $select = $readConnection
            ->select()
            ->from(array('sj' => $this->getTable('diglin_ricento/sync_job')), 'job_id')
            ->join(array('jl' => $this->getTable('diglin_ricento/sync_job_listing')),
                'jl.job_id = sj.job_id', '*')
            ->where('jl.products_listing_id = ?', $productListingId)
            ->where('job_Type = ? ', $jobType)
            ->where('progress = ?', Diglin_Ricento_Model_Sync_Job::PROGRESS_PENDING)
            ->deleteFromSelect('sj');

        if (!empty($select) && !is_numeric($select)) {
            $readConnection->query($select);
        }

        return $this;
    }
}
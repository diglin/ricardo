<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
class Diglin_Ricento_Model_Resource_Sync_Job extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Sync Resource Constructor
     * @return void
     */
    protected function _construct()
    {
        $this->_init('diglin_ricento/sync_job', 'job_id');
    }

    public function getSyncByTypeProductsListing($type, $productsListingId)
    {
        $readConnection = $this->_getReadAdapter();

        $select = $readConnection
            ->select(array('job_id'))
            ->from($this->getTable('diglin_ricento/sync_job'))
            ->where('job_type = :job_type AND products_listing_id = :products_listing_id');
        $bind = array('job_type' => $type, 'products_listing_id' => (int) $productsListingId);

        return $readConnection->fetchOne($select, $bind);
    }

    /**
     * @param int $jobId
     * @param string $status
     * @param int $totalProceed
     * @param int $lastItemId
     * @return int
     */
    public function saveCurrent($jobId, $status, $totalProceed, $lastItemId = 0)
    {
        $writeConection = $this->_getWriteAdapter();

        $bind = array(
            'status' => $status,
            'total_proceed' => $totalProceed,
            'last_item_id' => $lastItemId
        );

        return $writeConection->update(
            $this->getTable('diglin_ricento/sync_job'),
            $bind,
            array('job_id = ?' => $jobId));
    }
}
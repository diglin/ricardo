<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

class Diglin_Ricento_Model_Resource_Products_Listing_Log extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Products_Listing_Item Resource Constructor
     * @return void
     */
    protected function _construct()
    {
        $this->_init('diglin_ricento/listing_log', 'log_id');
    }

    /**
     * @param array $bind
     * @return int
     */
    public function saveLog($bind)
    {
        $writeConection = $this->_getWriteAdapter();

        return $writeConection->insert(
            $this->getMainTable(),
            $bind);
    }

    /**
     * @param int $jobId
     * @return int
     */
    public function cleanSpecificJob($jobId)
    {
        $writeConection = $this->_getWriteAdapter();
        return $writeConection->delete(
            $this->getMainTable(),
            array('job_id = ?' => $jobId)
        );
    }
}
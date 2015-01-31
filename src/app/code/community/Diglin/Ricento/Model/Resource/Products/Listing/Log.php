<?php
/**
 * ricardo.ch AG - Switzerland
 *
 * @author      Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2014 ricardo.ch AG (http://www.ricardo.ch)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Class Diglin_Ricento_Model_Resource_Products_Listing_Log
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
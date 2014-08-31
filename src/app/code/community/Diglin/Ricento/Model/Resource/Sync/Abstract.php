<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

abstract class Diglin_Ricento_Model_Resource_Sync_Abstract extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * @param int $jobId
     * @param array $bind
     * @return int
     */
    public function saveCurrentJob($jobId, $bind)
    {
        $writeConection = $this->_getWriteAdapter();

        return $writeConection->update(
            $this->getMainTable(),
            $bind,
            array($this->getIdFieldName() . ' = ?' => $jobId));
    }
}
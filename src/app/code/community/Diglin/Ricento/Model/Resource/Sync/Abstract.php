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
 * Class Diglin_Ricento_Model_Resource_Sync_Abstract
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
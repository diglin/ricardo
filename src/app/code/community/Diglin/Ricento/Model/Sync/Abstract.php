<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

abstract class Diglin_Ricento_Model_Sync_Abstract extends Mage_Core_Model_Abstract
{
    /**
     * Set date of last update, convert payment method array to string
     *
     * @return Diglin_Ricento_Model_Sync_Abstract
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        $this->setUpdatedAt(Mage::getSingleton('core/date')->gmtDate());
        return $this;
    }

    /**
     * Save the current status of a job
     *
     * @param array $bind
     * @return int|bool
     */
    public function saveCurrentJob($bind)
    {
        $jobId = $this->getId();

        if (is_null($jobId)) {
            return false;
        }

        return $this->getResource()->saveCurrentJob($jobId, $bind);
    }
}
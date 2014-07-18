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
 * Resource Model of Sync_Log
 */
class Diglin_Ricento_Model_Resource_Sync_Log extends Mage_Core_Model_Resource_Db_Abstract
{


    /**
     * Sync_Log Resource Constructor
     * @return void
     */
    protected function _construct()
    {
        $this->_init('diglin_ricento/sync_log', 'job_id');
    }


}
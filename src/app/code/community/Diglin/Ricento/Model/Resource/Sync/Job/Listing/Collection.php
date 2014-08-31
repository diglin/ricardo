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
 * Class Diglin_Ricento_Model_Resource_Sync_Job_Listing_Collection
 */
class Diglin_Ricento_Model_Resource_Sync_Job_Listing_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Sync Collection Resource Constructor
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('diglin_ricento/sync_job_listing');
    }
}
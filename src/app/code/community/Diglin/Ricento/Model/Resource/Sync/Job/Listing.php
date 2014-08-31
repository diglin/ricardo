<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
class Diglin_Ricento_Model_Resource_Sync_Job_Listing extends Diglin_Ricento_Model_Resource_Sync_Abstract
{
    protected function _construct()
    {
        $this->_init('diglin_ricento/sync_job_listing', 'job_listing_id');
    }
}
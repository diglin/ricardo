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
 * Class Diglin_Ricento_Model_Resource_Sync_Job_Listing
 */
class Diglin_Ricento_Model_Resource_Sync_Job_Listing extends Diglin_Ricento_Model_Resource_Sync_Abstract
{
    protected function _construct()
    {
        $this->_init('diglin_ricento/sync_job_listing', 'job_listing_id');
    }
}
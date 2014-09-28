<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Diglin_Ricento_Model_Resource_Sync_Job extends Diglin_Ricento_Model_Resource_Sync_Abstract
{
    /**
     * Sync Resource Constructor
     * @return void
     */
    protected function _construct()
    {
        $this->_init('diglin_ricento/sync_job', 'job_id');
    }

    /**
     * @param $type
     * @param $productsListingId
     * @return string
     */
    public function getSyncByTypeProductsListing($type, $productsListingId)
    {
        $readConnection = $this->_getReadAdapter();

        $select = $readConnection
            ->select(array('job_id'))
            ->from(array('sj' => $this->getTable('diglin_ricento/sync_job')))
            ->joinLeft(array('sjl' => $this->getTable('diglin_ricento/sync_job_listing')), 'sjl.job_id = sj.job_id')
            ->where('sj.job_type = :job_type AND sjl.products_listing_id = :products_listing_id');

        $bind = array('job_type' => $type, 'products_listing_id' => (int) $productsListingId);

        return $readConnection->fetchOne($select, $bind);
    }
}
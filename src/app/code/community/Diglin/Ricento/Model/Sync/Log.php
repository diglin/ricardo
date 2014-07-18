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
 * Sync_Log Model
 *
 * @method string getJobMessage() getJobMessage()
 * @method int    getProductsListingId() getProductsListingId()
 * @method int    getStatus() getStatus()
 * @method DateTime getCreatedAt() getCreatedAt()
 * @method DateTime getUpdatedAt() getUpdatedAt()
 * @method Diglin_Ricento_Model_Sync_Log setJobMessage() setJobMessage(string $message)
 * @method Diglin_Ricento_Model_Sync_Log setProductsListingId() setProductsListingId(int $id)
 * @method Diglin_Ricento_Model_Sync_Log setStatus() setStatus(int $status)
 * @method Diglin_Ricento_Model_Sync_Log setCreatedAt() setCreatedAt(DateTime $createdAt)
 * @method Diglin_Ricento_Model_Sync_Log setUpdatedAt() setUpdatedAt(DateTime $updatedAt)
 */
class Diglin_Ricento_Model_Sync_Log extends Mage_Core_Model_Abstract
{


    /**
     * Prefix of model events names
     * @var string
     */
    protected $_eventPrefix = 'sync_log';

    /**
     * Parameter name in event
     * In observe method you can use $observer->getEvent()->getObject() in this case
     * @var string
     */
    protected $_eventObject = 'sync_log';

    /**
     * Sync_Log Constructor
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('diglin_ricento/sync_log');
    }

    /**
     * Set date of last update
     *
     * @return Diglin_Ricento_Model_Sync_Log
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        $this->setUpdatedAt(Mage::getSingleton('core/date')->gmtDate());
        return $this;
    }

}
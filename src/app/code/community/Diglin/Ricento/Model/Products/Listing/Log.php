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
 * Class Diglin_Ricento_Model_Products_Listing_Log
 *
 * @method string getMessage()
 * @method int    getJobId()
 * @method int    getProductsListingId()
 * @method int    getProductId()
 * @method string getProductTitle()
 * @method string    getLogStatus()
 * @method string    getLogType()
 * @method DateTime getCreatedAt()
 * @method DateTime getUpdatedAt()
 * @method Diglin_Ricento_Model_Products_Listing_Log setMessage(string $message)
 * @method Diglin_Ricento_Model_Products_Listing_Log setJobId(int $id)
 * @method Diglin_Ricento_Model_Products_Listing_Log setProductsListingId(int $id)
 * @method Diglin_Ricento_Model_Products_Listing_Log setProductId(int $id)
 * @method Diglin_Ricento_Model_Products_Listing_Log setProductTitle(string $title)
 * @method Diglin_Ricento_Model_Products_Listing_Log setLogStatus(string $status)
 * @method Diglin_Ricento_Model_Products_Listing_Log setCreatedAt() setCreatedAt(DateTime $createdAt)
 * @method Diglin_Ricento_Model_Products_Listing_Log setUpdatedAt() setUpdatedAt(DateTime $updatedAt)
 */
class Diglin_Ricento_Model_Products_Listing_Log extends Mage_Core_Model_Abstract
{
    // STATUSES
    const STATUS_NOTICE     = 'notice';
    const STATUS_WARNING    = 'warning';
    const STATUS_ERROR      = 'error';
    const STATUS_SUCCESS    = 'success';

    // TYPE OF LOGS
    const LOG_TYPE_CHECK = 1;
    const LOG_TYPE_LIST = 2;
    const LOG_TYPE_STOP = 3;
    const LOG_TYPE_RELIST = 3;

    /**
     * Prefix of model events names
     * @var string
     */
    protected $_eventPrefix = 'listing_log';

    /**
     * Parameter name in event
     * In observe method you can use $observer->getEvent()->getObject() in this case
     * @var string
     */
    protected $_eventObject = 'listing_log';

    /**
     * Sync_Log Constructor
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('diglin_ricento/products_listing_log');
    }

    /**
     * @return string
     */
    public function getLogTypeMessage()
    {
        $sourceLog = Mage::getModel('diglin_ricento/config_source_products_listing_log')->toOptionHash();
        return $sourceLog[$this->getLogType()];
    }
}
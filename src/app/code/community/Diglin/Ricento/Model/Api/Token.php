<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

/**
 * Api_Token Model
 *
 * @method string   getToken() getToken()
 * @method string   getTokenType() getTokenType()
 * @method int      getStoreId() getStoreId()
 * @method DateTime getExpirationDate() getExpirationDate()
 * @method int      getSessionDuration() getSessionDuration()
 * @method DateTime getSessionExpirationDate() getSessionExpirationDate()
 * @method DateTime getCreatedAt() getCreatedAt()
 * @method Diglin_Ricento_Model_Api_Token setToken() setToken(string $token)
 * @method Diglin_Ricento_Model_Api_Token setTokenType() setTokenType(string $tokenType)
 * @method Diglin_Ricento_Model_Api_Token setStoreId() setStoreId(int $storeId)
 * @method Diglin_Ricento_Model_Api_Token setSessionDuration() setSessionDuration(int $sessionDuration)
 * @method Diglin_Ricento_Model_Api_Token setExpirationDate() setExpirationDate(DateTime $expirationDate)
 * @method Diglin_Ricento_Model_Api_Token setSessionExpirationDate() setSessionExpirationDate(DateTime $expirationDate)
 * @method Diglin_Ricento_Model_Api_Token setCreatedAt() setCreatedAt(DateTime $createdAt)
 */
class Diglin_Ricento_Model_Api_Token extends Mage_Core_Model_Abstract
{
    /**
     * Prefix of model events names
     * @var string
     */
    protected $_eventPrefix = 'api_token';

    /**
     * Parameter name in event
     * In observe method you can use $observer->getEvent()->getObject() in this case
     * @var string
     */
    protected $_eventObject = 'api_token';

    /**
     * Api_Token Constructor
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('diglin_ricento/api_token');
    }
}
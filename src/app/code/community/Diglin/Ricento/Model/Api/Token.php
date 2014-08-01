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
 * @method string   getToken()
 * @method string   getTokenType()
 * @method int      getStoreId()
 * @method DateTime getExpirationDate()
 * @method int      getSessionDuration()
 * @method DateTime getSessionExpirationDate()
 * @method DateTime getCreatedAt()
 * @method Diglin_Ricento_Model_Api_Token setToken(string $token)
 * @method Diglin_Ricento_Model_Api_Token setTokenType(string $tokenType)
 * @method Diglin_Ricento_Model_Api_Token setStoreId(int $storeId)
 * @method Diglin_Ricento_Model_Api_Token setSessionDuration(int $sessionDuration)
 * @method Diglin_Ricento_Model_Api_Token setExpirationDate(DateTime $expirationDate)
 * @method Diglin_Ricento_Model_Api_Token setSessionExpirationDate(DateTime $expirationDate)
 * @method Diglin_Ricento_Model_Api_Token setCreatedAt(DateTime $createdAt)
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
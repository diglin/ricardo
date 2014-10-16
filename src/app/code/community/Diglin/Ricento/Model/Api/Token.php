<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author      Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Api_Token Model
 *
 * @method string   getToken()
 * @method string   getTokenType()
 * @method int      getWebsiteId()
 * @method DateTime getExpirationDate()
 * @method int      getSessionDuration()
 * @method DateTime getSessionExpirationDate()
 * @method DateTime getCreatedAt()
 * @method DateTime getUpdatedAt()
 * @method Diglin_Ricento_Model_Api_Token setToken() setToken(string $token)
 * @method Diglin_Ricento_Model_Api_Token setTokenType(string $tokenType)
 * @method Diglin_Ricento_Model_Api_Token setWebsiteId(int $websiteId)
 * @method Diglin_Ricento_Model_Api_Token setSessionDuration(int $sessionDuration)
 * @method Diglin_Ricento_Model_Api_Token setExpirationDate(DateTime $expirationDate)
 * @method Diglin_Ricento_Model_Api_Token setSessionExpirationDate(DateTime $expirationDate)
 * @method Diglin_Ricento_Model_Api_Token setCreatedAt(DateTime $createdAt)
 * @method Diglin_Ricento_Model_Api_Token setUpdatedAt(DateTime $updateAt)
 */

use \Diglin\Ricardo\Services\Security;

/**
 * Class Diglin_Ricento_Model_Api_Token
 */
class Diglin_Ricento_Model_Api_Token extends Mage_Core_Model_Abstract
{
    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'api_token';

    /**
     * Parameter name in event
     * In observe method you can use $observer->getEvent()->getObject() in this case
     *
     * @var string
     */
    protected $_eventObject = 'api_token';

    /**
     * Api_Token Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('diglin_ricento/api_token');
    }

    /**
     * @param string $tokenType
     * @param int $websiteId
     * @return $this | Diglin_Ricento_Model_Api_Token
     */
    public function loadByWebsiteAndTokenType($tokenType = Security::TOKEN_TYPE_IDENTIFIED, $websiteId = 0)
    {
        $entityId = $this->getResource()->getSpecificTokenType($tokenType, $websiteId);
        if (!empty($entityId)) {
            $this->load($entityId);
        }
        return $this;
    }

    /**
     * @return $this
     */
    protected function _validate()
    {
        $helper = Mage::helper('diglin_ricento');

        // token example:  4a8b285e-417b-431e-bc91-4295793c0d71
        preg_match('/[^.]{8}-[^.]{4}-[^.]{4}-[^.]{4}-[^.]{12}/', $this->getToken(), $matches);
        if (count($matches) != 1) {
            Mage::throwException($helper->__('The format of the token is invalid'));
        }

        return $this;
    }

    /**
     * @return Mage_Core_Model_Abstract
     */
    protected function _beforeSave()
    {
        $this->_validate();
        $this->setUpdatedAt(Mage::getSingleton('core/date')->gmtDate());
        return parent::_beforeSave();
    }
}
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
 * Resource Model of Api_Token
 */
use \Diglin\Ricardo\Services\Security;

class Diglin_Ricento_Model_Resource_Api_Token extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Api_Token Resource Constructor
     * @return void
     */
    protected function _construct()
    {
        $this->_init('diglin_ricento/api_token', 'token_id');
    }

    /**
     * @param string $tokenType
     * @return array
     */
    public function getSpecificTokenType($tokenType = Security::TOKEN_TYPE_IDENTIFIED)
    {
        $readConnection = $this->_getReadAdapter();

        $select = $readConnection
            ->select()
            ->from($this->getTable('diglin_ricento/api_token'))
            ->where('token_type = :token_type');
        $bind = array('token_type' => $tokenType);

        return $readConnection->fetchRow($select, $bind, Zend_Db::FETCH_ASSOC);
    }
}
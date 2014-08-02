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

/**
 * Class Diglin_Ricento_Model_Resource_Api_Token
 */
class Diglin_Ricento_Model_Resource_Api_Token extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Api_Token Resource Constructor
     * @return void
     */
    protected function _construct()
    {
        $this->_init('diglin_ricento/api_token', 'entity_id');
    }

    /**
     * Get the token of a specific type and website
     *
     * @param string $tokenType
     * @param int $websiteId
     * @return array
     */
    public function getSpecificTokenType($tokenType = Security::TOKEN_TYPE_IDENTIFIED, $websiteId = 0)
    {
        $readConnection = $this->_getReadAdapter();

        $select = $readConnection
            ->select(array('entity_id'))
            ->from($this->getTable('diglin_ricento/api_token'))
            ->where('token_type = :token_type AND website_id = :website_id');
        $bind = array('token_type' => $tokenType, 'website_id' => (int) $websiteId);

        return $readConnection->fetchOne($select, $bind);
    }
}
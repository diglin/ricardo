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

        $result = $readConnection->fetchOne($select, $bind);

        if (empty($result) && !empty($websiteId)) {
            $result = $this->getSpecificTokenType($tokenType, 0);
        }

        return $result;
    }
}
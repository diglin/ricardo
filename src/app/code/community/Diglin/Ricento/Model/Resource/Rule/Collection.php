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
class Diglin_Ricento_Model_Resource_Rule_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Rule Collection Resource Constructor
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('diglin_ricento/shipping_payment_rule');
    }
}
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
 * Class Diglin_Ricento_Model_Config_Source_Rules_Payment
 */
class Diglin_Ricento_Model_Config_Source_Rules_Payment extends Diglin_Ricento_Model_Config_Source_Abstract
{
    const TYPE_BANK_TRANSFER = 0;
    const TYPE_CASH = 1;
    const TYPE_CREDIT_CARD = 2;
    const TYPE_OTHER = 3;
    /**
     * @return array
     */
    public function toOptionHash()
    {
        // TODO: implement
        return array(
            self::TYPE_BANK_TRANSFER => 'Bank Transfer',
            self::TYPE_CASH          => 'Cash',
            self::TYPE_CREDIT_CARD   => 'Credit Card (Payu - Ricardo service)',
            self::TYPE_OTHER         => 'Other (fill the description)'
        );
    }

}
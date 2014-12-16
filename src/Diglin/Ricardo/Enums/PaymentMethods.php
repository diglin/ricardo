<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Diglin\Ricardo\Enums;

class PaymentMethods extends AbstractEnums
{
    const TYPE_BANK_TRANSFER = 8192;

    const TYPE_CASH = 1073741824;

    const TYPE_CREDIT_CARD = 262144;

    const TYPE_OTHER = 0;

    /**
     * @return array
     */
    public static function getEnums()
    {
        return array(
            array('label' => 'TYPE_BANK_TRANSFER', 'value' => self::TYPE_BANK_TRANSFER),
            array('label' => 'TYPE_CASH', 'value' => self::TYPE_CASH),
            array('label' => 'TYPE_CREDIT_CARD', 'value' => self::TYPE_CREDIT_CARD),
            array('label' => 'TYPE_OTHER', 'value' => self::TYPE_OTHER)
        );
    }
}

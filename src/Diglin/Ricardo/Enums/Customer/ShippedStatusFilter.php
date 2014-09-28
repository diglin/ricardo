<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

namespace Diglin\Ricardo\Enums\Customer;

use Diglin\Ricardo\Enums\AbstractEnums;

/**
 * Class PaidStatusFilter
 * @package Diglin\Ricardo\Enums\Customer
 */
class PaidStatusFilter extends AbstractEnums
{
    const ANY = 0;

    const PAID = 1;

    const NOTPAID = 2;

    /**
     * @return array
     */
    public static function getEnums()
    {
        return array(
            array('label' => 'ANY', 'value' => self::ANY),
            array('label' => 'PAID', 'value' => self::PAID),
            array('label' => 'NOTPAID', 'value' => self::NOTPAID),
        );
    }
}
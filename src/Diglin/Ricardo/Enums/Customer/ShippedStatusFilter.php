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
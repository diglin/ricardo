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
namespace Diglin\Ricardo\Enums\Article;

use Diglin\Ricardo\Enums\AbstractEnums;

/**
 * Class CloseListStatus
 * @package Diglin\Ricardo\Enums
 */
class CloseListStatus extends AbstractEnums
{
    /* Ricardo API Enum Close Status */

    // Open article
    const OPEN = 0;

    // Closed article
    const CLOSED = 1;

    // Closed by customer
    const CLOSED_BY_CUSTOMER = 2;

    // Archived
    const ARCHIVED = 3;

    /**
     * @return array
     */
    public static function getEnums()
    {
        return array(
            array('label' => 'OPEN', 'value' => self::OPEN),
            array('label' => 'CLOSED', 'value' => self::CLOSED),
            array('label' => 'CLOSED_BY_CUSTOMER', 'value' => self::CLOSED_BY_CUSTOMER),
            array('label' => 'ARCHIVED', 'value' => self::ARCHIVED),
        );
    }
}

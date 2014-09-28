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
namespace Diglin\Ricardo\Enums\System;

use Diglin\Ricardo\Enums\AbstractEnums;

/**
 * Class LanguageCode
 * @package Diglin\Ricardo\Enums\System
 */
class LanguageCode extends AbstractEnums
{
    const NOTDEFINED = 0;

    const SWITZERLANDFRANDDE = 1;

    const SWITZERLANDDE	= 2;

    const SWITZERLANDFR	= 3;

    const DENMARK = 12;

    const GREECE = 14;

    const NORWAY = 20;

    /**
     * @return array
     */
    public static function getEnums()
    {
        return array(
            array('label' => 'NOTDEFINED', 'value' => self::NOTDEFINED),
            array('label' => 'SWITZERLANDFRANDDE', 'value' => self::SWITZERLANDFRANDDE),
            array('label' => 'SWITZERLANDDE', 'value' => self::SWITZERLANDDE),
            array('label' => 'SWITZERLANDFR', 'value' => self::SWITZERLANDFR),
            array('label' => 'DENMARK', 'value' => self::DENMARK),
            array('label' => 'GREECE', 'value' => self::GREECE),
            array('label' => 'NORWAY', 'value' => self::NORWAY),
        );
    }
}

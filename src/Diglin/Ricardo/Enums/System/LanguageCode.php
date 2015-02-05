<?php
/**
 * Diglin GmbH - Switzerland
 *
 * This file is part of a Diglin GmbH module.
 *
 * This Diglin GmbH module is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License version 3 as
 * published by the Free Software Foundation.
 *
 * This script is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * @author      Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
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

    const SWITZERLANDDE    = 2;

    const SWITZERLANDFR    = 3;

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

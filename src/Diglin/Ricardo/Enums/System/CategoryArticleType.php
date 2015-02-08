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
 * Class CategoryArticleType
 * @package Diglin\Ricardo\Enums
 */
class CategoryArticleType extends AbstractEnums
{
    /* Ricardo API Enum category article type */

    // All articles
    const ALL = 0;

    const AUCTION = 1;

    const BUYNOW = 2;

    const CLASSIFIED = 3;

    /**
     * @return array
     */
    public static function getEnums()
    {
        return array(
            array('label' => 'ALL', 'value' => self::ALL),
            array('label' => 'AUCTION', 'value' => self::AUCTION),
            array('label' => 'BUYNOW', 'value' => self::BUYNOW),
            array('label' => 'CLASSIFIED', 'value' => self::CLASSIFIED),
        );
    }
}

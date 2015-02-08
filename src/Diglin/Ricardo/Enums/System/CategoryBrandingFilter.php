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
 * Class CategoryBrandingFilter
 * @package Diglin\Ricardo\Enums
 */
class CategoryBrandingFilter extends AbstractEnums
{
    /* Ricardo API Enum Category Branding Filter */

    const ALLCATEGORIES = 0;

    const WITHOUTBRANDING = 1;

    const ONLYBRANDING = 2;

    /**
     * @return array
     */
    public static function getEnums()
    {
        return array(
            array('label' => 'ALLCATEGORIES', 'value' => self::ALLCATEGORIES),
            array('label' => 'WITHOUTBRANDING', 'value' => self::WITHOUTBRANDING),
            array('label' => 'ONLYBRANDING', 'value' => self::ONLYBRANDING)
        );
    }
}

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
namespace Diglin\Ricardo\Enums;

class PictureExtension extends AbstractEnums
{
    const PNG = 1;
    const JPG = 2;
    const GIF = 3;

    /**
     * @return array
     */
    public static function getEnums()
    {
        return array(
            array('label' => 'PNG', 'value' => self::PNG),
            array('label' => 'JPG', 'value' => self::JPG),
            array('label' => 'GIF', 'value' => self::GIF),
        );
    }
}

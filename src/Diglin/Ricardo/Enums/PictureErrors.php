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

class PictureErrors extends AbstractEnums
{
    const MAXPICTURESCOUNTREACHED = 1;
    const MAXPICTURESIZEREACHED = 2;
    const ERRORRESIZINGPICTURE = 3;
    const PICTUREINDEXDOESNOTEXIST = 4;
    const EMPTYPICTURECONTENT = 5;
    const NOPICTURES = 6;
    const INVALIDPICTUREINDEX = 7;
    const MAXLOGOPICTURESIZEREACHED = 8;
    const ERRORRESIZINGLOGOPICTURE = 9;

    /**
     * @return array
     */
    public static function getEnums()
    {
        return array(
            array('label' => 'MAXPICTURESCOUNTREACHED', 'value' => self::MAXPICTURESCOUNTREACHED),
            array('label' => 'MAXPICTURESIZEREACHED', 'value' => self::MAXPICTURESIZEREACHED),
            array('label' => 'ERRORRESIZINGPICTURE', 'value' => self::ERRORRESIZINGPICTURE),
            array('label' => 'PICTUREINDEXDOESNOTEXIST', 'value' => self::PICTUREINDEXDOESNOTEXIST),
            array('label' => 'EMPTYPICTURECONTENT', 'value' => self::EMPTYPICTURECONTENT),
            array('label' => 'NOPICTURES', 'value' => self::NOPICTURES),
            array('label' => 'INVALIDPICTUREINDEX', 'value' => self::INVALIDPICTUREINDEX),
            array('label' => 'MAXLOGOPICTURESIZEREACHED', 'value' => self::MAXLOGOPICTURESIZEREACHED),
            array('label' => 'ERRORRESIZINGLOGOPICTURE', 'value' => self::ERRORRESIZINGLOGOPICTURE),
        );
    }
}

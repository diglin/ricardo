<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
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
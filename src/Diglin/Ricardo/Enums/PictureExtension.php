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

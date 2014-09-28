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

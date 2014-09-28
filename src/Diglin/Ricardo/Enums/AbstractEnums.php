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

abstract class AbstractEnums
{
    /**
     * @return array
     */
    public static function getEnums()
    {
        return array();
    }

    /**
     * @param $value
     * @return bool
     */
    public static function getLabel($value)
    {
        foreach (static::getEnums() as $enum) {
            if ($enum['value'] == $value) {
                return $enum['label'];
            }
        }
        return false;
    }

    /**
     * @return array
     */
    public static function getValues()
    {
        $values = array();
        foreach (static::getEnums() as $enum) {
            $values[] = $enum['value'];
        }
        return $values;
    }
}
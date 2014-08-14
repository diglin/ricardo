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
}
<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
namespace Diglin\Ricardo\Enums\System;

use Diglin\Ricardo\Enums\AbstractEnums;

/**
 * Class LanguageId
 * @package Diglin\Ricardo\Enums\System
 */
class LanguageId extends AbstractEnums
{
    const NONE = 0;

    const GERMAN = 1;

    const FRENCH = 2;

    /**
     * @return array
     */
    public static function getEnums()
    {
        return array(
            array('label' => 'NONE', 'value' => self::NONE),
            array('label' => 'GERMAN', 'value' => self::GERMAN),
            array('label' => 'FRENCH', 'value' => self::FRENCH),
        );
    }
}

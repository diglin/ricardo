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
namespace Diglin\Ricardo\Enums\System;

use Diglin\Ricardo\Enums\AbstractEnums;

/**
 * Class LanguageId
 * @package Diglin\Ricardo\Enums\System
 */
class LanguageId extends AbstractEnums
{
    const NONE = 0;

    const GERMAN = 2;

    const FRENCH = 3;

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

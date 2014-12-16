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

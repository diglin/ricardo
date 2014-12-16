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
namespace Diglin\Ricardo\Enums\Article;

use Diglin\Ricardo\Enums\AbstractEnums;

/**
 * Class Warranty
 * @package Diglin\Ricardo\Enums\Article
 */
class Warranty extends AbstractEnums
{
    const FOLLOW_CONDITION = 0;

    const NONE = 1;

    /**
     * @return array
     */
    public static function getEnums()
    {
        return array(
            array('label' => 'FOLLOW_CONDITION', 'value' => self::FOLLOW_CONDITION),
            array('label' => 'NONE', 'value' => self::NONE)
        );
    }
}

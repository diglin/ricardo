<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
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
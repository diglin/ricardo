<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    magento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
namespace Diglin\Ricardo\Enums;

class CategoryArticleType extends AbstractEnums
{
    /* Ricardo API Enum category article type */

    // All articles
    const ALL = 0;

    const AUCTION = 1;

    const BUYNOW = 2;

    const CLASSIFIED = 3;

    /**
     * @return array
     */
    public static function getEnums()
    {
        return array(
            array('label' => 'ALL', 'value' => self::ALL),
            array('label' => 'AUCTION', 'value' => self::AUCTION),
            array('label' => 'BUYNOW', 'value' => self::BUYNOW),
            array('label' => 'CLASSIFIED', 'value' => self::CLASSIFIED),
        );
    }
}
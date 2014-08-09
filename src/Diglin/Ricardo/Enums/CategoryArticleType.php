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
    // All articles
    const All = 0;

    const AUCTION = 1;

    const BUYNOW = 2;

    const CLASSIFIED = 3;

    /**
     * @return array
     */
    public static function getEnums()
    {
        return array(
            array('label' => 'All', 'value' => self::ALL),
            array('label' => 'Auction', 'value' => self::AUCTION),
            array('label' => 'Buynow', 'value' => self::BUYNOW),
            array('label' => 'Classified', 'value' => self::CLASSIFIED),
        );
    }
}
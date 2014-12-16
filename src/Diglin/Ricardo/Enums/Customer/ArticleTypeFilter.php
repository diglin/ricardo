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
namespace Diglin\Ricardo\Enums\Customer;

use Diglin\Ricardo\Enums\AbstractEnums;

/**
 * Class ArticleTypeFilter
 * @package Diglin\Ricardo\Enums\Customer
 */
class ArticleTypeFilter extends AbstractEnums
{
    const ALL = 0; // Auctions and fixed Price = 0

    const ALLAUCTIONS = 1; // All auctions = 1

    const AUCTIONSWITHBIDS = 2; // Auctions with bids = 2

    const AUCTIONSWITHOUTBIDS = 3; // Auctions without bids = 3

    const FIXEDPRICE = 4; // Fixed price = 4

    /**
     * @return array
     */
    public static function getEnums()
    {
        return array(
            array('label' => 'ALL', 'value' => self::ALL),
            array('label' => 'ALLAUCTIONS', 'value' => self::ALLAUCTIONS),
            array('label' => 'AUCTIONSWITHBIDS', 'value' => self::AUCTIONSWITHBIDS),
            array('label' => 'AUCTIONSWITHOUTBIDS', 'value' => self::AUCTIONSWITHOUTBIDS),
            array('label' => 'FIXEDPRICE', 'value' => self::FIXEDPRICE),
        );
    }
}

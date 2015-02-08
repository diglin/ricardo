<?php
/**
 * Diglin GmbH - Switzerland
 *
 * This file is part of a Diglin GmbH module.
 *
 * This Diglin GmbH module is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License version 3 as
 * published by the Free Software Foundation.
 *
 * This script is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * @author      Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/gpl-3.0 GNU General Public License, version 3 (GPLv3)
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

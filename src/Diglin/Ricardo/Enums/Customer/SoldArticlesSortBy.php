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
 * Class OpenArticlesSortBy
 * @package Diglin\Ricardo\Enums\Customer
 */
class OpenArticlesSortBy extends AbstractEnums
{
    const SORTBYARTICLEID = 1; // Sort by article Id = 1

    const SORTBYTITLE = 2; // Sort by title = 2

    const SORTBYINTERNALREFERENCE = 3; // Sort by internal reference = 3

    const SORTBYQUANTITY = 4; // Sort by quantity = 4

    const SORTBYVIEWCOUNT = 5; // Sort by number of views = 5

    const SORTBYOBSERVERCOUNT = 6; // Sort by number of observers = 6

    const SORTBYBIDCOUNT = 7; // Sort number of bids = 7

    const SORTBYSTARTPRICE = 8; // Sort by start price = 8

    const SORTBYBUYNOWPRICE = 9; // Sort by buy now price = 9

    const SORTBYCURRENTPRICE = 11; // Sort by current price = 11

    const SORTBYENDDATE = 12; // Sort by end date = 12

    const SORTBYREPOSTCOUNT = 13; // Sort by number of reposts = 13

    const SORTBYSTARTDATE = 14; // Sort by the start date = 14

    const SORTBYCATEGORY = 15; // Sort by the category = 15

    const SORTBYSHIPPINGFEES = 16; // Sort by the shipping fees = 16

    /**
     * @return array
     */
    public static function getEnums()
    {
        return array(
            array('label' => 'SORTBYARTICLEID', 'value' => self::SORTBYARTICLEID),
            array('label' => 'SORTBYTITLE', 'value' => self::SORTBYTITLE),
            array('label' => 'SORTBYINTERNALREFERENCE', 'value' => self::SORTBYINTERNALREFERENCE),
            array('label' => 'SORTBYQUANTITY', 'value' => self::SORTBYQUANTITY),
            array('label' => 'SORTBYVIEWCOUNT', 'value' => self::SORTBYVIEWCOUNT),
            array('label' => 'SORTBYVIEWCOUNT', 'value' => self::SORTBYVIEWCOUNT),
            array('label' => 'SORTBYOBSERVERCOUNT', 'value' => self::SORTBYOBSERVERCOUNT),
            array('label' => 'SORTBYBIDCOUNT', 'value' => self::SORTBYBIDCOUNT),
            array('label' => 'SORTBYSTARTPRICE', 'value' => self::SORTBYSTARTPRICE),
            array('label' => 'SORTBYBUYNOWPRICE', 'value' => self::SORTBYBUYNOWPRICE),
            array('label' => 'SORTBYCURRENTPRICE', 'value' => self::SORTBYCURRENTPRICE),
            array('label' => 'SORTBYENDDATE', 'value' => self::SORTBYENDDATE),
            array('label' => 'SORTBYREPOSTCOUNT', 'value' => self::SORTBYREPOSTCOUNT),
            array('label' => 'SORTBYSTARTDATE', 'value' => self::SORTBYSTARTDATE),
            array('label' => 'SORTBYCATEGORY', 'value' => self::SORTBYCATEGORY),
            array('label' => 'SORTBYSHIPPINGFEES', 'value' => self::SORTBYSHIPPINGFEES),
        );
    }
}
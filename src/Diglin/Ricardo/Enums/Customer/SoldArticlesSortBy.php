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
 * Class SoldArticlesSortBy
 * @package Diglin\Ricardo\Enums\Customer
 */
class SoldArticlesSortBy extends AbstractEnums
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

    const SORTBYBUYERNICK = 10; // Sort by buyer nick = 10

    const SORTBYCURRENTPRICE = 11; // Sort by current price = 11

    const SORTBYENDDATE = 12; // Sort by end date = 12

    const SORTBYREPOSTCOUNT = 13; // Sort by number of reposts = 13

    const SORTBYSTARTDATE = 14; // Sort by the start date = 14

    const SORTBYCATEGORY = 16; // Sort by the category = 16

    const SORTBYSHIPPINGFEES = 17; // Sort by the shipping fees = 17

    const SORTBYCOMPANYNAME = 18; // Sort by the company name = 18

    const SORTBYFIRSTNAME = 19; // Sort by the first name = 19

    const SORTBYADDRESS1 = 20; // Sort by the address1 = 20

    const SORTBYADDRESS2 = 21; // Sort by the address2 = 21

    const SORTBYPOSTBOX = 22; // Sort by the post box = 22

    const SORTBYZIPCODE = 23; // Sort by the zip code = 23

    const SORTBYCITY = 24; // Sort by the city = 24

    const SORTBYCOUNTRY = 25; // Sort by the country = 25

    const ORTBYEMAIL = 26; // Sort by the email = 26

    const SORTBYPHONENUMBER = 27; // Sort by the phone number = 27

    const SORTBYMOBILEPHONE = 28; // Sort by the mobile phone = 28

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
            array('label' => 'SORTBYCOMPANYNAME', 'value' => self::SORTBYCOMPANYNAME),
            array('label' => 'SORTBYFIRSTNAME', 'value' => self::SORTBYFIRSTNAME),
            array('label' => 'SORTBYADDRESS1', 'value' => self::SORTBYADDRESS1),
            array('label' => 'SORTBYADDRESS2', 'value' => self::SORTBYADDRESS2),
            array('label' => 'SORTBYPOSTBOX', 'value' => self::SORTBYPOSTBOX),
            array('label' => 'SORTBYZIPCODE', 'value' => self::SORTBYZIPCODE),
            array('label' => 'SORTBYCITY', 'value' => self::SORTBYCITY),
            array('label' => 'SORTBYCOUNTRY', 'value' => self::SORTBYCOUNTRY),
            array('label' => 'ORTBYEMAIL', 'value' => self::ORTBYEMAIL),
            array('label' => 'SORTBYPHONENUMBER', 'value' => self::SORTBYPHONENUMBER),
            array('label' => 'SORTBYMOBILEPHONE', 'value' => self::SORTBYMOBILEPHONE),
        );
    }
}

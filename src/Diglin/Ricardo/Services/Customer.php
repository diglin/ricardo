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
namespace Diglin\Ricardo\Services;

/**
 * Class Customer
 * @package Diglin\Ricardo\Services
 */
class Customer extends ServiceAbstract
{
    /**
     * @var string
     */
    protected $_service = 'CustomerService';

    /**
     * @var string
     */
    protected $_typeOfToken = self::TOKEN_TYPE_IDENTIFIED;

    /**
     * @return array
     */
    public function getCustomerInformation()
    {
        return array(
            'method' => 'GetCustomerInformation',
            'params' => array('getCustomerInformationParameter' => array())
        );
    }

    /**
     * Array
     * (
     *      [Addresses] => Array
     *      (
     *       [0] => Array
     *           (
     *               [Address1] =>
     *               [Address2] =>
     *               [City] =>
     *               [Country] => 2
     *               [PostalBox] =>
     *               [StreetNumber] =>
     *               [ZipCode] =>
     *           )
     *      )
     *      [CustomerId] => 123456789
     *       [Infos] => Array
     *       (
     *       [ActivityStatus] => 0
     *       [BirthDate] => /Date(329004000000+0200)/
     *       [CompanyName] =>
     *       [CountryId] => 2
     *       [CreationDate] => /Date(1293993568500+0100)/
     *       [Email] =>
     *       [FirstName] =>
     *       [Gender] => m
     *       [IsBuyerOnly] =>
     *       [LanguageId] => 3
     *       [LastLoginDate] => /Date(1410991140000+0200)/
     *       [LastName] =>
     *       [Mobile] =>
     *       [NickName] =>
     *       [PartnerId] =>
     *       [Phone] =>
     *       [RegionId] => 2
     *       [SellerType] => 1
     *   )
     *
     * @param $data
     * @return bool
     */
    public function getCustomerInformationResult($data)
    {
        if (isset($data['GetCustomerInformationResult'])) {
            return $data['GetCustomerInformationResult'];
        }
        return false;
    }
}

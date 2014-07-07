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

abstract class ArticlesTypes
{
    // All articles
    const All = 0;

    // All vehicle articles, that means cars bikes others and utilities type. Could be the same result than "All" type if we search only classified articles
    const Vehicles = 1;

    // Only core articles
    const Core = 2;

    // Accessories articles
    const Accessories = 3;

    // Cars articles
    const Cars = 4;

    // Bikes articles
    const Bikes = 5;

    // Others articles
    const Others = 6;

    // Utilities articles
    const Utilities = 7;

    // CarsAndBikes articles [Accessory, Car, Bike, Utilities, Other]
    const CarsAndBikes = 8;
}
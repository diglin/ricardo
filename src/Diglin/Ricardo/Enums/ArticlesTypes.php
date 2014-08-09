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

class ArticlesTypes extends AbstractEnums
{
    // All articles
    const ALL = 0;

    // All vehicle articles, that means cars bikes others and utilities type. Could be the same result than "All" type if we search only classified articles
    const VEHICLES = 1;

    // Only core articles
    const CORE = 2;

    // Accessories articles
    const ACCESSORIES = 3;

    // Cars articles
    const CARS = 4;

    // Bikes articles
    const BIKES = 5;

    // Others articles
    const OTHERS = 6;

    // Utilities articles
    const UTILITIES = 7;

    // CarsAndBikes articles [Accessory, Car, Bike, Utilities, Other]
    const CARSANDBIKES = 8;

    /**
     * @return array
     */
    public static function getEnums()
    {
        return array(
            array('label' => 'All', 'value' => self::ALL),
            array('label' => 'Vehicles', 'value' => self::VEHICLES),
            array('label' => 'Core', 'value' => self::CORE),
            array('label' => 'Accessories', 'value' => self::ACCESSORIES),
            array('label' => 'Cars', 'value' => self::CARS),
            array('label' => 'Bikes', 'value' => self::BIKES),
            array('label' => 'Others', 'value' => self::OTHERS),
            array('label' => 'Utilities', 'value' => self::UTILITIES),
            array('label' => 'Cars and Bikes', 'value' => self::CARSANDBIKES)
        );
    }
}
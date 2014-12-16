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
namespace Diglin\Ricardo\Enums\Article;

use Diglin\Ricardo\Enums\AbstractEnums;

/**
 * Class ArticlesTypes
 * @package Diglin\Ricardo\Enums\Article
 */
class ArticlesTypes extends AbstractEnums
{
    /* Ricardo API Enum article types */

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
            array('label' => 'ALL', 'value' => self::ALL),
            array('label' => 'VEHICLES', 'value' => self::VEHICLES),
            array('label' => 'CORE', 'value' => self::CORE),
            array('label' => 'ACCESSORIES', 'value' => self::ACCESSORIES),
            array('label' => 'CARS', 'value' => self::CARS),
            array('label' => 'BIKES', 'value' => self::BIKES),
            array('label' => 'OTHERS', 'value' => self::OTHERS),
            array('label' => 'UTILITIES', 'value' => self::UTILITIES),
            array('label' => 'CARS_AND_BIKES', 'value' => self::CARSANDBIKES)
        );
    }
}

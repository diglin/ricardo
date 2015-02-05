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

namespace Diglin\Ricardo\Enums;

/**
 * Class GeneralErrors
 * @package Diglin\Ricardo\Enums
 */
class GeneralErrors extends AbstractEnums
{
    const CLOSEAUCTIONFAILED = 1; //default code when we didn't manage to close an auction

    const CLOSECLASSIFIEDFAILED = 2; //default code when we didn't manage to close a classified

    const DELETEPLANNEDFAILED = 3; //default code when we didn't manage to delete a planned

    const COUNTRYORPARTNERNOTDEFINED = 4; //The country or partner not defined

    const UNKNOWNCATEGORYID = 5; //the given cagetory Id does not exist

    const UNSUPPORTEDLANGUAGEID = 6; //The given languageId is not supported/existing

    const ARTICLENOTFOUND = 7; //Unable to find the article given its ID

    const CUSTOMERNOTFOUND = 8; //customer not found

    const EMPTYIPADDRESS = 9; //The IP address is not defined

    const TECHNICALPROBLEM = 10; //The Technical Exception

    const ARTICLESAMOUNTLIMIT = 11; //The limit for amount of articles per 1 push is 100

    const LISTOFARTICLESEMPTY = 12; //The list of article empty

    const UNKNOWNCATEGORYNAME = 13; //The category name specified is unknown

    /**
     * @return array
     */
    public static function getEnums()
    {
        return array(
            array('label' => 'CLOSEAUCTIONFAILED', 'value' => self::CLOSEAUCTIONFAILED),
            array('label' => 'CLOSECLASSIFIEDFAILED', 'value' => self::CLOSECLASSIFIEDFAILED),
            array('label' => 'DELETEPLANNEDFAILED', 'value' => self::DELETEPLANNEDFAILED),
            array('label' => 'COUNTRYORPARTNERNOTDEFINED', 'value' => self::COUNTRYORPARTNERNOTDEFINED),
            array('label' => 'UNKNOWNCATEGORYID', 'value' => self::UNKNOWNCATEGORYID),
            array('label' => 'UNSUPPORTEDLANGUAGEID', 'value' => self::UNSUPPORTEDLANGUAGEID),
            array('label' => 'ARTICLENOTFOUND', 'value' => self::ARTICLENOTFOUND),
            array('label' => 'CUSTOMERNOTFOUND', 'value' => self::CUSTOMERNOTFOUND),
            array('label' => 'EMPTYIPADDRESS', 'value' => self::EMPTYIPADDRESS),
            array('label' => 'TECHNICALPROBLEM', 'value' => self::TECHNICALPROBLEM),
            array('label' => 'ARTICLESAMOUNTLIMIT', 'value' => self::ARTICLESAMOUNTLIMIT),
            array('label' => 'LISTOFARTICLESEMPTY', 'value' => self::LISTOFARTICLESEMPTY),
            array('label' => 'UNKNOWNCATEGORYNAME', 'value' => self::UNKNOWNCATEGORYNAME),
        );
    }
}

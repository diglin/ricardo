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
namespace Diglin\Ricardo\Core;

use Diglin\Ricardo\Enums\PictureExtension;

/**
 * Class Helper
 * @package Diglin\Ricardo\Core
 */
class Helper
{
    /**
     * Json Date coming from Ricardo API
     * are formatted as the following '/Date(1400795100000+0200)/'
     * We return the Unix timestamp part
     *
     * We deal with UTC+0 for the timestamp
     *
     * @param string $date
     * @return string
     */
    public static function getJsonTimestamp($date)
    {
        if (strpos($date, '/Date') === false) {
            return $date;
        }

        preg_match('/(\d{10})(\d{3})([\+\-]\d{4})/', $date, $matches);
        return (int)$matches[1];
    }

    /**
     * Convert PHP date like date('Y-m-d H:m:i') to .NET Json '/Date(123456789+0200)/'
     *
     * We deal with UTC+0 for the timestamp
     *
     * @param string $unixTimestamp
     * @return string
     */
    public static function getJsonDate($unixTimestamp = null)
    {
        if (is_null($unixTimestamp)) {
            $unixTimestamp = time();
        }

        return '/Date(' . ($unixTimestamp * 1000) . date('O') . ')/';
    }

    /**
     * Get the Picture Extension ID from the picture filename
     *
     * @param string $filename
     * @return bool|int
     */
    public static function getPictureExtension($filename)
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        switch (strtolower($extension)) {
            case 'jpg':
            case 'jpeg':
                $return = PictureExtension::JPG;
                break;
            case 'gif':
                $return = PictureExtension::GIF;
                break;
            case 'png':
                $return = PictureExtension::PNG;
                break;
            default:
                $return = false;
                break;
        }

        return $return;
    }

    /**
     * @return string
     */
    public static function guid()
    {
        if (function_exists('com_create_guid') === true) {
            return strtolower(trim(com_create_guid(), '{}'));
        }

        return strtolower(sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535)));
    }
}

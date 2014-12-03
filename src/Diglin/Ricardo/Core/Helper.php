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
namespace Diglin\Ricardo\Core;

use Diglin\Ricardo\Enums\PictureExtension;

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
}
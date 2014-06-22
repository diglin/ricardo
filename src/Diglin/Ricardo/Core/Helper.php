<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    diglin_ricardo
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
namespace Diglin\Ricardo\Core;

class Helper
{
    /**
     * Json Date coming from Ricardo API
     * are formatted as the following '/Date(1400795100000+0200)/'
     * We convert in a "PHP way" per default 'Y-m-d H:i:s'
     *
     * @param string $date
     * @param string|null $format
     * @return string | DateTime
     */
    public function convertJsonDate($date, $format = 'Y-m-d H:i:s')
    {
        preg_match('/(\d{10})(\d{3})([\+\-]\d{4})/', $date, $matches);

        // Get the timestamp as the TS string
        $timestamp = (int) $matches[1];

        // Get the timezone name by offset
        $timezone = (int) $matches[3];
        $timezone = timezone_name_from_abbr("", $timezone / 100 * 3600, false);
        $timezone = new \DateTimeZone($timezone);

        // Create a new DateTime, set the timestamp and the timezone
        $datetime = new \DateTime();
        $datetime->setTimestamp($timestamp);
        $datetime->setTimezone($timezone);

        if (is_null($format)) {
            return $datetime;
        }

        return $datetime->format($format);
    }
}
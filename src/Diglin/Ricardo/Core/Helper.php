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
    public static function convertJsonDateToPhpDate($date, $format = 'Y-m-d H:i:s')
    {
        preg_match('/(\d{10})(\d{3})([\+\-]\d{4})/', $date, $matches);

        // Get the timestamp as the TS string
        $timestamp = (int) $matches[1];

        return date($format, $timestamp);
    }

    public static function convertPhpDateToJsonDate ($date/*, $timezone = 'Europe/Berlin'*/)
    {
        //$tz = new \DateTimeZone($timezone);

        // We want to calculate the date offset of the current system timezone
        // Get the datetime from today instead of the one provided in parameter
        // cause of a PHP 5.5.7 bug with date greater or equal than year 2038
        // and the daylight savings
        //$datetime = new \DateTime(date('Y-m-d H:m:i'));
        //$datetime->setTimezone($tz);

/*        $offset = $datetime->getOffset();
        $offset = round($offset / 3600 * 100, 0);

        if ($offset > 1200) {
            $offsetSrting = '-0' . ($offset - 1200);
        } else if ($offset < 1000) {
            $offsetSrting = '+0' . $offset;
        } else {
            $offsetSrting = '+' . $offset;
        }
*/
        $datetimeReal = new \DateTime($date);
        //$datetimeReal->setTimezone($tz);

        return '/Date[' . ($datetimeReal->getTimestamp()  * 1000) . date('O') . ']/';
    }
}
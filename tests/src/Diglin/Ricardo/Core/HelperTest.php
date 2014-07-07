<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    diglin_ricardo
 * @package     Diglin_Ricardo
 * @copyright   Copyright [c] 2011-2014 Diglin [http://www.diglin.com]
 */
namespace Diglin\Ricardo\Core;

class HelperTest extends \PHPUnit_Framework_TestCase
{
    public function testConvertJsonDateToPhpDate()
    {
        $defaultTimezone = date_default_timezone_get();
        date_default_timezone_set('Europe/Berlin');

        /* Date is 2079-06-06 22:59:00 GMT+2 */
        $phpDate = Helper::convertJsonDateToPhpDate('/Date(3453314340000+0200)/');

        preg_match('/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/', $phpDate, $matches);
        $this->assertSame('2079', $matches[1], 'Year in the date not found');
        $this->assertSame('06', $matches[2], 'Month in the date not found');
        $this->assertSame('06', $matches[3], 'Day in the date not found');
        $this->assertSame('22', $matches[4], 'Hour in the date not found');
        $this->assertSame('59', $matches[5], 'Minutes in the date not found');
        $this->assertSame('00', $matches[6], 'Second in the date not found');

        date_default_timezone_set($defaultTimezone);
        return $phpDate;
    }

    /**
     * @depends testConvertJsonDateToPhpDate
     * @param $phpDate
     */
    public function testConvertPhpDateToJsonDate($phpDate)
    {
        $defaultTimezone = date_default_timezone_get();
        date_default_timezone_set('Europe/Berlin');

        $jsonDate = Helper::convertPhpDateToJsonDate($phpDate);
        $this->assertSame('/Date(3453314340000+0200)/', $jsonDate, 'Conversion from PHP to .NET Json not successful');

        date_default_timezone_set($defaultTimezone);
    }
}
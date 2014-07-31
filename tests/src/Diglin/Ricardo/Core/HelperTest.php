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
        /* Date is 2079-06-06 22:59:00 GMT+2 */
        $timestamp = Helper::getJsonTimestamp('/Date(3453314340000+0200)/');

        $this->assertEquals('3453314340', $timestamp, 'Get the timestamp not correct. Expected 3453314340');
        return $timestamp;
    }

    /**
     * @depends testConvertJsonDateToPhpDate
     * @param $timestamp
     */
    public function testConvertPhpDateToJsonDate($timestamp)
    {
        $jsonDate = Helper::getJsonDate($timestamp);
        $this->assertSame('/Date(3453314340000+0200)/', $jsonDate, 'Conversion from PHP timestamp to .NET Json not successful');
        $this->assertSame('2079-06-06 22:59:00', date('Y-m-d H:i:s', $timestamp), 'Date is not equal to 2079-06-06 22:59:00');
    }
}
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
namespace Diglin\Ricardo\Enums;

class SecurityErrors extends AbstractEnums
{
    /* Ricardo API Enum Security Errors Code */

    const UNKNOWNPROPERTY = 0;

    const CUSTOMERNOTARTICLEOWNER = 1;

    const CHECKPASSWORDWRONGPASSWORD = 2;

    const CHECKPASSWORDBLACKLISTEDIP = 3;

    const CHECKPASSWORDBANNEDCUSTOMER = 4;

    const CHECKPASSWORDCLOSEDCUSTOMER = 5;

    const LOGADMINFAILED = 6;

    const TOKENERROR = 7;

    const TOKENEXPIRED = 8;

    const ANONYMOUSNOTALLOWED = 9;

    const TEMPORAYCREDENTIALEXPIRED = 10;

    const TEMPORAYCREDENTIALUNVALIDATED = 11;

    const SESSIONEXPIRED = 12;

    const UPDATEPASSWORDPOLICYINVALID = 13;

    const AFTOKENERROR = 14;

    const UNKNOWNPARTNERSHIP = 15;

    const CHECKPASSWORDPENDINGEMAILVALIDATION = 16;

    const VALIDATECUSTOMERADDRESSDISABLED = 17;

    const ACCOUNTBANNED = 18;

    const ACCOUNTCLOSED = 19;

    const METHODNOTALLOWED = 20;

    /* Diglin Ricardo API Own Exception Code */

    const TOKEN_AUTHORIZATION = 100;

    /**
     * @return array
     */
    public static function getEnums()
    {
        return array(
            array('label' => 'UNKNOWNPROPERTY', 'value' => self::UNKNOWNPROPERTY),
            array('label' => 'CUSTOMERNOTARTICLEOWNER', 'value' => self::CUSTOMERNOTARTICLEOWNER),
            array('label' => 'CHECKPASSWORDWRONGPASSWORD', 'value' => self::CHECKPASSWORDWRONGPASSWORD),
            array('label' => 'CHECKPASSWORDBLACKLISTEDIP', 'value' => self::CHECKPASSWORDBLACKLISTEDIP),
            array('label' => 'CHECKPASSWORDBANNEDCUSTOMER', 'value' => self::CHECKPASSWORDBANNEDCUSTOMER),
            array('label' => 'CHECKPASSWORDCLOSEDCUSTOMER', 'value' => self::CHECKPASSWORDCLOSEDCUSTOMER),
            array('label' => 'LOGADMINFAILED', 'value' => self::LOGADMINFAILED),
            array('label' => 'TOKENERROR', 'value' => self::TOKENERROR),
            array('label' => 'TOKENEXPIRED', 'value' => self::TOKENEXPIRED),
            array('label' => 'ANONYMOUSNOTALLOWED', 'value' => self::ANONYMOUSNOTALLOWED),
            array('label' => 'TEMPORAYCREDENTIALEXPIRED', 'value' => self::TEMPORAYCREDENTIALEXPIRED),
            array('label' => 'TEMPORAYCREDENTIALUNVALIDATED', 'value' => self::TEMPORAYCREDENTIALUNVALIDATED),
            array('label' => 'SESSIONEXPIRED', 'value' => self::SESSIONEXPIRED),
            array('label' => 'UPDATEPASSWORDPOLICYINVALID', 'value' => self::UPDATEPASSWORDPOLICYINVALID),
            array('label' => 'AFTOKENERROR', 'value' => self::AFTOKENERROR),
            array('label' => 'UNKNOWNPARTNERSHIP', 'value' => self::UNKNOWNPARTNERSHIP),
            array('label' => 'CHECKPASSWORDPENDINGEMAILVALIDATION', 'value' => self::CHECKPASSWORDPENDINGEMAILVALIDATION),
            array('label' => 'VALIDATECUSTOMERADDRESSDISABLED', 'value' => self::VALIDATECUSTOMERADDRESSDISABLED),
            array('label' => 'ACCOUNTBANNED', 'value' => self::ACCOUNTBANNED),
            array('label' => 'ACCOUNTCLOSED', 'value' => self::ACCOUNTCLOSED),
            array('label' => 'METHODNOTALLOWED', 'value' => self::UNKNOWNPROPERTY),
            array('label' => 'TOKEN_AUTHORIZATION', 'value' => self::TOKEN_AUTHORIZATION),
        );
    }
}

<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
namespace Diglin\Ricardo\Enums;

class CustomerErrors extends AbstractEnums
{
    const NOTALLOWEDTOSELL = 1;
    const BILLINGERROR = 2;
    const GETCUSTOMERERROR = 3;
    const NEEDTOCHANGEPASSWORDERROR = 4;
    const GETACCOUNTPREFERENCESERROR = 5;
    const INVALIDARGUMENTS = 6;
    const EMPTYCOUNTRYID = 7;
    const EMPTYNICKNAME = 8;
    const EMPTYIPADDRESS = 9;
    const EMPTYEMAIL = 10;
    const EMPTYCUSTOMERID = 11;
    const EMPTYGUID = 12;
    const NICKNAMEISNOTUNIQUE = 13;
    const EMAILISNOTUNIQUE = 14;
    const EMPTYFIRSTNAME = 15;
    const TOOLONGFIRSTNAME = 16;
    const EMPTYLASTNAME = 17;
    const TOOLONGLASTNAME = 18;
    const TOOLONGNICKNAME = 19;
    const TOOLONGEMAIL = 20;
    const MUSTBEOVER18 = 21;
    const EMPTYADDRESS1 = 22;
    const TOOLONGADDRESS1 = 23;
    const TOOLONGADDRESS2 = 24;
    const TOOLONGSTREETNR = 25;
    const TOOLONGPOSTALBOX = 26;
    const EMPTYZIPCODE = 27;
    const TOOLONGZIPCODE = 28;
    const EMPTYCITY = 29;
    const TOOLONGCITY = 30;
    const EMPTYCANTONID = 31;
    const EMPTYPHONENUMBER = 32;
    const TOOLONGPHONENUMBER = 33;
    const INVALIDNICKNAME = 34;
    const INVALIDEMAIL = 35;
    const INVALIDSTREETNR = 36;
    const INVALIDZIPCODE = 37;
    const INVALIDPHONENUMBER = 38;
    const EMPTYPASSWORD = 39;
    const COMPANYDETAILSNOTFULL = 40;
    const TOOLONGCOMPANYNAME = 41;
    const TOOLONGCOMPANYVATNUMBER = 42;
    const TOOLONGCOMPANYREGISTRYNUMBER = 43;
    const INVALIDVALIDATIONKEY = 44;
    const UNABLETOVALIDATE = 45;
    const MAXNUMBEROFCALLSTOAUTOMATICALLYVALIDATEUSERINFORMATION = 46;
    const EMPTYSTREETNR = 47;
    const NOTCARDEALER = 48;
    const PASSWORDCOMPLEXITY = 49;
    const UNABLETOUPDATE = 50;
    const EMPTYNEWEMAIL = 51;
    const EMPTYNEWNICK = 52;
    const VALIDATIONKEYEXPIRED = 53;
    const TOOMANYPREMIUMPACKAGES = 54;
    const CARDPAYMENTOPTIONNOTAVAILABLE = 55;
    const CUSTOMERBANNED = 56;
    const BUYERNOTFROMCHORLI = 57;
    const BUYERNORDVNORAC = 58;
    const EMPTYCUSTOMERINFOS = 59;
    const EMPTYMEMBERPREFERENCES = 60;
    const EMPTYADDRESSES = 61;
    const UNABLETOINSERT = 62;
    const CREDITLIMITEXCEEDED = 63;
    const ACTIVATIONCODESTATUSMANUALLYBLOCKED = 64;

    /**
     * @return array
     */
    public static function getEnums()
    {
        return array(
            array('label' => 'NOTALLOWEDTOSELL', 'value' => self::NOTALLOWEDTOSELL),
            array('label' => 'BILLINGERROR', 'value' => self::BILLINGERROR),
            array('label' => 'GETCUSTOMERERROR', 'value' => self::GETCUSTOMERERROR),
            array('label' => 'NEEDTOCHANGEPASSWORDERROR', 'value' => self::NEEDTOCHANGEPASSWORDERROR),
            array('label' => 'GETACCOUNTPREFERENCESERROR', 'value' => self::GETACCOUNTPREFERENCESERROR),
            array('label' => 'INVALIDARGUMENTS', 'value' => self::INVALIDARGUMENTS),
            array('label' => 'EMPTYCOUNTRYID', 'value' => self::EMPTYCOUNTRYID),
            array('label' => 'EMPTYNICKNAME', 'value' => self::EMPTYNICKNAME),
            array('label' => 'EMPTYIPADDRESS', 'value' => self::EMPTYIPADDRESS),
            array('label' => 'EMPTYEMAIL', 'value' => self::EMPTYEMAIL),
            array('label' => 'EMPTYCUSTOMERID', 'value' => self::EMPTYCUSTOMERID),
            array('label' => 'EMPTYGUID', 'value' => self::EMPTYGUID),
            array('label' => 'NICKNAMEISNOTUNIQUE', 'value' => self::NICKNAMEISNOTUNIQUE),
            array('label' => 'EMAILISNOTUNIQUE', 'value' => self::EMAILISNOTUNIQUE),
            array('label' => 'EMPTYFIRSTNAME', 'value' => self::EMPTYFIRSTNAME),
            array('label' => 'TOOLONGFIRSTNAME', 'value' => self::TOOLONGFIRSTNAME),
            array('label' => 'EMPTYLASTNAME', 'value' => self::EMPTYLASTNAME),
            array('label' => 'TOOLONGLASTNAME', 'value' => self::TOOLONGLASTNAME),
            array('label' => 'TOOLONGNICKNAME', 'value' => self::TOOLONGNICKNAME),
            array('label' => 'TOOLONGEMAIL', 'value' => self::TOOLONGEMAIL),
            array('label' => 'MUSTBEOVER18', 'value' => self::MUSTBEOVER18),
            array('label' => 'EMPTYADDRESS1', 'value' => self::EMPTYADDRESS1),
            array('label' => 'TOOLONGADDRESS1', 'value' => self::TOOLONGADDRESS1),
            array('label' => 'TOOLONGADDRESS2', 'value' => self::TOOLONGADDRESS2),
            array('label' => 'TOOLONGSTREETNR', 'value' => self::TOOLONGSTREETNR),
            array('label' => 'TOOLONGPOSTALBOX', 'value' => self::TOOLONGPOSTALBOX),
            array('label' => 'EMPTYZIPCODE', 'value' => self::EMPTYZIPCODE),
            array('label' => 'TOOLONGZIPCODE', 'value' => self::TOOLONGZIPCODE),
            array('label' => 'EMPTYCITY', 'value' => self::EMPTYCITY),
            array('label' => 'TOOLONGCITY', 'value' => self::TOOLONGCITY),
            array('label' => 'EMPTYCANTONID', 'value' => self::EMPTYCANTONID),
            array('label' => 'EMPTYPHONENUMBER', 'value' => self::EMPTYPHONENUMBER),
            array('label' => 'TOOLONGPHONENUMBER', 'value' => self::TOOLONGPHONENUMBER),
            array('label' => 'INVALIDNICKNAME', 'value' => self::INVALIDNICKNAME),
            array('label' => 'INVALIDEMAIL', 'value' => self::INVALIDEMAIL),
            array('label' => 'INVALIDSTREETNR', 'value' => self::INVALIDSTREETNR),
            array('label' => 'INVALIDZIPCODE', 'value' => self::INVALIDZIPCODE),
            array('label' => 'INVALIDPHONENUMBER', 'value' => self::INVALIDPHONENUMBER),
            array('label' => 'EMPTYPASSWORD', 'value' => self::EMPTYPASSWORD),
            array('label' => 'COMPANYDETAILSNOTFULL', 'value' => self::COMPANYDETAILSNOTFULL),
            array('label' => 'TOOLONGCOMPANYNAME', 'value' => self::TOOLONGCOMPANYNAME),
            array('label' => 'TOOLONGCOMPANYVATNUMBER', 'value' => self::TOOLONGCOMPANYVATNUMBER),
            array('label' => 'TOOLONGCOMPANYREGISTRYNUMBER', 'value' => self::TOOLONGCOMPANYREGISTRYNUMBER),
            array('label' => 'INVALIDVALIDATIONKEY', 'value' => self::INVALIDVALIDATIONKEY),
            array('label' => 'UNABLETOVALIDATE', 'value' => self::UNABLETOVALIDATE),
            array('label' => 'MAXNUMBEROFCALLSTOAUTOMATICALLYVALIDATEUSERINFORMATION', 'value' => self::MAXNUMBEROFCALLSTOAUTOMATICALLYVALIDATEUSERINFORMATION),
            array('label' => 'EMPTYSTREETNR', 'value' => self::EMPTYSTREETNR),
            array('label' => 'NOTCARDEALER', 'value' => self::NOTCARDEALER),
            array('label' => 'PASSWORDCOMPLEXITY', 'value' => self::PASSWORDCOMPLEXITY),
            array('label' => 'UNABLETOUPDATE', 'value' => self::UNABLETOUPDATE),
            array('label' => 'EMPTYNEWEMAIL', 'value' => self::EMPTYNEWEMAIL),
            array('label' => 'EMPTYNEWNICK', 'value' => self::EMPTYNEWNICK),
            array('label' => 'VALIDATIONKEYEXPIRED', 'value' => self::VALIDATIONKEYEXPIRED),
            array('label' => 'TOOMANYPREMIUMPACKAGES', 'value' => self::TOOMANYPREMIUMPACKAGES),
            array('label' => 'CARDPAYMENTOPTIONNOTAVAILABLE', 'value' => self::CARDPAYMENTOPTIONNOTAVAILABLE),
            array('label' => 'CUSTOMERBANNED', 'value' => self::CUSTOMERBANNED),
            array('label' => 'BUYERNOTFROMCHORLI', 'value' => self::BUYERNOTFROMCHORLI),
            array('label' => 'BUYERNORDVNORAC', 'value' => self::BUYERNORDVNORAC),
            array('label' => 'EMPTYCUSTOMERINFOS', 'value' => self::EMPTYCUSTOMERINFOS),
            array('label' => 'EMPTYMEMBERPREFERENCES', 'value' => self::EMPTYMEMBERPREFERENCES),
            array('label' => 'EMPTYADDRESSES', 'value' => self::EMPTYADDRESSES),
            array('label' => 'UNABLETOINSERT', 'value' => self::UNABLETOINSERT),
            array('label' => 'CREDITLIMITEXCEEDED', 'value' => self::CREDITLIMITEXCEEDED),
            array('label' => 'ACTIVATIONCODESTATUSMANUALLYBLOCKED', 'value' => self::ACTIVATIONCODESTATUSMANUALLYBLOCKED),
        );
    }
}
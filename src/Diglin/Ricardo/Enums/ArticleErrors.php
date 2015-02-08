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
 * Class ArticleErrors
 * @package Diglin\Ricardo\Enums
 */
class ArticleErrors extends AbstractEnums
{
    const EMPTYCOUNTRYID = 1;
    const EMPTYPARTNERID = 2;
    const EMPTYCATEGORY = 3;
    const EMPTYRELISINFOS = 4;
    const EMPTYCUSTOMER = 5;
    const UNKNOWNPARTNERID = 6;
    const UNKONWNCATEGORYID = 7;
    const INVALIDDURATION = 8;
    const RELISTCOUNTEXCEEDED = 9;
    const STARTPRICETOOHIGH = 10;
    const STARTPRICETOOLOW = 11;
    const INVALIDARTICLETYPEFORCATEGORY = 12;
    const CATEGORYNOTFINAL = 13;
    const CATEGORYDEDICATEDTOUSERS = 14;
    const INVALIDTEMPLATEID = 15;
    const EMPTYTITLE = 16;
    const EMPTYDESCRIPTION = 17;
    const EMPTYSUBTITLE = 18;
    const TOOLONGTITLE = 19;
    const TOOLONGDESCRIPTION = 20;
    const TOOLONGSUBTITLE = 21;
    const EMPTYPAYMENTDESCRIPTION = 22;
    const TOOLONGPAYMENTDESCRIPTION = 23;
    const EMPTYDELIVERYDESCRIPTION = 24;
    const TOOLONGDELIVERYDESCRIPTION = 25;
    const EMPTYWARRANTYDESCRIPTION = 26;
    const TOOLONGWARRANTYDESCRIPTION = 27;
    const INCREMENTNOTDEFINED = 28;
    const INVALIDINCREMENT = 29;
    const QUANTITYTOOLOW = 30;
    const QUANTITYTOOHIGH = 31;
    const NOPAYMENTMETHOD = 32;
    const NODELIVERYCONDITION = 33;
    const DELIVERYCOSTTOOHIGH = 34;
    const MANUALINCREMENTNOTALLOWED = 35;
    const PLANNEDSCHEDULETOOLOW = 36;
    const PLANNEDSCHEDULETOOHIGH = 37;
    const REFERENCETOOLONG = 38;
    const INVALIDQUANTITYFORAUCTIONBUYNOW = 39;
    const BUYNOWLOWERTHANSTARTPRICE = 40;
    const RICARDOPAYNOCARSANDBIKES = 41;
    const RICARDOPAYWRONGPAYMENTCONDITIONS = 42;
    const RICARDOPAYPRICETOOHIGH = 43;
    const RICARDOPAYWALLETBLOCKED = 44;
    const MAXPICTURESIZEREACHED = 45;
    const ERRORRESIZINGPICTURE = 46;
    const MAXPICTURESCOUNTREACHED = 48;
    const INVALIDPROMOTION = 49;
    const INVALIDPROMOTIONCOMBINATION = 50;
    const NOTCLOSED = 51;
    const EMPTYARTICLEINFOS = 52;
    const EMPTYDELIVERY = 53;
    const EMPTYPROMOTIONS = 54;
    const EMPTYDESCRIPTIONS = 55;
    const UPDATEARTICLEERROR = 56;
    const MULTIPLEUPDATENOTALLOWED = 57;
    const UPDATEARTICLECANTRETREIVEARTICLEINFORMATIONS = 58;
    const UPDATEARTICLECANTRETREIVECATEGORY = 59;
    const UPDATEARTICLECANTUPDATEDESCRIPTION = 60;
    const ARTICLEHASBIDS = 61;
    const INVALIDPICTUREEXTENSTION = 62;
    const LANGUAGEIDNOTSET = 63;
    const SETLIVEERROR = 64;
    const ARTICLENOTFOUND = 65;
    const EMPTYPICTURES = 66;
    const UPDATEFAILEDCOULDNOTLOCKARTICLE = 67;
    const UPDATEFAILEDONARTICLETABLE = 68;
    const UPDATEFAILEDFORSTARTDATE = 69;
    const UPDATEFAILEDCOULDNOTDELETEOLDIMAGES = 70;
    const UPDATEFAILEDFORIMAGES = 71;
    const UPDATEFAILEDCOULDNOTDELETEOLDDESCRIPTIONS = 72;
    const UPDATEFAILEDFORDESCRIPTIONS = 73;
    const UPDATEFAILEDCOULDNOTDELETEOLDLIST = 74;
    const UPDATEFAILEDFORLIST = 75;
    const INSERTPREVIEWARTICLEERROR = 76;
    const ONEPRICEMUSTBESET = 77;
    const PICTUREBASEDPROMOTIONNEEDPICTURE = 78;
    const SUBTITLEMUSTBESETFORSUBTITLEPROMOTION = 79;
    const NOPAYMENTCONDITIONSELECTED = 80;
    const QUANTITYCANTINCREASE = 81;
    const STARTPRICECANTINCREASE = 82;
    const BUYNOWPRICECANTINCREASE = 83;
    const PROMOTIONCANTBEREMOVEDONUPDATE = 84;
    const PROMOTIONCANTBEDOWNGRADED = 85;
    const DURATIONCHANGESTOOMANY = 86;
    const CANTPURCHASEWITHBUYNOW = 87;
    const UNABLETOUPDATEQUANTITY = 88;
    const UNABLETOUPDATERELISTCOUNT = 89;
    const BUYNOWPROMOTIONNEEDED = 90;
    const INVALIDMAXRELISTCOUNT = 92;
    const RELISTSOLDOUTAUCTIONNOTALLOWED = 93;
    const SUBTITLEALTERNATIVELANGUAGEISEMPTY = 94;
    const DELIVERYDESCRIPTIONALTERNATIVELANGUAGEISEMPTY = 95;
    const PAYMENTDESCRIPTIONALTERNATIVELANGUAGEISEMPTY = 96;
    const WARRANTYDESCRIPTIONALTERNATIVELANGUAGEISEMPTY = 97;
    const TITLEALTERNATIVELANGUAGEISEMPTY = 98;
    const DESCRIPTIONALTERNATIVELANGUAGEISEMPTY = 99;
    const SUBTITLEMAINLANGUAGEISEMPTY = 100;
    const DELIVERYDESCRIPTIONMAINLANGUAGEISEMPTY = 101;
    const PAYMENTDESCRIPTIONMAINLANGUAGEISEMPTY = 102;
    const WARRANTYDESCRIPTIONMAINLANGUAGEISEMPTY = 103;
    const TITLEMAINLANGUAGEISEMPTY = 104;
    const DESCRIPTIONMAINLANGUAGEISEMPTY = 105;
    const CLOSED = 106;
    const TOOLONGMAINTITLE = 107;
    const TOOLONGMAINDESCRIPTION = 108;
    const TOOLONGMAINSUBTITLE = 109;
    const TOOLONGALTERNATIVETITLE = 110;
    const TOOLONGALTERNATIVEDESCRIPTION = 111;
    const TOOLONGALTERNATIVESUBTITLE = 112;
    const TOOLONGMAINPAYMENTDESCRIPTION = 113;
    const TOOLONGALTERNATIVEPAYMENTDESCRIPTION = 114;
    const TOOLONGMAINWARRANTYDESCRIPTION = 115;
    const TOOLONGALTERNATIVEWARRANTYDESCRIPTION = 116;
    const TOOLONGMAINDELIVERYDESCRIPTION = 117;
    const TOOLONGALTERNATIVEDELIVERYDESCRIPTION = 118;
    const BUYNOWPRICETOOLOW = 119;
    const BUYNOWPROMOTIONNOTALLOWED = 120;
    const CANTUPDATEPICTUREONARTICLEWITHBID = 121;
    const HTMLTAGSNOTALLOWED = 122;
    const WRONGPAYMENTCONDITIONS = 123;
    const EMPTYPAYMENTCONDITIONIDS = 124;
    const NODETAILS = 125;
    const ARTICLEALREADYHASCARDPAYMENT = 126;
    const ERRORINSERTINGCARDPAYMENT = 127;
    const ARTICLEDOESNOTHAVECARDPAYMENT = 128;
    const ERRORREMOVINGCARDPAYMENT = 129;
    const RICARDOPAYNOTACTIVATED = 130;
    const EMPTYPAYMENTMETHODSIDS = 131;
    const EMPTYARTICLEID = 132;
    const INVALIDCARDCOMBINATIONWITHDELIVERYCONDITION = 133;
    const NUMBEROFARTICLELIMITED = 134;
    const PAYMENTMETHODNEEDALTERNATEONE = 135;
    const WRONGPAYMENTMETHODS = 136;
    const CUMULATIVESHIPPINGNOTALLOWED = 137;

    /**
     * @return array
     */
    public static function getEnums()
    {
        return array(
            array('label' => 'EMPTYCOUNTRYID', 'value' => self::EMPTYCOUNTRYID),
            array('label' => 'EMPTYPARTNERID', 'value' => self::EMPTYPARTNERID),
            array('label' => 'EMPTYCATEGORY', 'value' => self::EMPTYCATEGORY),
            array('label' => 'EMPTYRELISINFOS', 'value' => self::EMPTYRELISINFOS),
            array('label' => 'EMPTYCUSTOMER', 'value' => self::EMPTYCUSTOMER),
            array('label' => 'UNKNOWNPARTNERID', 'value' => self::UNKNOWNPARTNERID),
            array('label' => 'UNKONWNCATEGORYID', 'value' => self::UNKONWNCATEGORYID),
            array('label' => 'INVALIDDURATION', 'value' => self::INVALIDDURATION),
            array('label' => 'RELISTCOUNTEXCEEDED', 'value' => self::RELISTCOUNTEXCEEDED),
            array('label' => 'STARTPRICETOOHIGH', 'value' => self::STARTPRICETOOHIGH),
            array('label' => 'STARTPRICETOOLOW', 'value' => self::STARTPRICETOOLOW),
            array('label' => 'INVALIDARTICLETYPEFORCATEGORY', 'value' => self::INVALIDARTICLETYPEFORCATEGORY),
            array('label' => 'CATEGORYNOTFINAL', 'value' => self::CATEGORYNOTFINAL),
            array('label' => 'CATEGORYDEDICATEDTOUSERS', 'value' => self::CATEGORYDEDICATEDTOUSERS),
            array('label' => 'INVALIDTEMPLATEID', 'value' => self::INVALIDTEMPLATEID),
            array('label' => 'EMPTYTITLE', 'value' => self::EMPTYTITLE),
            array('label' => 'EMPTYDESCRIPTION', 'value' => self::EMPTYDESCRIPTION),
            array('label' => 'EMPTYSUBTITLE', 'value' => self::EMPTYSUBTITLE),
            array('label' => 'TOOLONGTITLE', 'value' => self::TOOLONGTITLE),
            array('label' => 'TOOLONGDESCRIPTION', 'value' => self::TOOLONGDESCRIPTION),
            array('label' => 'TOOLONGSUBTITLE', 'value' => self::TOOLONGSUBTITLE),
            array('label' => 'EMPTYPAYMENTDESCRIPTION', 'value' => self::EMPTYPAYMENTDESCRIPTION),
            array('label' => 'TOOLONGPAYMENTDESCRIPTION', 'value' => self::TOOLONGPAYMENTDESCRIPTION),
            array('label' => 'EMPTYDELIVERYDESCRIPTION', 'value' => self::EMPTYDELIVERYDESCRIPTION),
            array('label' => 'TOOLONGDELIVERYDESCRIPTION', 'value' => self::TOOLONGDELIVERYDESCRIPTION),
            array('label' => 'EMPTYWARRANTYDESCRIPTION', 'value' => self::EMPTYWARRANTYDESCRIPTION),
            array('label' => 'TOOLONGWARRANTYDESCRIPTION', 'value' => self::TOOLONGWARRANTYDESCRIPTION),
            array('label' => 'INCREMENTNOTDEFINED', 'value' => self::INCREMENTNOTDEFINED),
            array('label' => 'INVALIDINCREMENT', 'value' => self::INVALIDINCREMENT),
            array('label' => 'QUANTITYTOOLOW', 'value' => self::QUANTITYTOOLOW),
            array('label' => 'QUANTITYTOOHIGH', 'value' => self::QUANTITYTOOHIGH),
            array('label' => 'NOPAYMENTMETHOD', 'value' => self::NOPAYMENTMETHOD),
            array('label' => 'NODELIVERYCONDITION', 'value' => self::NODELIVERYCONDITION),
            array('label' => 'DELIVERYCOSTTOOHIGH', 'value' => self::DELIVERYCOSTTOOHIGH),
            array('label' => 'MANUALINCREMENTNOTALLOWED', 'value' => self::MANUALINCREMENTNOTALLOWED),
            array('label' => 'PLANNEDSCHEDULETOOLOW', 'value' => self::PLANNEDSCHEDULETOOLOW),
            array('label' => 'PLANNEDSCHEDULETOOHIGH', 'value' => self::PLANNEDSCHEDULETOOHIGH),
            array('label' => 'REFERENCETOOLONG', 'value' => self::REFERENCETOOLONG),
            array('label' => 'INVALIDQUANTITYFORAUCTIONBUYNOW', 'value' => self::INVALIDQUANTITYFORAUCTIONBUYNOW),
            array('label' => 'BUYNOWLOWERTHANSTARTPRICE', 'value' => self::BUYNOWLOWERTHANSTARTPRICE),
            array('label' => 'RICARDOPAYNOCARSANDBIKES', 'value' => self::RICARDOPAYNOCARSANDBIKES),
            array('label' => 'RICARDOPAYWRONGPAYMENTCONDITIONS', 'value' => self::RICARDOPAYWRONGPAYMENTCONDITIONS),
            array('label' => 'RICARDOPAYPRICETOOHIGH', 'value' => self::RICARDOPAYPRICETOOHIGH),
            array('label' => 'RICARDOPAYWALLETBLOCKED', 'value' => self::RICARDOPAYWALLETBLOCKED),
            array('label' => 'MAXPICTURESIZEREACHED', 'value' => self::MAXPICTURESIZEREACHED),
            array('label' => 'ERRORRESIZINGPICTURE', 'value' => self::ERRORRESIZINGPICTURE),
            array('label' => 'MAXPICTURESCOUNTREACHED', 'value' => self::MAXPICTURESCOUNTREACHED),
            array('label' => 'INVALIDPROMOTION', 'value' => self::INVALIDPROMOTION),
            array('label' => 'INVALIDPROMOTIONCOMBINATION', 'value' => self::INVALIDPROMOTIONCOMBINATION),
            array('label' => 'NOTCLOSED', 'value' => self::NOTCLOSED),
            array('label' => 'EMPTYARTICLEINFOS', 'value' => self::EMPTYARTICLEINFOS),
            array('label' => 'EMPTYDELIVERY', 'value' => self::EMPTYDELIVERY),
            array('label' => 'EMPTYPROMOTIONS', 'value' => self::EMPTYPROMOTIONS),
            array('label' => 'EMPTYDESCRIPTIONS', 'value' => self::EMPTYDESCRIPTIONS),
            array('label' => 'UPDATEARTICLEERROR', 'value' => self::UPDATEARTICLEERROR),
            array('label' => 'MULTIPLEUPDATENOTALLOWED', 'value' => self::MULTIPLEUPDATENOTALLOWED),
            array('label' => 'UPDATEARTICLECANTRETREIVEARTICLEINFORMATIONS', 'value' => self::UPDATEARTICLECANTRETREIVEARTICLEINFORMATIONS),
            array('label' => 'UPDATEARTICLECANTRETREIVECATEGORY', 'value' => self::UPDATEARTICLECANTRETREIVECATEGORY),
            array('label' => 'UPDATEARTICLECANTUPDATEDESCRIPTION', 'value' => self::UPDATEARTICLECANTUPDATEDESCRIPTION),
            array('label' => 'ARTICLEHASBIDS', 'value' => self::ARTICLEHASBIDS),
            array('label' => 'INVALIDPICTUREEXTENSTION', 'value' => self::INVALIDPICTUREEXTENSTION),
            array('label' => 'LANGUAGEIDNOTSET', 'value' => self::LANGUAGEIDNOTSET),
            array('label' => 'SETLIVEERROR', 'value' => self::SETLIVEERROR),
            array('label' => 'ARTICLENOTFOUND', 'value' => self::ARTICLENOTFOUND),
            array('label' => 'EMPTYPICTURES', 'value' => self::EMPTYPICTURES),
            array('label' => 'UPDATEFAILEDCOULDNOTLOCKARTICLE', 'value' => self::UPDATEFAILEDCOULDNOTLOCKARTICLE),
            array('label' => 'UPDATEFAILEDONARTICLETABLE', 'value' => self::UPDATEFAILEDONARTICLETABLE),
            array('label' => 'UPDATEFAILEDFORSTARTDATE', 'value' => self::UPDATEFAILEDFORSTARTDATE),
            array('label' => 'UPDATEFAILEDCOULDNOTDELETEOLDIMAGES', 'value' => self::UPDATEFAILEDCOULDNOTDELETEOLDIMAGES),
            array('label' => 'UPDATEFAILEDFORIMAGES', 'value' => self::UPDATEFAILEDFORIMAGES),
            array('label' => 'UPDATEFAILEDCOULDNOTDELETEOLDDESCRIPTIONS', 'value' => self::UPDATEFAILEDCOULDNOTDELETEOLDDESCRIPTIONS),
            array('label' => 'UPDATEFAILEDFORDESCRIPTIONS', 'value' => self::UPDATEFAILEDFORDESCRIPTIONS),
            array('label' => 'UPDATEFAILEDCOULDNOTDELETEOLDLIST', 'value' => self::UPDATEFAILEDCOULDNOTDELETEOLDLIST),
            array('label' => 'UPDATEFAILEDFORLIST', 'value' => self::UPDATEFAILEDFORLIST),
            array('label' => 'INSERTPREVIEWARTICLEERROR', 'value' => self::INSERTPREVIEWARTICLEERROR),
            array('label' => 'ONEPRICEMUSTBESET', 'value' => self::ONEPRICEMUSTBESET),
            array('label' => 'PICTUREBASEDPROMOTIONNEEDPICTURE', 'value' => self::PICTUREBASEDPROMOTIONNEEDPICTURE),
            array('label' => 'SUBTITLEMUSTBESETFORSUBTITLEPROMOTION', 'value' => self::SUBTITLEMUSTBESETFORSUBTITLEPROMOTION),
            array('label' => 'NOPAYMENTCONDITIONSELECTED', 'value' => self::NOPAYMENTCONDITIONSELECTED),
            array('label' => 'QUANTITYCANTINCREASE', 'value' => self::QUANTITYCANTINCREASE),
            array('label' => 'STARTPRICECANTINCREASE', 'value' => self::STARTPRICECANTINCREASE),
            array('label' => 'BUYNOWPRICECANTINCREASE', 'value' => self::BUYNOWPRICECANTINCREASE),
            array('label' => 'PROMOTIONCANTBEREMOVEDONUPDATE', 'value' => self::PROMOTIONCANTBEREMOVEDONUPDATE),
            array('label' => 'PROMOTIONCANTBEDOWNGRADED', 'value' => self::PROMOTIONCANTBEDOWNGRADED),
            array('label' => 'DURATIONCHANGESTOOMANY', 'value' => self::DURATIONCHANGESTOOMANY),
            array('label' => 'CANTPURCHASEWITHBUYNOW', 'value' => self::CANTPURCHASEWITHBUYNOW),
            array('label' => 'UNABLETOUPDATEQUANTITY', 'value' => self::UNABLETOUPDATEQUANTITY),
            array('label' => 'UNABLETOUPDATERELISTCOUNT', 'value' => self::UNABLETOUPDATERELISTCOUNT),
            array('label' => 'BUYNOWPROMOTIONNEEDED', 'value' => self::BUYNOWPROMOTIONNEEDED),
            array('label' => 'INVALIDMAXRELISTCOUNT', 'value' => self::INVALIDMAXRELISTCOUNT),
            array('label' => 'RELISTSOLDOUTAUCTIONNOTALLOWED', 'value' => self::RELISTSOLDOUTAUCTIONNOTALLOWED),
            array('label' => 'SUBTITLEALTERNATIVELANGUAGEISEMPTY', 'value' => self::SUBTITLEALTERNATIVELANGUAGEISEMPTY),
            array('label' => 'DELIVERYDESCRIPTIONALTERNATIVELANGUAGEISEMPTY', 'value' => self::DELIVERYDESCRIPTIONALTERNATIVELANGUAGEISEMPTY),
            array('label' => 'PAYMENTDESCRIPTIONALTERNATIVELANGUAGEISEMPTY', 'value' => self::PAYMENTDESCRIPTIONALTERNATIVELANGUAGEISEMPTY),
            array('label' => 'WARRANTYDESCRIPTIONALTERNATIVELANGUAGEISEMPTY', 'value' => self::WARRANTYDESCRIPTIONALTERNATIVELANGUAGEISEMPTY),
            array('label' => 'TITLEALTERNATIVELANGUAGEISEMPTY', 'value' => self::TITLEALTERNATIVELANGUAGEISEMPTY),
            array('label' => 'DESCRIPTIONALTERNATIVELANGUAGEISEMPTY', 'value' => self::DESCRIPTIONALTERNATIVELANGUAGEISEMPTY),
            array('label' => 'SUBTITLEMAINLANGUAGEISEMPTY', 'value' => self::SUBTITLEMAINLANGUAGEISEMPTY),
            array('label' => 'DELIVERYDESCRIPTIONMAINLANGUAGEISEMPTY', 'value' => self::DELIVERYDESCRIPTIONMAINLANGUAGEISEMPTY),
            array('label' => 'PAYMENTDESCRIPTIONMAINLANGUAGEISEMPTY', 'value' => self::PAYMENTDESCRIPTIONMAINLANGUAGEISEMPTY),
            array('label' => 'WARRANTYDESCRIPTIONMAINLANGUAGEISEMPTY', 'value' => self::WARRANTYDESCRIPTIONMAINLANGUAGEISEMPTY),
            array('label' => 'TITLEMAINLANGUAGEISEMPTY', 'value' => self::TITLEMAINLANGUAGEISEMPTY),
            array('label' => 'DESCRIPTIONMAINLANGUAGEISEMPTY', 'value' => self::DESCRIPTIONMAINLANGUAGEISEMPTY),
            array('label' => 'CLOSED', 'value' => self::CLOSED),
            array('label' => 'TOOLONGMAINTITLE', 'value' => self::TOOLONGMAINTITLE),
            array('label' => 'TOOLONGMAINDESCRIPTION', 'value' => self::TOOLONGMAINDESCRIPTION),
            array('label' => 'TOOLONGMAINSUBTITLE', 'value' => self::TOOLONGMAINSUBTITLE),
            array('label' => 'TOOLONGALTERNATIVETITLE', 'value' => self::TOOLONGALTERNATIVETITLE),
            array('label' => 'TOOLONGALTERNATIVEDESCRIPTION', 'value' => self::TOOLONGALTERNATIVEDESCRIPTION),
            array('label' => 'TOOLONGALTERNATIVESUBTITLE', 'value' => self::TOOLONGALTERNATIVESUBTITLE),
            array('label' => 'TOOLONGMAINPAYMENTDESCRIPTION', 'value' => self::TOOLONGMAINPAYMENTDESCRIPTION),
            array('label' => 'TOOLONGALTERNATIVEPAYMENTDESCRIPTION', 'value' => self::TOOLONGALTERNATIVEPAYMENTDESCRIPTION),
            array('label' => 'TOOLONGMAINWARRANTYDESCRIPTION', 'value' => self::TOOLONGMAINWARRANTYDESCRIPTION),
            array('label' => 'TOOLONGALTERNATIVEWARRANTYDESCRIPTION', 'value' => self::TOOLONGALTERNATIVEWARRANTYDESCRIPTION),
            array('label' => 'TOOLONGMAINDELIVERYDESCRIPTION', 'value' => self::TOOLONGMAINDELIVERYDESCRIPTION),
            array('label' => 'TOOLONGALTERNATIVEDELIVERYDESCRIPTION', 'value' => self::TOOLONGALTERNATIVEDELIVERYDESCRIPTION),
            array('label' => 'BUYNOWPRICETOOLOW', 'value' => self::BUYNOWPRICETOOLOW),
            array('label' => 'BUYNOWPROMOTIONNOTALLOWED', 'value' => self::BUYNOWPROMOTIONNOTALLOWED),
            array('label' => 'CANTUPDATEPICTUREONARTICLEWITHBID', 'value' => self::CANTUPDATEPICTUREONARTICLEWITHBID),
            array('label' => 'HTMLTAGSNOTALLOWED', 'value' => self::HTMLTAGSNOTALLOWED),
            array('label' => 'WRONGPAYMENTCONDITIONS', 'value' => self::WRONGPAYMENTCONDITIONS),
            array('label' => 'EMPTYPAYMENTCONDITIONIDS', 'value' => self::EMPTYPAYMENTCONDITIONIDS),
            array('label' => 'NODETAILS', 'value' => self::NODETAILS),
            array('label' => 'ARTICLEALREADYHASCARDPAYMENT', 'value' => self::ARTICLEALREADYHASCARDPAYMENT),
            array('label' => 'ERRORINSERTINGCARDPAYMENT', 'value' => self::ERRORINSERTINGCARDPAYMENT),
            array('label' => 'ARTICLEDOESNOTHAVECARDPAYMENT', 'value' => self::ARTICLEDOESNOTHAVECARDPAYMENT),
            array('label' => 'ERRORREMOVINGCARDPAYMENT', 'value' => self::ERRORREMOVINGCARDPAYMENT),
            array('label' => 'RICARDOPAYNOTACTIVATED', 'value' => self::RICARDOPAYNOTACTIVATED),
            array('label' => 'EMPTYPAYMENTMETHODSIDS', 'value' => self::EMPTYPAYMENTMETHODSIDS),
            array('label' => 'EMPTYARTICLEID', 'value' => self::EMPTYARTICLEID),
            array('label' => 'INVALIDCARDCOMBINATIONWITHDELIVERYCONDITION', 'value' => self::INVALIDCARDCOMBINATIONWITHDELIVERYCONDITION),
            array('label' => 'NUMBEROFARTICLELIMITED', 'value' => self::NUMBEROFARTICLELIMITED),
            array('label' => 'PAYMENTMETHODNEEDALTERNATEONE', 'value' => self::PAYMENTMETHODNEEDALTERNATEONE),
            array('label' => 'WRONGPAYMENTMETHODS', 'value' => self::WRONGPAYMENTMETHODS),
            array('label' => 'CUMULATIVESHIPPINGNOTALLOWED', 'value' => self::CUMULATIVESHIPPINGNOTALLOWED),
        );
    }
}

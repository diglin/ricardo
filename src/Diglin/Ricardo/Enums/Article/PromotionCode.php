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
namespace Diglin\Ricardo\Enums\Article;

use Diglin\Ricardo\Enums\AbstractEnums;

/**
 * Class PromotionCode
 * @package Diglin\Ricardo\Enums\Article
 */
class PromotionCode extends AbstractEnums
{
    // The subtitle
    const SUBTITLE = 1;

    // The buy now
    const BUYNOW = 2;

    // The premium home page
    const PREMIUMHOMEPAGE = 4;

    // The show case
    const SHOWCASE = 8;

    // was named PhotoL in old schema
    const THUMBNAIL = 16;

    // My articles
    const MYARTICLES = 32;

    // The automatic increment
    const AUTOMATICINCREMENT = 64;

    // The premium category bronze
    const PREMIUMCATEGORYBRONZE = 128;

    // The premium category silver
    const PREMIUMCATEGORYSILVER = 256;

    // The premium category gold
    const PREMIUMCATEGORYGOLD = 512;

    // The picture1
    const PICTURE1 = 1024;

    // The picture2
    const PICTURE2 = 2048;

    // The picture3
    const PICTURE3 = 4096;

    // The picture4
    const PICTURE4 = 8192;

    // The picture5
    const PICTURE5 = 16384;

    // The picture6
    const PICTURE6 = 32768;

    // The picture7
    const PICTURE7 = 65536;

    // The picture8
    const PICTURE8 = 131072;

    // The picture9
    const PICTURE9 = 262144;

    // The picture10
    const PICTURE10 = 524288;

    // The login user
    const LOGINUSER = 1048576;

    // The personal home page
    const PERSONALHOMEPAGE = 2097152;

    // Bold
    const BOLD = 4194304;

    // Highlight
    const HIGHLIGHT = 8388608;

    // The premium category
    const PREMIUMCATEGORY = 16777216;

    // The planned article
    const PLANNEDARTICLE = 33554432;

    /**
     * Return the list of current enums
     *
     * @return array
     */
    public static function getEnums()
    {
        return array(
            array('label' => 'SUBTITLE', 'value' => self::SUBTITLE),
            array('label' => 'BUYNOW', 'value' => self::BUYNOW),
            array('label' => 'PREMIUMHOMEPAGE', 'value' => self::PREMIUMHOMEPAGE),
            array('label' => 'SHOWCASE', 'value' => self::SHOWCASE),
            array('label' => 'THUMBNAIL', 'value' => self::THUMBNAIL),
            array('label' => 'MYARTICLES', 'value' => self::MYARTICLES),
            array('label' => 'AUTOMATICINCREMENT', 'value' => self::AUTOMATICINCREMENT),
            array('label' => 'PREMIUMCATEGORYBRONZE', 'value' => self::PREMIUMCATEGORYBRONZE),
            array('label' => 'PREMIUMCATEGORYSILVER', 'value' => self::PREMIUMCATEGORYSILVER),
            array('label' => 'PREMIUMCATEGORYGOLD', 'value' => self::PREMIUMCATEGORYGOLD),
            array('label' => 'PICTURE1', 'value' => self::PICTURE1),
            array('label' => 'PICTURE2', 'value' => self::PICTURE2),
            array('label' => 'PICTURE3', 'value' => self::PICTURE3),
            array('label' => 'PICTURE4', 'value' => self::PICTURE4),
            array('label' => 'PICTURE5', 'value' => self::PICTURE5),
            array('label' => 'PICTURE6', 'value' => self::PICTURE6),
            array('label' => 'PICTURE7', 'value' => self::PICTURE7),
            array('label' => 'PICTURE8', 'value' => self::PICTURE8),
            array('label' => 'PICTURE9', 'value' => self::PICTURE9),
            array('label' => 'PICTURE10', 'value' => self::PICTURE10),
            array('label' => 'LOGINUSER', 'value' => self::LOGINUSER),
            array('label' => 'PERSONALHOMEPAGE', 'value' => self::PERSONALHOMEPAGE),
            array('label' => 'BOLD', 'value' => self::BOLD),
            array('label' => 'HIGHLIGHT', 'value' => self::HIGHLIGHT),
            array('label' => 'PREMIUMCATEGORY', 'value' => self::PREMIUMCATEGORY),
            array('label' => 'PLANNEDARTICLE', 'value' => self::PLANNEDARTICLE),
        );
    }
}
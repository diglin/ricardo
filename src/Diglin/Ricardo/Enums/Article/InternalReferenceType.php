<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
namespace Diglin\Ricardo\Enums\Article;

use Diglin\Ricardo\Enums\AbstractEnums;

/**
 * Class PromotionCode
 * @package Diglin\Ricardo\Enums\Article
 */
class InternalReferenceType extends AbstractEnums
{
    const SELLERSPECIFIC = 1;

    /**
     * @return array
     */
    public static function getEnums()
    {
        return array(
            array('label' => 'SELLERSPECIFIC', 'value' => self::SELLERSPECIFIC)
        );
    }
}
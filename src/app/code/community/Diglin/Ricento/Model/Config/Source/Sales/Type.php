<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

/**
 * Class Diglin_Ricento_Model_Config_Source_Sales_Type
 */
class Diglin_Ricento_Model_Config_Source_Sales_Type extends Diglin_Ricento_Model_Config_Source_Abstract
{
    const AUCTION = 'auction';
    const FIX_PRICE = 'fixprice';

    /**
     * @return array
     */
    public function toOptionHash()
    {
        // TODO: implement
        return array(
            ''         => '- Please Select -',
            self::AUCTION  => 'Auction',
            self::FIX_PRICE => 'Fix price'
        );
    }
}
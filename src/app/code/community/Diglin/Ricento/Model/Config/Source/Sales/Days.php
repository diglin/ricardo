<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
class Diglin_Ricento_Model_Config_Source_Sales_Days extends Diglin_Ricento_Model_Config_Source_Abstract
{
    public function toOptionHash()
    {
        // @todo extract from Ricardo API ?
        return array(
            2 => 2,
            4 => 4,
            6 => 6,
            8 => 8,
            10 => 10
        );
    }
}
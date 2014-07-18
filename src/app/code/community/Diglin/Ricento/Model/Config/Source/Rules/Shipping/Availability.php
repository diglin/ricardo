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
 * Class Diglin_Ricento_Model_Config_Source_Rules_Shipping_Availability
 */
class Diglin_Ricento_Model_Config_Source_Rules_Shipping_Availability extends Diglin_Ricento_Model_Config_Source_Abstract
{
    /**
     * @return array
     */
    public function toOptionHash()
    {
        // TODO: implement
        return array(
            '' => '- Select Availability -',
            1  => '1 business day',
            2  => '2 business days',
            3  => '3 business days',
            4  => 'None (description)',
        );
    }
}
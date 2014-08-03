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
 * Class Diglin_Ricento_Model_Config_Source_Sales_Promotion
 */
class Diglin_Ricento_Model_Config_Source_Sales_Promotion extends Diglin_Ricento_Model_Config_Source_Abstract
{
    /**
     * Return options as value => label array
     *
     * @return array
     */
    public function toOptionHash()
    {
        //$promotions = Mage::getSingleton('diglin_ricento/api_services_system')->getPromotions();

        //TODO implement
        return array(
            '' => 'None',
            1  => 'Small CHF 2.00',
            2  => 'Medium CHF 5.00',
            3  => 'Big CHF 12.00'
        );
    }

}
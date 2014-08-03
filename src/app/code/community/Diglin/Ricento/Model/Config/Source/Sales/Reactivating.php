<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
class Diglin_Ricento_Model_Config_Source_Sales_Reactivating extends Diglin_Ricento_Model_Config_Source_Abstract
{
    /**
     * Return options as value => label array
     *
     * @return array
     */
    public function toOptionHash()
    {
        $partnerConfiguration = Mage::getSingleton('diglin_ricento/api_services_system')->getPartnerConfigurations();

        if (isset($partnerConfiguration['MaxRelistCount'])) {
            return range(0, $partnerConfiguration['MaxRelistCount']);
        }

        //@todo how to handle 'until sold'? null => Mage::helper('diglin_ricento')->__('Until sold')
        return array(1);
    }

}
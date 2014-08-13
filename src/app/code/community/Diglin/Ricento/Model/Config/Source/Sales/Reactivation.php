<?php

/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
class Diglin_Ricento_Model_Config_Source_Sales_Reactivation extends Diglin_Ricento_Model_Config_Source_Abstract
{

    /**
     * @var array
     */
    protected $_partnerConfiguration = array();

    /**
     * Return options as value => label array
     *
     * @return array
     */
    public function toOptionHash()
    {
        if (empty($this->_partnerConfiguration)) {
            $partnerConfiguration = Mage::getSingleton('diglin_ricento/api_services_system')->getPartnerConfigurations();

            if (isset($partnerConfiguration['MaxRelistCount'])) {
                $this->_partnerConfiguration = range(0, $partnerConfiguration['MaxRelistCount']);
            }
            if (empty($this->_partnerConfiguration)) {
                $this->_partnerConfiguration  = array(1);
            }

            $this->_partnerConfiguration = array_merge($this->_partnerConfiguration, array('99999' => Mage::helper('diglin_ricento')->__('Until sold')));
        }

        return $this->_partnerConfiguration;
    }

}
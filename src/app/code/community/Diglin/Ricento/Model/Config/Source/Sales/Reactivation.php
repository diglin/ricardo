<?php

/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Diglin_Ricento_Model_Config_Source_Sales_Reactivation extends Diglin_Ricento_Model_Config_Source_Abstract
{
    const SOLDOUT = 99999;

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
            $partnerConfiguration = (array) Mage::getSingleton('diglin_ricento/api_services_system')->getPartnerConfigurations();

            if (isset($partnerConfiguration['MaxRelistCount'])) {
                $this->_partnerConfiguration = range(0, $partnerConfiguration['MaxRelistCount']);
            }
            if (empty($this->_partnerConfiguration)) {
                $this->_partnerConfiguration  = array(1);
            }
        }

        return $this->_partnerConfiguration;
    }
}
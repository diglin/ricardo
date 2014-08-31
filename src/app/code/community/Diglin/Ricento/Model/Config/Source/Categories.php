<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
class Diglin_Ricento_Model_Config_Source_Categories extends Diglin_Ricento_Model_Config_Source_Abstract
{
    /**
     * @var array
     */
    protected $_categories = array();

    /**
     * @return array
     */
    public function toOptionHash()
    {
        if (empty($this->_categories)) {
            $this->_categories = (array) Mage::getSingleton('diglin_ricento/api_services_system')->getCategories();
        }

        return $this->_categories;
    }
}
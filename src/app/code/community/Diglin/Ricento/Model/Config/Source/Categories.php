<?php
/**
 * ricardo.ch AG - Switzerland
 *
 * @author      Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2014 ricardo.ch AG (http://www.ricardo.ch)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Class Diglin_Ricento_Model_Config_Source_Categories
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
        if (empty($this->_categories) && Mage::helper('diglin_ricento')->isConfigured()) {
            $this->_categories = (array) Mage::getSingleton('diglin_ricento/api_services_system')->getCategories();
        }

        return $this->_categories;
    }
}
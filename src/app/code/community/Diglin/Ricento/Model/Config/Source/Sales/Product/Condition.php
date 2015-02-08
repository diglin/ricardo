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
 * Class Diglin_Ricento_Model_Config_Source_Sales_Product_Condition
 */
class Diglin_Ricento_Model_Config_Source_Sales_Product_Condition extends Diglin_Ricento_Model_Config_Source_Abstract
{
    /**
     * @var array
     */
    protected $_articleConditions = array();

    /**
     * Return options as value => label array
     *
     * @return array
     */
    public function toOptionHash()
    {
        if (empty($this->_articleConditions) && Mage::helper('diglin_ricento')->isConfigured()) {
            $articleConditions = (array)Mage::getSingleton('diglin_ricento/api_services_system')->getArticleConditions();

            if (!empty($articleConditions)) {
                $this->_articleConditions = array(null => Mage::helper('diglin_ricento')->__('-- Please Select --'));
            };

            foreach ($articleConditions as $condition) {
                $this->_articleConditions[$condition['ArticleConditionId']] = $condition['ArticleConditionText'];
            }
        }

        return $this->_articleConditions;
    }
}
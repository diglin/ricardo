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
 * Class Diglin_Ricento_Model_Config_Source_Sales_Product_Condition
 */
class Diglin_Ricento_Model_Config_Source_Sales_Product_Condition extends Diglin_Ricento_Model_Config_Source_Abstract
{
    /**
     * Return options as value => label array
     *
     * @return array
     */
    public function toOptionHash()
    {
        $articleConditions = Mage::getSingleton('diglin_ricento/api_services_system')->getArticleConditions();

        $result = array(null => MAge::helper('diglin_ricento')->__('-- Please Select --'));
        foreach ($articleConditions as $condition) {
            $result[$condition['ArticleConditionId']] = $condition['ArticleConditionText'];
        }

        return $result;
    }
}
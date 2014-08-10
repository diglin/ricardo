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
 * Class Diglin_Ricento_Model_Config_Source_Sales_Template
 */
class Diglin_Ricento_Model_Config_Source_Sales_Template extends Diglin_Ricento_Model_Config_Source_Abstract
{
    /**
     * @return array
     */
    public function toOptionHash()
    {
        $templates = Mage::getSingleton('diglin_ricento/api_services_selleraccount')->getTemplates();

        $result = array();
        foreach ($templates as $template) {
            $result[$template['TemplateId']] = $template['TemplateName'];
        }

        if (empty($result)) {
            $result[''] = Mage::helper('diglin_ricento')->__('No Template found');
        }

        return $result;
    }
}
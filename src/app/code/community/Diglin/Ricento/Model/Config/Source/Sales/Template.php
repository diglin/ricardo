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
 * Class Diglin_Ricento_Model_Config_Source_Sales_Template
 */
class Diglin_Ricento_Model_Config_Source_Sales_Template extends Diglin_Ricento_Model_Config_Source_Abstract
{
    /**
     * @var array
     */
    protected $_templates = array();

    /**
     * @return array
     */
    public function toOptionHash()
    {
        if (empty($this->_templates) && Mage::helper('diglin_ricento')->isConfigured()) {
            $templates = (array) Mage::getSingleton('diglin_ricento/api_services_selleraccount')->getTemplates();

            $this->_templates = array('-1' => Mage::helper('diglin_ricento')->__('None'));
            foreach ($templates as $template) {
                $this->_templates[$template['TemplateId']] = $template['TemplateName'];
            }
        }

        return $this->_templates;
    }
}
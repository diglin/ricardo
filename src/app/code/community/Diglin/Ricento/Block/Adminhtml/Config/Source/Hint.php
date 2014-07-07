<?php
/**
 * Diglin
 *
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

class Diglin_Ricento_Block_Adminhtml_Config_Source_Hint
    extends Mage_Adminhtml_Block_Abstract
    implements Varien_Data_Form_Element_Renderer_Interface
{
    /**
     * Render fieldset html
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $buttonSignUp = $this->getLayout()->createBlock('adminhtml/widget_button')->setData(array(
            'label'     => $this->__('Sign Up to Ricardo API'),
            'onclick'   => "window.open('" . Mage::helper('diglin_ricento')->getRicardoSignupApiUrl() . "', '_self');",
            'class'     => 'add',
            'type'      => 'button',
            'id'        => 'ricardo-account',
        ))
        ->toHtml();

        $buttonDashboard  = $this->getLayout()->createBlock('adminhtml/widget_button')->setData(array(
            'label'     => $this->__('Ricardo Assistant'),
            'onclick'   => "window.open('". Mage::helper('diglin_ricento')->getRicardoAssistantUrl() ."', '_blank');",
            'class'     => 'go',
            'type'      => 'button',
            'id'        => 'ricardo-assistant',
        ))
            ->toHtml();

        return '<p>' . $buttonSignUp . '&nbsp;' . $buttonDashboard . ' - <strong>Diglin_Ricento Version: '. Mage::getConfig()->getModuleConfig('Diglin_Ricento')->version .' - Powered by <a href="http://www.diglin.com/?utm_source=magento&utm_medium=extension&utm_campaign=ricento">Diglin GmbH</a></strong></p>';
    }
}

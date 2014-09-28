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

class Diglin_Ricento_Block_Adminhtml_Config_Source_Heading
    extends Mage_Adminhtml_Block_System_Config_Form_Field_Heading
{
    /**
     * Render element html
     *
     * Diglin: for compatibility with older Magento version < 1.5 but also to support depends tag in system.xml
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        return sprintf('<tr class="system-fieldset-sub-head" id="row_%s"><td colspan="5"><h4>%s</h4><input type="hidden" id="%s" name="depends-input" title="depends-input"/></td></tr>',
            $element->getHtmlId(), $element->getLabel(), $element->getHtmlId()
        );
    }
}

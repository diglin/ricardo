<?php
/*
 * ricardo.ch AG - Switzerland
 *
 * @author Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2014 ricardo.ch AG (http://www.ricardo.ch)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Class Diglin_Ricento_Block_Adminhtml_Widget_Grid_Column_Renderer_Button
 */
class Diglin_Ricento_Block_Adminhtml_Widget_Grid_Column_Renderer_Button extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action
{
    protected function _toLinkHtml($action, Varien_Object $row)
    {
        $actionAttributes = new Varien_Object();

        $actionCaption = '';
        $this->_transformActionData($action, $actionCaption, $row);

        $action['onclick'] = "window.location='{$action['href']}'";
        unset($action['href']);

        if(isset($action['confirm'])) {
            $action['onclick'] = 'window.confirm(\''
                . addslashes($this->escapeHtml($action['confirm']))
                . '\') && (' . $action['onclick'] . ')';
            unset($action['confirm']);
        }

        $actionAttributes->setData($action);
        return '<button ' . $actionAttributes->serialize() . '><span>' . $actionCaption . '</span></button>';
    }
}
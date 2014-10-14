<?php
/*
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Class Diglin_Ricento_Block_Adminhtml_Widget_Grid_Column_Renderer_Button
 */
class Diglin_Ricento_Block_Adminhtml_Widget_Grid_Column_Renderer_Button extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action
{
    protected function _toLinkHtml($action, Varien_Object $row)
    {
        return preg_replace(
            '~^<a(.*?)>(.*)</a>$~', '<button$1><span>$2</span></button>',
            parent::_toLinkHtml($action, $row));
    }

}
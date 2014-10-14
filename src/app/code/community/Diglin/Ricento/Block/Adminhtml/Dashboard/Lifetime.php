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
 * Class Diglin_Ricento_Block_Adminhtml_Dashboard_Lifetime
 */
class Diglin_Ricento_Block_Adminhtml_Dashboard_Lifetime extends Mage_Adminhtml_Block_Template
{
    public function getPriceHtml()
    {
        return Mage::helper('core')->formatCurrency(0); //TODO calculate
    }
}
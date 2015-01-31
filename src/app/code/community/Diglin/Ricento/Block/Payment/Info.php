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

class Diglin_Ricento_Block_Payment_Info extends Mage_Payment_Block_Info
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('ricento/payment/info/default.phtml');
    }

    /**
     * Render as PDF
     * @return string
     */
    public function toPdf()
    {
        $this->setTemplate('ricento/payment/info/pdf/default.phtml');
        return $this->toHtml();
    }
}
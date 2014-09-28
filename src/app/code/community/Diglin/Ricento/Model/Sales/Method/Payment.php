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

class Diglin_Ricento_Model_Sales_Method_Payment extends Mage_Payment_Model_Method_Abstract
{
    /**
     * unique internal payment method identifier
     *
     * @var string [a-z0-9_]
     */
    protected $_code = 'ricento';

    /**
     * payment form block
     *
     * @var string MODULE/BLOCKNAME
     */
    protected $_formBlockType = 'debit/form';

    /**
     * payment info block
     *
     * @var string MODULE/BLOCKNAME
     */
    protected $_infoBlockType = 'debit/info';

    /**
     * @var bool Allow capturing for this payment method
     */
    protected $_canCapture = true;

    /**
     * @var bool Allow partial capturing for this payment method
     */
    protected $_canCapturePartial = true;

    /**
     * Assigns data to the payment info instance
     *
     * @param  Varien_Object|array $data Payment Data from checkout
     * @return Diglin_Ricento_Model_Sales_Method_Payment Self.
     */
    public function assignData($data)
    {
        $info = $this->getInfoInstance();
    }

    /**
     * Check whether payment method can be used
     * Not allowed for frontend
     * @todo not allowed for backend too but check that we get the data while sync
     *
     * @param Mage_Sales_Model_Quote|null $quote
     *
     * @return bool
     */
    public function isAvailable($quote = null)
    {
        if (!Mage::app()->getStore()->isAdmin()) {
            return false;
        }

        return parent::isAvailable($quote);
    }
}
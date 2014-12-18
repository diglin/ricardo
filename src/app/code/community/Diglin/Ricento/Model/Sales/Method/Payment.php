<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author      Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Class Diglin_Ricento_Model_Sales_Method_Payment
 */
class Diglin_Ricento_Model_Sales_Method_Payment extends Mage_Payment_Model_Method_Abstract
{
    const CHECK_IS_RICARDO_ORDER    = 256;
    const PAYMENT_CODE              = 'ricento';

    /**
     * unique internal payment method identifier
     *
     * @var string [a-z0-9_]
     */
    protected $_code = self::PAYMENT_CODE;

    /**
     * payment info block
     *
     * @var string MODULE/BLOCKNAME
     */
    protected $_infoBlockType = 'diglin_ricento/payment_info';

    protected $_canManageRecurringProfiles  = false;
    protected $_canUseCheckout              = false;
    protected $_canCapture                  = true;
    protected $_canCapturePartial           = true;

    /**
     * Check whether payment method can be used
     * Not allowed for frontend
     *
     * @param Mage_Sales_Model_Quote|null $quote
     *
     * @return bool
     */
    public function isAvailable($quote = null)
    {
        if (!Mage::helper('diglin_ricento')->isEnabledConfigured()) {
            return false;
        }

        if ($quote && parent::isAvailable($quote) && $this->isApplicableToQuote($quote, self::CHECK_IS_RICARDO_ORDER)) {
            return true;
        }

        return false;
    }

    /**
     * Check whether payment method is applicable to quote
     * Purposed to allow use in controllers some logic that was implemented in blocks only before
     *
     * @param Mage_Sales_Model_Quote $quote
     * @param int|null $checksBitMask
     * @return bool
     */
    public function isApplicableToQuote($quote, $checksBitMask)
    {
        if (!Mage::helper('diglin_ricento')->isEnabledConfigured()) {
            return parent::isApplicableToQuote($quote, $checksBitMask);
        }

        if ($checksBitMask & self::CHECK_IS_RICARDO_ORDER) {
            if (!$quote->getIsRicardo()) {
                return false;
            }
        }

        return parent::isApplicableToQuote($quote, $checksBitMask);
    }
}
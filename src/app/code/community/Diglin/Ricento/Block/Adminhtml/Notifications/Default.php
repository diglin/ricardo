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
 * Class Diglin_Ricento_Block_Adminhtml_Notifications
 */
class Diglin_Ricento_Block_Adminhtml_Notifications_Default extends Mage_Adminhtml_Block_Template
{
    /**
     * Disable the block caching for this block
     */
    protected function _construct()
    {
        $this->addData(array('cache_lifetime'=> null));
    }

    /**
     * @param null|string|bool|int|Mage_Core_Model_Website $website
     * @return bool
     */
    public function isEnabled($website = 0)
    {
        return (bool) Mage::helper('diglin_ricento')->isEnabled($website);
    }

    /**
     * @param null|string|bool|int|Mage_Core_Model_Website $website
     * @return bool
     */
    public function isApiConfigured($website = 0)
    {
        return (bool) Mage::helper('diglin_ricento')->isConfigured($website);
    }

    /**
     * ACL validation before html generation
     *
     * @return string Notification content
     */
    protected function _toHtml()
    {
        try {
            if (Mage::getSingleton('admin/session')->isAllowed('system/ricento')) {
                return parent::_toHtml();
            }
        } catch (\Diglin\Ricardo\Exceptions\CurlException $e) {
            // @todo Curl Error can happens here - the addError session method is maybe too late to be defined here
            if ($this->isEnabled() && $this->isApiConfigured()) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError($this->__('Error while trying to connect to the ricardo.ch API. Please, check your log files.'));
            }
        }

        return '';
    }
}

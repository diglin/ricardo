<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
abstract class Diglin_Ricento_Controller_Adminhtml_Action extends Mage_Adminhtml_Controller_Action
{
    protected function _construct()
    {
        // Important to get appropriate translation from this module
        $this->setUsedModuleName('Diglin_Ricento');
    }

    /**
     * @return bool|Diglin_Ricento_Model_Products_Listing
     */
    protected function _initListing()
    {
        $registeredListing = $this->_getListing();
        if ($registeredListing) {
            return $registeredListing;
        }
        $id = (int) $this->getRequest()->getParam('id');
        if (!$id) {
            $this->_getSession()->addError('Product Listing not found.');
            return false;
        }

        $productsListing = Mage::getModel('diglin_ricento/products_listing')->load($id);
        Mage::register('products_listing', $productsListing);

        return $this->_getListing();
    }

    /**
     * @return Diglin_Ricento_Model_Products_Listing
     */
    protected function _getListing()
    {
        return Mage::registry('products_listing');
    }

    protected function isApiReady()
    {
        $helper = Mage::helper('diglin_ricento');
        $helperApi = Mage::helper('diglin_ricento/api');
        $websiteId = $this->_initListing()->getWebsiteId();

        return $helper->isEnabled($websiteId) && $helper->isConfigured($websiteId) && !$helperApi->isApiTokenCredentialGoingToExpire($websiteId);
    }

    /**
     * @return boolean
     */
    protected function _savingAllowed()
    {
        return true;
    }
}
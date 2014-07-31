<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

/**
 * Class Diglin_Ricento_Controller_Adminhtml_Products_Listing
 *
 * Abstract controller for controllers that save listing configuration
 */
abstract class Diglin_Ricento_Controller_Adminhtml_Products_Listing extends Mage_Adminhtml_Controller_Action
{
    /**
     * @var Varien_Data_Collection
     */
    protected $_salesOptionsCollection;

    /**
     * @var Varien_Data_Collection
     */
    protected $_shippingPaymentCollection;

    protected function _construct()
    {
        // important to get appropriate translation from this module
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

        return $productsListing;
    }

    /**
     * @return Diglin_Ricento_Model_Products_Listing
     */
    protected function _getListing()
    {
        return Mage::registry('products_listing');
    }

    /**
     * Filtering posted data (sales options and rules form). Converting localized data if needed
     *
     * @param array
     * @return array
     */
    protected function _filterPostData($data)
    {
        if (!isset($data['product_listing'])) {
            $data['product_listing'] = array();
        }

        if (!isset($data['sales_options'])) {
            $data['sales_options'] = array();
        }
        $data['sales_options'] = $this->_filterDates($data['sales_options'], array('schedule_date_start', 'schedule_period_end_date'));
        if (empty($data['sales_options']['entity_id'])) {
            unset($data['sales_options']['entity_id']);
        }
        if (!empty($data['sales_options']['ricardo_category_use_mapping'])) {
            $data['sales_options']['ricardo_category'] = 0;
        }
        if (!empty($data['sales_options']['schedule_cycle_multiple_products_random'])) {
            $data['sales_options']['schedule_cycle_multiple_products'] = null;
        }
        if (!empty($data['sales_options']['stock_management_use_inventory'])) {
            $data['sales_options']['stock_management'] = -1;
        }
        if (!empty($data['sales_options']['schedule_date_start_immediately'])) {
            $data['sales_options']['schedule_date_start'] = date(Varien_Date::DATE_PHP_FORMAT);
        }
        if (!empty($data['sales_options']['schedule_period_use_end_date'])) {
            try {
                $interval = date_diff(
                    new DateTime($data['sales_options']['schedule_date_start']),
                    new DateTime($data['sales_options']['schedule_period_end_date']));
                $data['sales_options']['schedule_period_days'] = ($interval->invert ? -1 : 1) * $interval->days;
            } catch (Exception $e) {
                // Invalid date => period of 0 will fail the validation
                $data['sales_options']['schedule_period_days'] = 0;
            }
        }
        if (empty($data['sales_options']['product_condition_use_attribute'])) {
            $data['sales_options']['product_condition_source_attribute_code'] = null;
        } else {
            $data['sales_options']['product_condition'] = null;
        }

        if (!isset($data['rules'])) {
            $data['rules'] = array();
        }
        if (empty($data['rules']['rule_id'])) {
            unset($data['rules']['rule_id']);
        }
        if (!empty($data['rules']['payment_methods'])) {
            $data['rules']['payment_methods'] = implode(',', $data['rules']['payment_methods']);
        }
        if (!empty($data['rules']['payment_description'])) {
            $data['rules']['payment_description'] = Mage::helper('core')->escapeHtml($data['rules']['payment_description']);
        }
        if (!empty($data['rules']['shipping_description'])) {
            $data['rules']['shipping_description'] = Mage::helper('core')->escapeHtml($data['rules']['shipping_description']);
        }
        if (!empty($data['rules']['free_shipping'])) {
            $data['rules']['shipping_price'] = 0;
        }

        return $data;
    }

    /**
     * Validate post data
     *
     * @param array $data
     * @return bool     Return FALSE if some item is invalid
     */
    protected function _validatePostData($data)
    {
        /* @var $rulesValidator Diglin_Ricento_Model_Rule_Validate */
        $rulesValidator = Mage::getModel('diglin_ricento/rule_validate');
        $methods = array(
            'payment' => array_filter(explode(',', $data['rules']['payment_methods']), 'strlen'),
            'shipping' => $data['rules']['shipping_method']
        );
        if (!$rulesValidator->isValid($methods)) {
            foreach ($rulesValidator->getMessages() as $message) {
                $this->_getSession()->addError($message);
            }
            return false;
        }
        if ($data['sales_options']['sales_type'] == Diglin_Ricento_Model_Config_Source_Sales_Type::AUCTION) {
            $startDateInfo = date_parse_from_format(Varien_Date::DATE_PHP_FORMAT, $data['sales_options']['schedule_date_start']);
            if ($startDateInfo['error_count']) {
                $this->_getSession()->addError($this->__('Invalid start date.') . '<br>' . join ('<br>', $startDateInfo['errors']));
                return false;
            }
            if ($data['sales_options']['schedule_period_days'] <= 0) {
                $this->_getSession()->addError($this->__('The end date must be in the future.'));
                return false;
            }
        }

        return true;
    }


    /**
     * Save rules and sales options for listing or for items
     */
    public function saveConfiguration($data)
    {
        $data = $this->_filterPostData($data);
        if (!$this->_savingAllowed()) {
            $this->_getSession()->addError($this->__('Listed listings cannot be modified. Stop the listing first to make changes.'));
            $this->_redirect($this->_getEditUrl());
            return false;
        }

        $this->_getSalesOptions()->setDataToAll($data['sales_options']);
        $this->_getShippingPaymentRule()->setDataToAll($data['rules']);

        if (!$this->_validatePostData($data)) {
            $this->_saveFormDataInSession($data);
            $this->_redirectUrl($this->_getEditUrl());
            return false;
        }

        try {
            if (isset($data['sales_options']['use_products_list_settings'])) {
                $this->_getSalesOptions()->walk('delete');
            } else {
                $this->_getSalesOptions()->walk('save');
            }

            if (isset($data['rules']['use_products_list_settings'])) {
                $this->_getShippingPaymentRule()->walk('delete');
            } else {
                $this->_getShippingPaymentRule()->walk('save');
            }

            $this->_saveFormDataInSession(null);
            if ($this->getRequest()->getParam('back')) {
                $this->_redirectUrl($this->_getEditUrl());
                return false;
            }
            $this->_redirectUrl($this->_getIndexUrl());
            return true;

        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            Mage::logException($e);
            $this->_getSession()->addException($e, $this->__('An error occurred while saving the configuration.'));
        }

        $this->_saveFormDataInSession($data);
        $this->_redirectUrl($this->_getEditUrl());
        return false;
    }

    protected function _saveFormDataInSession($data)
    {
        if ($data === null) {
            $data = array('rules' => null, 'sales_options' => null);
        }
        $this->_getSession()->setRulesFormData($data['rules']);
        $this->_getSession()->setSalesOptionsFormData($data['sales_options']);
    }

    /**
     * @return boolean
     */
    abstract protected function _savingAllowed();

    /**
     * @return Varien_Data_Collection
     */
    abstract protected function _getSalesOptions();

    /**
     * @return Varien_Data_Collection
     */
    abstract protected function _getShippingPaymentRule();

    /**
     * @return string
     */
    abstract protected function _getEditUrl();

    /**
     * @return string
     */
    abstract protected function _getIndexUrl();
}
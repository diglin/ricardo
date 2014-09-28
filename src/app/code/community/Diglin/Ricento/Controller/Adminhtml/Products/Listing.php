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

/**
 * Class Diglin_Ricento_Controller_Adminhtml_Products_Listing
 *
 * Abstract controller for controllers that save listing configuration
 */
abstract class Diglin_Ricento_Controller_Adminhtml_Products_Listing extends Diglin_Ricento_Controller_Adminhtml_Action
{
    /**
     * @var Varien_Data_Collection
     */
    protected $_salesOptionsCollection;

    /**
     * @var Varien_Data_Collection
     */
    protected $_shippingPaymentCollection;

    /**
     * Filtering posted data (sales options and rules form). Converting localized data if needed
     *
     * @param array
     * @return array
     */
    protected function _filterPostData($data)
    {
        /* Product Listing part */

        if (!isset($data['product_listing'])) {
            $data['product_listing'] = array();
        }

        if (isset($data['product_listing']['publish_languages']) && $data['product_listing']['publish_languages'] != Diglin_Ricento_Helper_Data::LANG_ALL) {
            $data['product_listing']['default_language'] = $data['product_listing']['publish_languages'];
            foreach (Mage::helper('diglin_ricento')->getSupportedLang() as $lang) {
                if ($data['product_listing']['publish_languages'] != $lang) {
                    $data['product_listing']['lang_store_id_' . $lang] = null;
                }
            }
        }

        /* Sales Option part */

        if (!isset($data['sales_options'])) {
            $data['sales_options'] = array();
        }
        $data['sales_options'] = $this->_filterDateTime($data['sales_options'], array('schedule_date_start', 'schedule_period_end_date'));
        if (empty($data['sales_options']['entity_id'])) {
            unset($data['sales_options']['entity_id']);
        }
        if (!empty($data['sales_options']['ricardo_category_use_mapping'])) {
            $data['sales_options']['ricardo_category'] = -1;
        }
        if (!empty($data['sales_options']['schedule_cycle_multiple_products_random'])) {
            $data['sales_options']['schedule_cycle_multiple_products'] = null;
        }
        if (!empty($data['sales_options']['stock_management_use_inventory'])) {
            $data['sales_options']['stock_management'] = -1;
        }
        if (!empty($data['sales_options']['sales_auction_direct_buy'])) {
            $data['sales_options']['stock_management'] = 1;
        }
        if (!empty($data['sales_options']['schedule_date_start_immediately'])) {
            $data['sales_options']['schedule_date_start'] = null;
            if ($data['sales_options']['sales_type'] == Diglin_Ricento_Model_Config_Source_Sales_Type::AUCTION) {
                $dateStart = new DateTime();
                $derivedValues['schedule_date_start'] = $dateStart->format(Varien_Date::DATETIME_PHP_FORMAT);
            }
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
            $data['sales_options']['product_condition_source_attribute_code'] = 'ricardo_condition';
        }

        if (isset($data['sales_options']['product_warranty'])) {
            if ($data['sales_options']['product_warranty'] == \Diglin\Ricardo\Enums\Article\Warranty::NONE) {
                unset($data['sales_options']['product_warranty_description_de']);
                unset($data['sales_options']['product_warranty_description_fr']);
            } else {
                $data['sales_options']['product_warranty_description_de'] = mb_substr(Mage::helper('core')->escapeHtml($data['sales_options']['product_warranty_description_de']), 0, 5000);
                $data['sales_options']['product_warranty_description_fr'] = mb_substr(Mage::helper('core')->escapeHtml($data['sales_options']['product_warranty_description_fr']), 0, 5000);
            }
        }
        if (!isset($data['sales_options']['promotion_start_page']) || empty($data['sales_options']['promotion_start_page'])) {
            $data['sales_options']['promotion_start_page'] = 0;
        }

        /* Rules part */

        if (!isset($data['rules'])) {
            $data['rules'] = array();
        }
        if (empty($data['rules']['rule_id'])) {
            unset($data['rules']['rule_id']);
        }

        if (isset($data['rules']['payment_methods'])) {
            $initDescription = true;
            foreach ($data['rules']['payment_methods'] as $method) {
                if ((int) $method === 0) {
                    $initDescription = false;
                }
            }
            if ($initDescription) {
                $data['rules']['payment_description_de'] = null;
                $data['rules']['payment_description_fr'] = null;
            }
        }

        if (!empty($data['rules']['payment_description_de'])) {
            $data['rules']['payment_description_de'] = mb_substr(Mage::helper('core')->escapeHtml($data['rules']['payment_description_de']), 0, 5000);
        }
        if (!empty($data['rules']['payment_description_fr'])) {
            $data['rules']['payment_description_fr'] = mb_substr(Mage::helper('core')->escapeHtml($data['rules']['payment_description_fr']), 0, 5000);
        }
        if (isset($data['rules']['shipping_method']) && (int) $data['rules']['shipping_method'] !== 0) {
            $data['rules']['shipping_description_de'] = null;
            $data['rules']['shipping_description_fr'] = null;
        }
        if (!empty($data['rules']['shipping_description_de'])) {
            $data['rules']['shipping_description_de'] = mb_substr(Mage::helper('core')->escapeHtml($data['rules']['shipping_description_de']), 0, 5000);
        }
        if (!empty($data['rules']['shipping_description_fr'])) {
            $data['rules']['shipping_description_fr'] = mb_substr(Mage::helper('core')->escapeHtml($data['rules']['shipping_description_fr']), 0, 5000);
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
        if (empty($data['rules']['use_products_list_settings'])) {
            /* @var $rulesValidator Diglin_Ricento_Model_Validate_Rules_Methods */
            $rulesValidator = Mage::getModel('diglin_ricento/validate_rules_methods');
            $methods = array(
                'payment' => array_filter((array)$data['rules']['payment_methods'], 'strlen'),
                'shipping' => $data['rules']['shipping_method']
            );
            if (!$rulesValidator->isValid($methods)) {
                foreach ($rulesValidator->getMessages() as $message) {
                    $this->_getSession()->addError($message);
                }
                return false;
            }
        }

        if (empty($data['sales_options']['use_products_list_settings'])) {
            if (!empty($data['sales_options']['schedule_date_start'])) {
                $startDateInfo = date_parse_from_format(Varien_Date::DATETIME_PHP_FORMAT, $data['sales_options']['schedule_date_start']);
                if ($startDateInfo['error_count']) {
                    $this->_getSession()->addError($this->__('Invalid start date.') . '<br>' . join ('<br>', $startDateInfo['errors']));
                    return false;
                }
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
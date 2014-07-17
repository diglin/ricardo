<?php
abstract class Diglin_Ricento_Controller_Adminhtml_Products_Listing extends Mage_Adminhtml_Controller_Action
{
    /**
     * @var Varien_Data_Collection
     */
    protected $_salesOptionsCollection;

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
        $registeredListing = Mage::registry('products_listing');
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
            $data['sales_options']['schedule_period_days'] = date_diff(
                new DateTime($data['sales_options']['schedule_date_start']),
                new DateTime($data['sales_options']['schedule_period_end_date']))->days;
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
        //TODO validation if necessary
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

        unset($data['sales_options']['entity_id']);
        $this->_getSalesOptions()->setDataToAll($data['sales_options']);

        if (!$this->_validatePostData($data)) {
            $this->_redirectUrl($this->_getEditUrl());
            return false;
        }

        try {
            $this->_getSalesOptions()->walk('save');

            $this->_getSession()->setFormData(false);
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

        $this->_getSession()->setFormData($data);
        $this->_redirectUrl($this->_getEditUrl());
        return false;
    }

    /**
     * @return boolean
     */
    abstract protected function _savingAllowed();

    /**
     * @return Diglin_Ricento_Model_Resource_Sales_Options_Collection
     */
    abstract protected function _getSalesOptions();

    /**
     * @return string
     */
    abstract protected function _getEditUrl();

    /**
     * @return string
     */
    abstract protected function _getIndexUrl();
}
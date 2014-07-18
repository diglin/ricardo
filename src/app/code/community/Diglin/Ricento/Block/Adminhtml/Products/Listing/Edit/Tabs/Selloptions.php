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
 * Class Diglin_Ricento_Block_Adminhtml_Products_Listing_Edit_Tabs_Selloptions
 */
class Diglin_Ricento_Block_Adminhtml_Products_Listing_Edit_Tabs_Selloptions
    extends Diglin_Ricento_Block_Adminhtml_Products_Listing_Form_Abstract
    implements Mage_Adminhtml_Block_Widget_Tab_Interface

{
    /**
     * @var Diglin_Ricento_Model_Resource_Sales_Options
     */
    protected $_model;

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $htmlIdPrefix = 'sales_options_';
        $form->setHtmlIdPrefix($htmlIdPrefix);
        $form->addField('entity_id', 'hidden', array(
            'name' => 'sales_options[entity_id]',
        ));

        $fieldsetCategory = $form->addFieldset('fieldset_category', array('legend' => Mage::helper('catalog')->__('Category')));
        $fieldsetCategory->addType('radios_extensible', Mage::getConfig()->getBlockClassName('diglin_ricento/adminhtml_form_element_radios_extensible'));
        $fieldsetCategory->addField('ricardo_category_use_mapping', 'radios_extensible', array(
            'name'      => 'sales_options[ricardo_category_use_mapping]',
            'label'     => $this->__('Ricardo Category'),
            'separator' => '<br>',
            'values'    => array(
                array('value' => 0, 'label' => $this->__('Use Magento / Ricardo Category mapping (if mapping does not exist, an error message will be triggered while preparing the synchronization to Ricardo)')),
                array('value' => 1, 'label' => $this->__('Select Ricardo Category'))
            )
        ));
        $fieldsetCategory->addType('ricardo_category', Mage::getConfig()->getBlockClassName('diglin_ricento/adminhtml_catalog_category_form_renderer_mapping'));
        $fieldsetCategory->addField('ricardo_category', 'ricardo_category', array(
            'name'  => 'sales_options[ricardo_category]',
            'label' => $this->__('Select the category')
        ));


        $fieldsetType = $form->addFieldset('fieldset_type', array('legend' => $this->__('Type of sales')));
        $fieldsetType->addField('sales_type', 'select', array(
            'name'      => 'sales_options[sales_type]',
            'required'  => true,
            'label'     => $this->__('Type of sales'),
            'values'    => Mage::getModel('diglin_ricento/config_source_sales_type')->getAllOptions(),
            'onchange'  => '' //TODO toggle fieldset
        ));
        $fieldsetTypeFixPrice = $fieldsetType->addFieldset('fieldset_type_fixprice', array('legend' => $this->__('Fix price')));
        $fieldsetTypeFixPrice->addField('price_source_attribute_id', 'select', array(
            'name'    => 'sales_options[price_source_attribute_id]',
            'label'   => $this->__('Source'),
            'values'  => Mage::getModel('diglin_ricento/config_source_sales_price_source')->getAllOptions()
        ));
        $fieldsetTypeFixPrice->addType('fieldset_inline', Mage::getConfig()->getBlockClassName('diglin_ricento/adminhtml_form_element_fieldset_inline'));
        $fieldsetPriceChange = $fieldsetTypeFixPrice->addField('fieldset_price_change', 'fieldset_inline', array(
            'label'             => $this->__('Price Change'),
        ));
        $fieldsetPriceChange->addField('price_change_type', 'select', array(
            'name'               => 'sales_options[price_change_type]',
            'after_element_html' => ' +&nbsp;',
            'no_span'            => true,
            'values'             => Mage::getModel('diglin_ricento/config_source_sales_price_method')->getAllOptions()
        ));
        $fieldsetPriceChange->addField('price_change', 'text', array(
            'name'    => 'sales_options[price_change]',
            'no_span' => true,
            'class'   => 'inline-number validate-number',
        ));
        $fieldsetTypeFixPrice->addField('sales_auction_direct_buy', 'select', array(
            'name'   => 'sales_options[sales_auction_direct_buy]',
            'label'  => $this->__('Allow Direct Buy (in case of auction type of sales)'),
            'values' => Mage::getModel('eav/entity_attribute_source_boolean')->getAllOptions()
        ));
        $fieldsetTypeFixPrice->addField('fix_currency', 'label', array(
            'name'  => 'sales_options[fix_currency]',
            'label' => $this->__('Currency')
        ));
        $fieldsetTypeAuction = $fieldsetType->addFieldset('fieldset_type_auction', array('legend' => $this->__('Auction')));
        $fieldsetTypeAuction->addField('sales_auction_start_price', 'text', array(
            'name'  => 'sales_options[sales_auction_start_price]',
            'label' => $this->__('Start price')
        ));
        $fieldsetTypeAuction->addField('sales_auction_increment', 'text', array(
            'name'  => 'sales_options[sales_auction_increment]',
            'label' => $this->__('Increment')
        ));
        $fieldsetTypeAuction->addField('auction_currency', 'label', array(
            'name'  => 'sales_options[auction_currency]',
            'label' => $this->__('Currency'),
        ));


        $fieldsetSchedule = $form->addFieldset('fieldset_schedule', array('legend' => $this->__('Schedule')));
        $fieldsetSchedule->addType('radios_extensible', Mage::getConfig()->getBlockClassName('diglin_ricento/adminhtml_form_element_radios_extensible'));
        $dateFormatIso = Mage::app()->getLocale()->getDateFormat(
            Mage_Core_Model_Locale::FORMAT_TYPE_SHORT
        );
        $fieldsetSchedule->addField('schedule_date_start_immediately', 'radios_extensible', array(
            'name'   => 'sales_options[schedule_date_start_immediately]',
            'label'  => $this->__('Start'),
            'values' => array(
                array('value' => 1, 'label' => $this->__('Start immediately')),
                array('value' => 0, 'label' => $this->__('Start from'), 'field' => array(
                    'schedule_date_start', 'date', array(
                        'name'   => 'sales_options[schedule_date_start]',
                        'image'  => $this->getSkinUrl('images/grid-cal.gif'),
                        'format' => $dateFormatIso,
                        'class'  => '' //TODO concrete validation (validate-date only if radio button selected))
                    )
                ))
            )
        ));
        $fieldsetSchedule->addField('schedule_period_use_end_date', 'radios_extensible', array(
            'name'   => 'sales_options[schedule_period_use_end_date]',
            'label'  => $this->__('End'),
            'values' => array(
                array('value' => 0, 'label' => $this->__('End after %s days'), 'field' => array(
                    'schedule_period_days', 'select', array(
                        'name'    => 'sales_options[schedule_period_days]',
                        'options' => $this->_getDaysOptions(),
                        'class'   => 'inline-select'
                    )
                )),
                array('value' => 1, 'label' => $this->__('End on'), 'field' => array(
                    'schedule_period_end_date', 'date', array(
                        'name'   => 'sales_options[schedule_period_end_date]',
                        'image'  => Mage::getDesign()->getSkinUrl('images/grid-cal.gif'),
                        'format' => $dateFormatIso,
                        'class'  => '' //TODO concrete validation (validate-date only if radio button selected))
                    )
                ))
            )
        ));
        $fieldsetSchedule->addField('schedule_reactivation', 'select', array(
            'name'    => 'sales_options[schedule_reactivation]',
            'label'   => $this->__('Reactivation'),
            'options' => $this->_getReactivationOptions()
        ));
        $fieldsetSchedule->addField('schedule_cycle_multiple_products_random', 'radios_extensible', array(
            'name'  => 'sales_options[schedule_cycle_multiple_products_random]',
            'label' => $this->__('Cycle'),
            'values' => array(
                array('value' => 0, 'label' => $this->__('Cycle to publish multiple products %s minutes after the first publish'), 'field' => array(
                    'schedule_cycle_multiple_products', 'text', array(
                        'name'    => 'sales_options[schedule_cycle_multiple_products]',
                        'class'   => 'inline-number validate-number',
                    )
                )),
                array('value' => 1, 'label' => $this->__('Randomly published'))
            )
        ));
        $fieldsetSchedule->addField('schedule_overwrite_product_date_start', 'select', array(
            'name'   => 'sales_options[schedule_overwrite_product_date_start]',
            'label'  => $this->__('Overwrite all products starting date'),
            'values' => Mage::getModel('eav/entity_attribute_source_boolean')->getAllOptions()
        ));

        //TODO add product_warranty, product_condition and product_condition_source_attribute_id attributes to sales options table?
        $fieldsetCondition = $form->addFieldset('fieldset_condition', array('legend' => $this->__('Product Condition')));
        $fieldsetCondition->addType('checkboxes_extensible', Mage::getConfig()->getBlockClassName('diglin_ricento/adminhtml_form_element_checkboxes_extensible'));
        $fieldsetCondition->addField('product_condition_use_attribute', 'checkboxes_extensible', array(
            'name'   => 'sales_options[product_condition_use_attribute]',
            'label'  => $this->__('Condition Source'),
            'values' => array(
                array('value' => 1, 'label' => $this->__('If available use condition information from product'), 'field' => array(
                    'product_condition_source_attribute_id', 'select', array(
                        'name'  => 'sales_options[product_condition_source]',
                        'class' => 'inline-select',
                        'values' => Mage::getModel('diglin_ricento/config_source_sales_product_condition_source')->getAllOptions()
                    )
                ))
            )
        ));
        $fieldsetCondition->addField('product_condition', 'select', array(
            'name'   => 'sales_options[product_condition]',
            'label'  => $this->__('Condition'),
            'values' => Mage::getModel('diglin_ricento/config_source_sales_product_condition')->getAllOptions()
        ));
        $fieldsetCondition->addField('product_warranty', 'select', array(
            'name' => 'sales_options[product_warranty]',
            'label' => $this->__('Warranty'),
            'values' => Mage::getModel('eav/entity_attribute_source_boolean')->getAllOptions()
        ));


        $fieldsetStock = $form->addFieldset('fieldset_stock', array('legend' => $this->__('Stock Management')));
        $fieldsetStock->addType('radios_extensible', Mage::getConfig()->getBlockClassName('diglin_ricento/adminhtml_form_element_radios_extensible'));
        $fieldsetStock->addField('stock_management_use_inventory', 'radios_extensible', array(
            'name'   => 'sales_options[stock_management_use_inventory]',
            'label'  => $this->__('Stock Management'),
            'values' => array(
                array('value' => 1, 'label' => $this->__('Use product inventory')),
                array('value' => 0, 'label' => $this->__('Use custom qty'), 'field' => array(
                    'stock_management', 'text', array(
                        'name'  => 'sales_options[stock_management]',
                        'class' => 'inline-number validate-number'
                    )
                ))
            )
        ));


        $fieldsetCustomization = $form->addFieldset('fieldset_customization', array('legend' => $this->__('Customization')));
        $fieldsetCustomization->addField('customization_template', 'select', array(
            'name'    => 'sales_options[customization_template]',
            'label'   => $this->__('Template'),
            'values'  => Mage::getModel('diglin_ricento/config_source_sales_template')->getAllOptions()
        ));


        $fieldsetPromotion = $form->addFieldset('fieldset_promotion', array('legend' => $this->__('Promotion')));
        $fieldsetPromotion->addType('radios_extensible', Mage::getConfig()->getBlockClassName('diglin_ricento/adminhtml_form_element_radios_extensible'));
        $fieldsetPromotion->addField('promotion_space', 'radios_extensible', array(
            'name'   => 'sales_options[promotion_space]',
            'label'  => $this->__('Privilege Space'),
            'values' => Mage::getModel('diglin_ricento/config_source_sales_promotion')->getAllOptions()
        ));
        $this->setForm($form);

        return parent::_prepareForm();
    }

    protected function _initFormValues()
    {
        $this->getForm()->setValues($this->getSalesOptions());
        $derivedValues = array();
        $derivedValues['fix_currency'] = 'CHF';
        $derivedValues['auction_currency'] = 'CHF';
        if ($this->getSalesOptions()->getScheduleCycleMultipleProducts() === null) {
            $derivedValues['schedule_cycle_multiple_products_random'] = '1';
        }
        if ((int) $this->getSalesOptions()->getStockManagement() === -1) {
            $derivedValues['stock_management'] = '';
            $derivedValues['stock_management_use_inventory'] = 1;
        }
        if (!in_array($this->getSalesOptions()->getSchedulePeriodDays(), $this->_getDaysOptions())) {
            $derivedValues['schedule_period_use_end_date'] = 1;
            $derivedValues['schedule_period_end_date'] = date_add(
                new DateTime($this->getSalesOptions()->getScheduleDateStart()),
                DateInterval::createFromDateString($this->getSalesOptions()->getSchedulePeriodDays().' day')
            )->format(Varien_Date::DATE_PHP_FORMAT);
        }
        $this->getForm()->addValues($derivedValues);
        return parent::_initFormValues();
    }

    /**
     * Return Tab label
     *
     * @return string
     */
    public function getTabLabel()
    {
        return $this->__('Sell Options');
    }

    /**
     * Return Tab title
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->__('Sell Options');
    }

    /**
     * Can show tab in tabs
     *
     * @return boolean
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Tab is hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
        return false;
    }

    protected function _getDaysOptions()
    {
        //TODO extract to source model or helper
        return array(
            2 => 2,
            4 => 4,
            6 => 6,
            8 => 8,
            10 => 10
        );
    }

    protected function _getReactivationOptions()
    {
        //TODO extract to helper or source model
        return array(
            0 => 0,
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 5,
            null => $this->__('Until sold')
        );
    }

    /**
     * Returns sales options model
     *
     * @return Diglin_Ricento_Model_Sales_Options
     */
    public function getSalesOptions()
    {
        if ($this->_model) {
            return $this->_model;
        }
        $this->_model = $this->_getListing()->getSalesOptions();
        return $this->_model;
    }

}
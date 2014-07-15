<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
class Diglin_Ricento_Block_Adminhtml_Products_Listing_Edit_Tabs_Selloptions
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface

{
    /**
     * @var Diglin_Ricento_Model_Resource_Sales_Options
     */
    protected $_model;

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $htmlIdPrefix = 'diglin_ricento_';
        $form->setHtmlIdPrefix($htmlIdPrefix);

        $fieldsetCategory = $form->addFieldset('fieldset_category', array('legend' => Mage::helper('catalog')->__('Category')));
        $fieldsetCategory->addField('ricardo_category_use_mapping', 'radios', array(
            'name'      => 'ricardo_category_use_mapping',
            'label'     => $this->__('Ricardo Category'),
            'separator' => '<br>',
            'values'    => array(
                array('value' => 0, 'label' => $this->__('Use Magento / Ricardo Category mapping (if mapping does not exist, an error message will be triggered while preparing the synchronization to Ricardo)')),
                array('value' => 1, 'label' => $this->__('Select Ricardo Category'))
            )
        ));
        $fieldsetCategory->addType('ricardo_category', Mage::getConfig()->getBlockClassName('diglin_ricento/adminhtml_catalog_category_form_renderer_mapping'));
        $fieldsetCategory->addField('ricardo_category', 'ricardo_category', array(
            'name'  => 'ricardo_category',
            'label' => $this->__('Select the category')
        ));


        $fieldsetType = $form->addFieldset('fieldset_type', array('legend' => $this->__('Type of sales')));
        $fieldsetType->addField('sales_type', 'select', array(
            'name'      => 'sales_type',
            'required'  => true,
            'label'     => $this->__('Type of sales'),
            'values'    => Mage::getModel('diglin_ricento/config_source_sales_type')->getAllOptions(),
            'onchange'  => '' //TODO toggle fieldset
        ));
        $fieldsetTypeFixPrice = $fieldsetType->addFieldset('fieldset_type_fixprice', array('legend' => $this->__('Fix price')));
        $fieldsetTypeFixPrice->addField('price_source_attribute_id', 'select', array(
            'name'    => 'price_source_attribute_id',
            'label'   => $this->__('Source'),
            'values'  => Mage::getModel('diglin_ricento/config_source_sales_price_source')->getAllOptions()
        ));
        $fieldsetTypeFixPrice->addType('fieldset_inline', Mage::getConfig()->getBlockClassName('diglin_ricento/adminhtml_form_element_fieldset_inline'));
        $fieldsetPriceChange = $fieldsetTypeFixPrice->addField('fieldset_price_change', 'fieldset_inline', array(
            'label'             => $this->__('Price Change'),
        ));
        $fieldsetPriceChange->addField('price_change_type', 'select', array(
            'name'               => 'price_change_type',
            'after_element_html' => ' +&nbsp;',
            'no_span'            => true,
            'values'             => Mage::getModel('diglin_ricento/config_source_sales_price_method')->getAllOptions()
        ));
        $fieldsetPriceChange->addField('price_change', 'text', array(
            'name'    => 'price_change',
            'no_span' => true
        ));
        $fieldsetTypeFixPrice->addField('sales_auction_direct_buy', 'checkbox', array(
            'name'  => 'sales_auction_direct_buy',
            'label' => $this->__('Allow Direct Buy (in case of auction type of sales)'),
            'value' => '1'
        ));
        $fieldsetTypeFixPrice->addField('fix_currency', 'label', array(
            'name'  => 'fix_currency',
            'label' => $this->__('Currency')
        ));
        $fieldsetTypeAuction = $fieldsetType->addFieldset('fieldset_type_auction', array('legend' => $this->__('Auction')));
        $fieldsetTypeAuction->addField('sales_auction_start_price', 'text', array(
            'name'  => 'sales_auction_start_price',
            'label' => $this->__('Start price')
        ));
        $fieldsetTypeAuction->addField('sales_auction_increment', 'text', array(
            'name'  => 'sales_auction_increment',
            'label' => $this->__('Increment')
        ));
        $fieldsetTypeAuction->addField('auction_currency', 'label', array(
            'name'  => 'auction_currency',
            'label' => $this->__('Currency'),
            'value' => 'CHF'
        ));


        $fieldsetSchedule = $form->addFieldset('fieldset_schedule', array('legend' => $this->__('Schedule')));
        $fieldsetSchedule->addType('radios_extensible', Mage::getConfig()->getBlockClassName('diglin_ricento/adminhtml_form_element_radios_extensible'));
        $dateFormatIso = Mage::app()->getLocale()->getDateFormat(
            Mage_Core_Model_Locale::FORMAT_TYPE_SHORT
        );
        $fieldsetSchedule->addField('schedule_date_start_immediately', 'radios_extensible', array(
            'name'   => 'schedule_date_start_immediately',
            'label'  => $this->__('Start'),
            'values' => array(
                array('value' => 1, 'label' => $this->__('Start immediately')),
                array('value' => 0, 'label' => $this->__('Start from'), 'field' => array(
                    'schedule_date_start', 'date', array(
                        'name'   => 'schedule_date_start',
                        'image'  => $this->getSkinUrl('images/grid-cal.gif'),
                        'format' => $dateFormatIso,
                        'class'  => 'validate-date validate-date-range' //TODO concrete validation
                    )
                ))
            )
        ));
        $fieldsetSchedule->addField('schedule_period_use_end_date', 'radios_extensible', array(
            'name'   => 'schedule_period_use_end_date',
            'label'  => $this->__('End'),
            'values' => array(
                array('value' => 0, 'label' => $this->__('End after %s days'), 'field' => array(
                    'schedule_period_days', 'select', array(
                        'name'    => 'schedule_period_days',
                        'options' => $this->_getDaysOptions(),
                        'class'   => 'inline-select'
                    )
                )),
                array('value' => 1, 'label' => $this->__('End on'), 'field' => array(
                    'schedule_period_end_date', 'date', array(
                        'name'   => 'schedule_period_end_date',
                        'image'  => Mage::getDesign()->getSkinUrl('images/grid-cal.gif'),
                        'format' => $dateFormatIso,
                        'class'  => 'validate-date validate-date-range' //TODO concrete validation
                    )
                ))
            )
        ));
        $fieldsetSchedule->addField('schedule_reactivation', 'select', array(
            'name'    => 'schedule_reactivation',
            'label'   => $this->__('Reactivation'),
            'options' => $this->_getReactivationOptions()
        ));
        $fieldsetSchedule->addField('schedule_cycle_multiple_products_random', 'radios_extensible', array(
            'name'  => 'schedule_cycle_multiple_products_random',
            'label' => $this->__('Cycle'),
            'values' => array(
                array('value' => 0, 'label' => $this->__('Cycle to publish multiple products %s minutes after the first publish'), 'field' => array(
                    'schedule_cycle_multiple_products', 'text', array(
                        'name'    => 'schedule_cycle_multiple_products',
                        'class'   => 'inline-number validate-number',
                    )
                )),
                array('value' => 1, 'label' => $this->__('Randomly published'))
            )
        ));
        $fieldsetSchedule->addField('schedule_overwrite_product_date_start', 'checkbox', array(
            'name'  => 'schedule_overwrite_product_date_start',
            'label' => $this->__('Overwrite all products starting date'),
            'value' => '1'
        ));

        //TODO add product_warranty, product_condition and product_condition_source_attribute_id attributes to sales options table?
        $fieldsetCondition = $form->addFieldset('fieldset_condition', array('legend' => $this->__('Product Condition')));
        $fieldsetCondition->addType('checkboxes_extensible', Mage::getConfig()->getBlockClassName('diglin_ricento/adminhtml_form_element_checkboxes_extensible'));
        $fieldsetCondition->addField('product_condition_use_attribute', 'checkboxes_extensible', array(
            'name'   => 'product_condition_use_attribute',
            'label'  => $this->__('Condition Source'),
            'values' => array(
                array('value' => 1, 'label' => $this->__('If available use condition information from product'), 'field' => array(
                    'product_condition_source_attribute_id', 'select', array(
                        'name'  => 'product_condition_source',
                        'class' => 'inline-select',
                        'values' => Mage::getModel('diglin_ricento/config_source_sales_product_condition_source')->getAllOptions()
                    )
                ))
            )
        ));
        $fieldsetCondition->addField('product_condition', 'select', array(
            'name'   => 'product_condition',
            'label'  => $this->__('Condition'),
            'values' => Mage::getModel('diglin_ricento/config_source_sales_product_condition')->getAllOptions()
        ));
        $fieldsetCondition->addField('product_warranty', 'radios', array(
            'name' => 'product_warranty',
            'label' => $this->__('Warranty'),
            'values' => Mage::getModel('eav/entity_attribute_source_boolean')->getAllOptions()
        ));


        $fieldsetStock = $form->addFieldset('fieldset_stock', array('legend' => $this->__('Stock Management')));
        $fieldsetStock->addType('radios_extensible', Mage::getConfig()->getBlockClassName('diglin_ricento/adminhtml_form_element_radios_extensible'));
        $fieldsetStock->addField('stock_management_use_inventory', 'radios_extensible', array(
            'name'   => 'stock_management_use_inventory',
            'label'  => $this->__('Stock Management'),
            'values' => array(
                array('value' => 1, 'label' => $this->__('Use product inventory')),
                array('value' => 0, 'label' => $this->__('Use custom qty'), 'field' => array(
                    'stock_management', 'text', array(
                        'name'  => 'stock_management',
                        'class' => 'inline-number validate-number'
                    )
                ))
            )
        ));


        $fieldsetCustomization = $form->addFieldset('fieldset_customization', array('legend' => $this->__('Customization')));
        $fieldsetCustomization->addField('customization_template', 'select', array(
            'name'    => 'customization_template',
            'label'   => $this->__('Template'),
            'values'  => Mage::getModel('diglin_ricento/config_source_sales_template')->getAllOptions()
        ));


        $fieldsetPromotion = $form->addFieldset('fieldset_promotion', array('legend' => $this->__('Promotion')));
        $fieldsetPromotion->addField('promotion_space', 'radios', array(
            'name'   => 'promotion_space',
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
        if ($this->getSalesOptions()->getScheduleCycleMultipleProducts() === null) {
            $derivedValues['schedule_cycle_multiple_products_random'] = '1';
        }
        if ((int) $this->getSalesOptions()->getStockManagement() === -1) {
            $derivedValues['stock_management'] = '';
            $derivedValues['stock_management_use_inventory'] = 1;
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
        $listing = Mage::registry('products_listing');
        if (!$listing) {
            Mage::throwException('Products listing not loaded');
        }
        $this->_model = $listing->getSalesOptions();
        return $this->_model;
    }

}
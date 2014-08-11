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
 * Class Diglin_Ricento_Block_Adminhtml_Products_Listing_Edit_Tabs_General
 */
class Diglin_Ricento_Block_Adminhtml_Products_Listing_Edit_Tabs_General
    extends Diglin_Ricento_Block_Adminhtml_Products_Listing_Form_Abstract
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $htmlIdPrefix = 'product_listing_';
        $form->setHtmlIdPrefix($htmlIdPrefix);

        $fieldsetMain = $form->addFieldset('fieldset_main', array('legend' => $this->__('General')));
        $fieldsetMain->addField('entity_id', 'hidden', array(
            'name' => 'product_listing[entity_id]',
        ));
        $fieldsetMain->addField('title', 'text', array(
            'name'  => 'product_listing[title]',
            'label' => $this->__('Title')
        ));
        $fieldsetMain->addField('status', 'select', array(
            'label'    => $this->__('Status'),
            'disabled' => true,
            'values'   => Mage::getSingleton('diglin_ricento/config_source_status')->getAllOptions()
        ));
        $fieldsetMain->addField('website_id', 'select', array(
            'label'    => $this->__('Website'),
            'disabled' => true,
            'values'   => Mage::getSingleton('adminhtml/system_store')->getWebsiteValuesForForm(true, false)
        ));

        $languages = Mage::helper('diglin_ricento')->getSupportedLang();

        $fieldsetLang = $form->addFieldset('fieldset_lang', array('legend' => $this->__('Language')));
        $fieldsetLang->addField('publish_languages', 'select', array(
            'name'      => 'publish_languages',
            'label'    => $this->__('Product languages to synchronize to Ricardo.ch'),
            'note'     => $this->__('Ricardo.ch supports only two languages at the moment: German and French. You can set in which language you want to publish your product content (title, subtitle, description, etc).'),
            'values'   => Mage::getSingleton('diglin_ricento/config_source_languages')->getAllOptions(),
            'onchange' => 'generalForm.onChangeInput(this, [\''. implode('\',\'', $languages) .'\']);',
            'required' => true

        ));
        $fieldsetLang->addField('default_language', 'select', array(
            'name'     => 'default_language',
            'label'    => $this->__('Default language to publish'),
            'note'     => $this->__('Which language to publish by default to Ricardo.ch when the product content is not available in a language'),
            'values'   => Mage::getSingleton('diglin_ricento/config_source_languages')->getAllOptions(false),
            'required' => true
        ));

        foreach ($languages as $lang) {
            $title = Mage::helper('catalog')->__('Store View for ' . ucwords(Mage::app()->getLocale()->getTranslation($lang, 'language')));
            $fieldsetLang->addField('lang_'. $lang .'_store_id', 'select', array(
                'name'      => 'lang_'. $lang .'_store_id',
                'label'     => $title,
                'title'     => $title,
                'class'     => 'lang_store_id',
                'values'    => Mage::getSingleton('diglin_ricento/config_source_store')
                        ->setWebsiteId($this->_getListing()->getWebsiteId())
                        ->getStoreValuesForForm(false, true),
                'required'  => true,
            ));
        }


        $this->setForm($form);

        return parent::_prepareForm();
    }

    protected function _initFormValues()
    {
        $this->getForm()->setValues($this->_getListing());

        $publishLanguages = $this->_getListing()->getPublishLanguages();

        if ($publishLanguages != 'all') {
            $this->getForm()->getElement('default_language')->setDisabled(true);
            $languages = Mage::helper('diglin_ricento')->getSupportedLang();
            foreach ($languages as $lang) {
                if ($publishLanguages != $lang) {
                    $this->getForm()->getElement('lang_'. strtolower($lang) .'_store_id')->setDisabled(true);
                }
            }
        }

        return parent::_initFormValues();
    }

    protected function _afterToHtml($html)
    {
        $html .= '<script type="text/javascript">var generalForm = new Ricento.GeneralForm("' . $this->getForm()->getHtmlIdPrefix() . '");</script>';
        return parent::_afterToHtml($html);
    }

    /**
     * Return Tab label
     *
     * @return string
     */
    public function getTabLabel()
    {
        return $this->__('General');
    }

    /**
     * Return Tab title
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->__('General');
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

    protected function _getListing()
    {
        return Mage::registry('products_listing');
    }
}
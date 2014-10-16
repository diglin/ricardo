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
 * Class Diglin_Ricento_Model_Config_Source_Languages
 */
class Diglin_Ricento_Model_Config_Source_Languages extends Diglin_Ricento_Model_Config_Source_Abstract
{
    /**
     * Retrieve All options
     *
     * @param bool $all
     * @return array
     */
    public function getAllOptions($all = true)
    {
        $options = array();
        foreach ($this->toOptionHash($all) as $value => $label) {
            $options[] = array('label' => $label, 'value' => $value);
        }
        return $options;
    }

    /**
     * Return options as value => label array
     *
     * @param bool $all
     * @return array
     */
    public function toOptionHash($all = true)
    {
        $helper = Mage::helper('diglin_ricento');
        $languagesSupported = $helper->getSupportedLang();


        $return = array('' => $helper->__('-- Please select --'));
        foreach ($languagesSupported as $lang ){
            $return[$lang] = Mage::app()->getLocale()->getTranslation($lang, 'language');
        }

        if ($all) {
            $return[Diglin_Ricento_Helper_Data::LANG_ALL] = $helper->__('All languages');
        }

        return $return;
    }
}
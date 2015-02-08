<?php
/**
 * ricardo.ch AG - Switzerland
 *
 * @author      Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2014 ricardo.ch AG (http://www.ricardo.ch)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Class Diglin_Ricento_Model_Entity_Attribute_Source_Conditions
 */
class Diglin_Ricento_Model_Entity_Attribute_Source_Conditions extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    /**
     * Retrieve All options
     * @todo - replace API call by hard coded list of options because these options are displayed in product edit page
     *
     * @return array
     */
    public function getAllOptions()
    {
        // need to check if configured because it blocks users to edit product page until they receive API info
        try {
            if (Mage::helper('diglin_ricento')->isConfigured()) {
                if (is_null($this->_options)) {

                    $this->_options = Mage::getSingleton('diglin_ricento/config_source_sales_product_condition')->getAllOptions();
                }
                return $this->_options;
            }

            return array('label' => Mage::helper('diglin_ricento')->__('No Options because API is not configured'), 'value' => '');
        } catch (Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('core/session')->addError('No ricardo.ch condition options retrieved cause of an API problem');
        }
        return array('label' => Mage::helper('diglin_ricento')->__('No Options because API has a problem'), 'value' => '');
    }
}

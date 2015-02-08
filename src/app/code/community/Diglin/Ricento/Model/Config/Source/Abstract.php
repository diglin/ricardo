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
 * Abstract base class for ricento attribute source models, used in edit form
 */
abstract class Diglin_Ricento_Model_Config_Source_Abstract implements Mage_Eav_Model_Entity_Attribute_Source_Interface
{
    /**
     * Retrieve All options
     *
     * @return array
     */
    public function getAllOptions()
    {
        $options = array();
        foreach ($this->toOptionHash() as $value => $label) {
            $options[] = array('label' => $label, 'value' => $value);
        }
        return $options;
    }

    /**
     * Return options as value => label array
     *
     * @return array
     */
    abstract public function toOptionHash();

    /**
     * Retrieve Option value text
     *
     * @param string $value
     * @return mixed
     */
    public function getOptionText($value)
    {
        $options = $this->toOptionHash();
        if (isset($options[$value])) {
            return $options[$value];
        }
        return null;
    }

}
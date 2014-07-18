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
        $options = $this->getOptionHash();
        if (isset($options[$value])) {
            return $options[$value];
        }
        return null;
    }

}
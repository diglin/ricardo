<?php
/**
 * ricardo.ch AG - Switzerland
 *
 * @author      Sylvain Rayé <support at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2014 ricardo.ch AG (http://www.ricardo.ch)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Class Diglin_Ricento_Model_Config_Source_Sales_Order_Status
 */
class Diglin_Ricento_Model_Config_Source_Sales_Order_Status extends Mage_Adminhtml_Model_System_Config_Source_Order_Status
{
    /**
     * @var array
     */
    protected $_stateStatuses = array(
        Mage_Sales_Model_Order::STATE_NEW,
        Diglin_Ricento_Helper_Data::ORDER_STATUS_PENDING,
        Mage_Sales_Model_Order::STATE_PROCESSING,
    );
}
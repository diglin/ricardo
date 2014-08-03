<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */

/**
 * Class Diglin_Ricento_Model_Api_Services_Security
 */
class Diglin_Ricento_Model_Api_Services_System extends Diglin_Ricento_Model_Api_Services_Abstract
{
    /**
     * @var string
     */
    protected $_serviceName = 'system';

    /**
     * @var string
     */
    protected $_model = '\Diglin\Ricardo\Managers\System';

    /**
     * Overwritten just to get the class/method auto completion
     *
     * @param int|Mage_Core_Model_Website $website
     * @return \Diglin\Ricardo\Managers\System
     */
    public function getServiceModel($website = 0)
    {
        return parent::getServiceModel($website);
    }
}
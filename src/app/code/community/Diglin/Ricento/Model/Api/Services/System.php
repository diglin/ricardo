<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <sylvain.raye at diglin.com>
 * @category    Ricento
 * @package     Diglin_Ricardo
 * @copyright   Copyright (c) 2011-2014 Diglin (http://www.diglin.com)
 */
use \Diglin\Ricardo\Managers\System;

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
    protected $_model = 'System';

    /**
     * @return System
     */
    public function getServiceModel()
    {
        return parent::getServiceModel();
    }
}
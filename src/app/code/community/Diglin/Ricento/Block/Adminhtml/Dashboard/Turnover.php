<?php
/*
 * ricardo.ch AG - Switzerland
 *
 * @author Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2014 ricardo.ch AG (http://www.ricardo.ch)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Class Diglin_Ricento_Block_Adminhtml_Dashboard_Turnover
 */
class Diglin_Ricento_Block_Adminhtml_Dashboard_Turnover extends Mage_Adminhtml_Block_Dashboard_Graph
{
    /**
     * Initialize object
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->setHtmlId('turnover');
        $this->setTemplate('ricento/dashboard/turnover/graph.phtml');
    }

    /**
     * Prepare chart data
     *
     * @return void
     */
    protected function _prepareData()
    {
        $this->setDataHelperName('diglin_ricento/dashboard_turnover');

        $this->setDataRows('revenue');
        $this->_axisMaps = array(
            'x' => 'range',
            'y' => 'revenue'
        );

        parent::_prepareData();
        $this->getDataHelper()->setParam('period', '1y');
    }
}
<?php
/**
 * Diglin GmbH - Switzerland
 *
 * @author Sylvain Rayé <support at diglin.com>
 * @category    Diglin
 * @package     Diglin_Ricento
 * @copyright   Copyright (c) 2011-2015 Diglin (http://www.diglin.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Class Diglin_Ricento_Block_Adminhtml_Log
 */
class Diglin_Ricento_Block_Adminhtml_Log extends Mage_Adminhtml_Block_Widget_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('ricentoLog');
        $this->setTemplate('ricento/log.phtml');

        $this->_headerText = Mage::helper('diglin_ricento')->__('Logs');

        $this->removeButton('back');
        $this->removeButton('delete');
        $this->removeButton('add');
        $this->removeButton('save');
        $this->removeButton('edit');

        $this->addButton('show_listing', array(
            'label' => $this->__('Show Products Listing'),
            'onclick' => 'setLocation(\''.$this->getUrl('*/products_listing/index').'\')',
        ));
    }

    protected function _toHtml()
    {
        $activeTab = !is_null($this->getData('active_tab')) ? $this->getData('active_tab') : Diglin_Ricento_Block_Adminhtml_Log_Tabs::TAB_LISTING;
        $tabsBlock = $this->getLayout()
            ->createBlock('diglin_ricento/adminhtml_log_tabs', '', array('active_tab' => $activeTab));

        return parent::_toHtml() . $tabsBlock->toHtml() . '<div id="tabs_container"></div>';
    }
}
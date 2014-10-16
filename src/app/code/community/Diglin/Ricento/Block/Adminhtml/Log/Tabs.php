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
class Diglin_Ricento_Block_Adminhtml_Log_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    const TAB_LISTING            = 'listing';
    const TAB_ORDER              = 'order';
    const TAB_SYNCHRONIZATION    = 'synchronization';

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('widget/tabshoriz.phtml');
        $this->setId('ricentoLogTabs');
        $this->setDestElementId('tabs_container');
    }

    protected function _prepareLayout()
    {
        $this->addTab(self::TAB_LISTING, $this->prepareTabListing());
        //$this->addTab(self::TAB_ORDER, $this->prepareTabOrder());
        $this->addTab(self::TAB_SYNCHRONIZATION, $this->prepareTabSynchronization());

        $this->setActiveTab($this->getData('active_tab'));

        return parent::_prepareLayout();
    }

    protected function prepareTabListing()
    {
        $helper = Mage::helper('diglin_ricento');
        $tab = array(
            'label' => $helper->__('Listing'),
            'title' => $helper->__('Listing')
        );

        if ($this->getData('active_tab') == self::TAB_LISTING) {
            $tab['content'] = $this->getLayout()->createBlock('diglin_ricento/adminhtml_products_listing_log_grid', 'ricento_listing_log')->toHtml();
        } else {
            $tab['url'] = $this->getUrl('*/log/listing');
        }

        return $tab;
    }

    protected function prepareTabSynchronization()
    {
        $helper = Mage::helper('diglin_ricento');
        $tab = array(
            'label' => $helper->__('Synchronization'),
            'title' => $helper->__('Synchronization')
        );

        if ($this->getData('active_tab') == self::TAB_SYNCHRONIZATION) {
            $tab['content'] = $this->getLayout()->createBlock('diglin_ricento/adminhtml_sync_log_grid', 'ricento_sync_log')->toHtml();
        } else {
            $tab['url'] = $this->getUrl('*/log/sync');
        }

        return $tab;
    }

    protected function prepareTabOrder()
    {
        $helper = Mage::helper('diglin_ricento');
        $tab = array(
            'label' => $helper->__('Orders'),
            'title' => $helper->__('Orders')
        );

        if ($this->getData('active_tab') == self::TAB_ORDER) {
            $tab['content'] = $this->getLayout()->createBlock('diglin_ricento/adminhtml_order_log_grid', 'ricento_order_log')->toHtml();
        } else {
            $tab['url'] = $this->getUrl('*/log/order');
        }

        return $tab;
    }
}
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
 * Class Diglin_Ricento_Model_Observer
 *
 * Highly inspired from the Magento Hackathon Project: https://github.com/magento-hackathon/Magento-PSR-0-Autoloader
 * We do our own implementation to merge it in only one extension and to remove useless methods for our case
 *
 */
class Diglin_Ricento_Model_Observer
{
    const CONFIG_PATH_PSR0NAMESPACES = 'global/psr0_namespaces';

    static $shouldAdd = true;

    /**
     * @return array
     */
    protected function _getNamespacesToRegister()
    {
        $namespaces = array();
        $node = Mage::getConfig()->getNode(self::CONFIG_PATH_PSR0NAMESPACES);
        if ($node && is_array($node->asArray())) {
            $namespaces = array_keys($node->asArray());
        }
        return $namespaces;
    }

    /**
     * Add PSR-0 Autoloader for our Diglin_Ricardo library
     *
     * Event
     * - resource_get_tablename
     * - add_spl_autoloader
     */
    public function addAutoloader()
    {
        if (!self::$shouldAdd) {
            return;
        }

        foreach ($this->_getNamespacesToRegister() as $namespace) {
            $namespace = str_replace('_', '/', $namespace);
            if (is_dir(Mage::getBaseDir('lib') . DS . $namespace)) {
                $args = array($namespace, Mage::getBaseDir('lib') . DS . $namespace);
                $autoloader = Mage::getModel("diglin_ricento/splAutoloader", $args);
                $autoloader->register();
            }
        }

        self::$shouldAdd = false;
        return $this;
    }

    /**
     * Decrease the inventory on ricardo.ch when an order is passed outside of ricardo.ch
     *
     * @todo to finish
     *
     * Event
     * - sales_order_item_save_commit_after
     *
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function decreaseInventory(Varien_Event_Observer $observer)
    {
        /* @var $item Mage_Sales_Model_Order_Item */
//        $item = $observer->getEvent()->getItem();
//
//        if (!Mage::helper('diglin_ricento')->getDecreaseInventory() || $item->getOrder()->getIsRicardo()) {
//            return;
//        }
//
//        $collection = Mage::getResourceModel('diglin_ricento/products_listing_item_collection')
//            ->addFieldToFilter('product_id', $item->getProductId())
//            ->addFieldToFilter('status', Diglin_Ricento_Helper_Data::STATUS_LISTED)
//            ->addFieldToFilter('ricardo_article_id', new Zend_Db_Expr('not null'))
//            ->addFieldToFilter('is_planned', 0);
//
//        $sell = Mage::getSingleton('diglin_ricento/api_services_sell');
//
//        foreach ($collection->getItems() as $productItem) {
//            /**
//             * We cannot decrease below 1
//             */
//            $newQuantity = $productItem->getQtyInventory() - $item->getQtyOrdered();
//            if (!($newQuantity)) {
//
//                $productItem->setQtyInventory($newQuantity);
//
//                $sell->updateArticle($productItem);
//
//                $productItem->save();
//            }
//        }
//        return $this;
    }

    /**
     * Event
     * - adminhtml_block_html_before
     *
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function disableFormField(Varien_Event_Observer $observer)
    {
        $block = $observer->getEvent()->getBlock();

        if ($block instanceof Mage_Adminhtml_Block_Customer_Edit_Tab_Account) {
            $block->getForm()->getElement('ricardo_username')->setDisabled(true);
            $block->getForm()->getElement('ricardo_id')->setDisabled(true);
        }
        return $this;
    }

    /**
     * Retrieve payment information from a ricardo.ch order
     *
     * Event
     * - payment_info_block_prepare_specific_information
     *
     * @param Varien_Event_Observer $observer
     * @return $this
     */
    public function paymentMethodsInformation(Varien_Event_Observer $observer)
    {
        /* @var $payment Mage_Sales_Model_Order_Payment */
        $payment = $observer->getEvent()->getPayment();
        $transport = $observer->getEvent()->getTransport();

        if ($payment->getMethod() == Diglin_Ricento_Model_Sales_Method_Payment::PAYMENT_CODE) {
            $additionalData = Mage::helper('core')->jsonDecode($payment->getAdditionalData(), Zend_Json::TYPE_OBJECT);
            $methods = explode(',', $additionalData->ricardo_payment_methods);

            $label = array();
            foreach ($methods as $method) {
                $label[] = Mage::helper('diglin_ricento')->__(\Diglin\Ricardo\Enums\PaymentMethods::getLabel($method));
            }

            if (!empty($label)) {
                $transport->setData(array(
                    'bid_ids' => (isset($additionalData->ricardo_bid_ids)) ? $additionalData->ricardo_bid_ids : null,
                    'methods' => $label));
            }
        }
        return $this;
    }

    /**
     * Event
     * - core_block_abstract_to_html_before
     *
     * @todo to finish
     *
     * @param Varien_Event_Observer $observer
     * @return $this
     */
//    public function addSalesOrderGrid(Varien_Event_Observer $observer)
//    {
//        $block = $observer->getEvent()->getBlock();
//        if ($block instanceof Mage_Adminhtml_Block_Sales_Order_Grid) {
//            $block->addColumn('ricardo_username', array(
//                'header' => Mage::helper('diglin_ricento')->__('Ricardo Username'),
//                'index' => 'ricardo_username',
//                'after' => 'billing_name'
//            ));
//        }
//
//        return $this;
//    }

    /**
     * Event
     * - sales_quote_item_set_product
     *
     * We skipped custom option while processing an order via the ricardo.ch API
     *
     * @param Varien_Event_Observer $observer
     */
    public function setSkipppedRequiredOption(Varien_Event_Observer $observer)
    {
        /* @var $product Mage_Catalog_Model_Product */
        $product = $observer->getEvent()->getProduct();

        /* @var $quoteItem Mage_Sales_Model_Quote_Item */
        $quoteItem = $observer->getEvent()->getQuoteItem();

        if ($quoteItem->getQuote() && $quoteItem->getQuote()->getIsRicardo()) {
            $product->setSkipCheckRequiredOption(true);
        }
        return $this;
    }
}
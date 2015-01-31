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
 * Class Diglin_Ricento_Helper_Tools
 */
class Diglin_Ricento_Helper_Tools extends Mage_Core_Helper_Abstract
{
    const XML_PATH_EMAIL_NOTIFICATION_TEMPLATE = 'system/messages/notification_email_template';
    const XML_PATH_PM_EMAIL_TEMPLATE = 'system/messages/pm_email_template';

    /**
     * @param $message
     */
    public static function sendAdminNotification($message)
    {
        self::sendNotification(
            Mage::getStoreConfig(Mage_Log_Model_Cron::XML_PATH_EMAIL_LOG_CLEAN_IDENTITY), //  e.g. 'general'
            'support',
            Mage::getStoreConfig(self::XML_PATH_EMAIL_NOTIFICATION_TEMPLATE),
            array('message' => $message),
            Mage::app()->getStore()->getId()
        );
    }

    /**
     * Send email with specific message
     *
     * @param Mage_Customer_Model_Customer $customer
     * @param string $title
     * @param string $message
     * @param string $backUrl
     * @param int $storeId
     * @return Mage_Customer_Model_Customer
     */
    public static function sendPrivateMessage(Mage_Customer_Model_Customer $customer, $title, $message, $backUrl = '', $storeId = null)
    {
        if (is_null($storeId)) {
            $storeId = $customer->getStoreId();
        }
        $variables = array(
            'customer' => $customer, 'back_url' => $backUrl, 'message' => $message, 'title' => $title
        );

        self::sendNotification(
            Mage::getStoreConfig('contacts/email/sender_email_identity'), //  e.g. 'general'
            'customer',
            Mage::getStoreConfig(self::XML_PATH_PM_EMAIL_TEMPLATE),
            $variables,
            $storeId
        );
    }

    /**
     * Send a notification email to the customer or the shop managers
     *
     * @param string $sender
     * @param string $recipient
     * @param string $template
     * @param array $variables
     * @param int $storeId
     * @throw Exception
     */
    public static function sendNotification($sender = 'general', $recipient = 'customer', $template, $variables = array(), $storeId = null)
    {
        try {
            if ($recipient == 'customer') {
                $customer = $variables['customer'];

                if (is_numeric($customer)) {
                    $customer = Mage::getModel('customer/customer')->load($customer);
                }

                $recipient = array('name' => $customer->getName(), 'email' => $customer->getEmail());
            } else {
                $recipient = array(
                    'name' => Mage::getStoreConfig('trans_email/ident_' . $recipient . '/name', $storeId),
                    'email' => Mage::getStoreConfig('trans_email/ident_' . $recipient . '/email', $storeId)
                );
            }

            $translate = Mage::getSingleton('core/translate');
            $translate->setTranslateInline(false);

            $emailTemplate = Mage::getModel('core/email_template');
            $emailTemplate->setDesignConfig(array('area' => 'frontend', 'store' => $storeId))
                ->sendTransactional($template, // xml path email template
                    $sender,
                    $recipient['email'],
                    $recipient['name'],
                    $variables,
                    $storeId);

            $translate->setTranslateInline(true);
        } catch (Exception $e) {
            Mage::logException($e);
            self::sendAdminNotification($e->__toString());
        }
    }
}

# Ricento - The ricardo.ch extension for the Magento eCommerce Platform - Developed by Diglin GmbH

## Description

The Magento extension for ricardo.ch helps you easily to publish and sell your products on ricardo.ch marketplace offering you an additional stream of revenue.

This extension replaces the ricardo assistant and deliver you the same features as this one but from your Magento shop, by synchronizing your Magento catalog to ricardo.ch. Orders passed on ricardo.ch are sent back to your shop, keeping all your orders in a unique place and making your daily business easier.

With this extension, you can define a list of products to publish, set all payment and shipping rules supported by the ricardo.ch platform, choose the price variation and the type of sales (auction and/or direct sales), select the ricardo category where to publish, define product conditions or options to promote your products.

The extension is quite flexible, allowing you to define the settings at products listing level or product listing item level. It offers you also the capability to publish your product data to the two supported ricardo.ch languages: german and french.

## License

This extension is licensed under OSL v.3.0
Some classes and javascript contain a MIT license.

## Support & Documentation

- Knowledge Base & FAQ: [https://diglin.zendesk.com](https://diglin.zendesk.com)
- PDF Documentation [https://raw.githubusercontent.com/diglin/ricento/master/doc/Documentation.pdf](https://raw.githubusercontent.com/diglin/ricento/master/doc/Documentation.pdf)
- Pull Requests: [https://github.com/diglin/ricento/issues](https://github.com/diglin/ricento/issues)
- Submit tickets - Contact (fee may apply if it's not a bug, we will inform you): support /at/ diglin.com - [read more about it](https://diglin.zendesk.com/hc/en-us/articles/201655882-Is-the-extension-free-)

## System requirements

- [ricardo.ch](http://www.ricardo.ch/) account
- ricardo.ch API Token: please visit the [Ricardo API website](http://www.ricardo.ch/interface/)
- Magento CE >= 1.6.x to 1.9.x (for EE, please contact us)
- Minimum memory: 256MB - Highly Recommended: 512MB
- PHP >= 5.3.2
- PHP Curl Library
- Cron enabled and configured for Magento (set your cron at server level to a period of 5 min to launch internal task related to the rircardo extension
*/5 * * * * php path/to/my/magento/cron.php)
- Base currency: CHF / Swiss Franc (currency conversion is not yet supported)

## Features

#### Version 1.0

- Synchronize and sell your products to ricardo.ch marketplace (Magento -> ricardo.ch)
- Create products listing to elaborate different sales options and rules
- Configuration of the sales options and rules at products listing or products level
- Add products to a products listing from selected categories or manually selected
- Support of Magento product types: simple, grouped and configurable
- Product data synchronization in French and/or German
- Support for Swiss Franc currency
- Pre-Check the product data and products listing configuration before to send to ricardo.ch
- Cleanup of listing/synchronization jobs/orders logs after a period of time automatically after 30 days or a customized period
- Preview before to list your products
- Create the orders passed on ricardo.ch into your Magento shop
- Merge orders of different articles from the same customer when he bought products in a period of 30min (Cross Selling)

## Installation

### Via MagentoConnect

- You can install the current stable version via [MagentoConnect Website](http://www.magentocommerce.com/magento-connect/magento-extension-for-ricardo-ch-by-diglin.html)

### Manually

```
git clone https://github.com/diglin/ricento.git
git submodule init
git submodule fetch
```

Then copy the files and folders in the corresponding Magento folders
Do not forget the folder "lib"

### Via modman

- Install [modman](https://github.com/colinmollenhour/modman)
- Use the command from your Magento installation folder: `modman clone https://github.com/diglin/ricento.git`

#### Via Composer

- Install [composer](http://getcomposer.org/download/)
- Create a composer.json into your project like the following sample:

```
 {
    "require" : {
        "diglin/ricento": "1.*"
    },
    "repositories" : [
        {
            "type": "vcs",
            "url": "git@github.com:diglin/ricento.git"
        },
        {
            "type": "vcs",
            "url": "git@github.com:diglin/ricardo.git"
        }
    ],
     "scripts": {
         "post-package-install": [
             "Diglin\\Ricardo\\Composer\\Magento::postPackageAction"
         ],
         "post-package-update": [
             "Diglin\\Ricardo\\Composer\\Magento::postPackageAction"
         ],
         "pre-package-uninstall": [
             "Diglin\\Ricardo\\Composer\\Magento::cleanPackageAction"
         ]
     },
     "extra":{
       "magento-root-dir": "./"
     }
 }
 ```
- Then from your composer.json folder: `php composer.phar install` or `composer install`

## Uninstall

The module install some data and changes in your database. Deinstalling or deactivating the module will make some trouble cause of those data. You will need to remove those information by following the procedure below otherwise you will meet errors when using the Magento Backend It's a problem due to Magento, it's not related to the extension.

### Via MageTrashApp

An additional module called MageTrashApp may help you to uninstall this module in a clean way. Install it from [MageTrashApp](https://github.com/magento-hackathon/MageTrashApp)
If it is installed, go to your backend menu System > Configuration > Advanced > MageTrashApp, then click on the tab "Extension Installed", select the drop down option "Uninstall" of the module Diglin_Ricento and press "Save Config" button to uninstall
If you use this module, you don't need to make any queries in your database as explained below in case of manually uninstallation.

### Via Magento Connect 

MagentoConnect Manager doesn't allow to remove changes done into your database, it just removed the files installed.
We do not advise you to use it otherwise proceed also the "Database" cleanup process in the chapter "Manually" further below.

### Modman

Same as MagentoConnect, modman can only remove files but cannot cleanup your database. So you can run the command `modman remove Diglin_Ricento` from your Magento root project however you will have to run the database cleanup procedure explained in the chapter "Manually" below.

### Manually

Remove the files or folders located into your Magento installation:
```
app/etc/modules/Diglin_Ricento.xml
app/code/community/Diglin/Ricento
app/design/adminhtml/default/default/layout/ricento.xml
app/design/adminhtml/default/default/template/ricento
app/design/frontend/base/default/template/ricento
skin/adminhtml/default/default/ricento
js/ricento
app/locale/en_US/Diglin_Ricento.csv
app/locale/en_US/template/email/ricento
app/locale/fr_FR/Diglin_Ricento.csv
app/locale/fr_FR/template/email/ricento
app/locale/de_CH/Diglin_Ricento.csv
app/locale/de_CH/template/email/ricento
app/locale/de_DE/Diglin_Ricento.csv
app/locale/de_DE/template/email/ricento
```

Cleanup your database by executing those queries (add your table prefix if relevant)
```
DELETE FROM MYPREFIX_eav_attribute WHERE attribute_code = 'ricardo_id';
DELETE FROM MYPREFIX_eav_attribute WHERE attribute_code = 'ricardo_username';

DELETE FROM MYPREFIX_eav_attribute WHERE attribute_code = 'ricardo_category';
DELETE FROM MYPREFIX_eav_attribute WHERE attribute_code = 'ricardo_title';
DELETE FROM MYPREFIX_eav_attribute WHERE attribute_code = 'ricardo_subtitle';
DELETE FROM MYPREFIX_eav_attribute WHERE attribute_code = 'ricardo_description';
DELETE FROM MYPREFIX_eav_attribute WHERE attribute_code = 'ricardo_condition';

ALTER TABLE MYPREFIX_sales_flat_quote DROP COLUMN is_ricardo, DROP COLUMN customer_ricardo_id, DROP COLUMN customer_ricardo_username;
ALTER TABLE MYPREFIX_sales_flat_order DROP COLUMN is_ricardo, DROP COLUMN customer_ricardo_id, DROP COLUMN customer_ricardo_username;

DROP TABLE MYPREFIX_api_token;
DROP TABLE MYPREFIX_products_listing;
DROP TABLE MYPREFIX_products_listing_item;
DROP TABLE MYPREFIX_listing_log;
DROP TABLE MYPREFIX_sales_options;
DROP TABLE MYPREFIX_shipping_payment_rule;
DROP TABLE MYPREFIX_sync_job;
DROP TABLE MYPREFIX_sales_transaction;
DROP TABLE MYPREFIX_sync_job_listing;

DELETE FROM MYPREFIX_sales_order_status WHERE status = 'ricardo_payment_canceled';
DELETE FROM MYPREFIX_sales_order_status WHERE status = 'ricardo_payment_pending';
```

## Known Issues

- Magento 1.6.x doesn't support jstranslator.xml, some strings generated for JS will be displayed in english

## Author

* Diglin GmbH
* http://www.diglin.com/
* [@diglin_](https://twitter.com/diglin_)
* [Follow me on github!](https://github.com/diglin)

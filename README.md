# Ricento - The ricardo.ch extension for the Magento eCommerce Platform - Developed by Diglin GmbH

## Description

The Magento extension for ricardo.ch helps you easily to publish and sell your products on ricardo.ch marketplace offering you an additional stream of revenue.

This extension replaces the ricardo assistant and deliver you the same features as this one but from your Magento shop, by synchronizing your Magento catalog to ricardo.ch. Orders passed on ricardo.ch are sent back to your shop, keeping all your orders in a unique place and making your daily business easier.

With this extension, you can define a list of products to publish, set all payment and shipping rules supported by the ricardo.ch platform, choose the price variation and the type of sales (auction and/or direct sales), select the ricardo category where to publish, define product conditions or options to promote your products.

The extension is quite flexible, allowing you to define the settings at products listing level or product listing item level. It offers you also the capability to publish your product data to the two supported ricardo.ch languages: german and french.

## System requirements

- ricardo.ch API Token: please visit the [Ricardo API website](http://www.ricardo.ch/interface/)
- Magento CE 1.9.x (should work from version 1.6 but not yet tested - for EE, please contact us)
- Minimum memory: 256MB - Recommended: 512MB
- PHP >= 5.3.2
- PHP Curl Library
- Cron enabled and configured for Magento (set your cron at server level to a period of 5min to launch internal task related to the rircardo extension
*/5 * * * * php path/to/my/magento/cron.php)
- Base currency: CHF (currency convertion is not yet supported)

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

The module install some data and changes in your database. Deinstalling the module will make some trouble cause of those data. You will need to remove those information by following the procedure below.

### Via MageTrashApp

An additional module called MageTrashApp may help you to uninstall this module in a clean way. Install it from [MageTrashApp](https://github.com/magento-hackathon/MageTrashApp)
If it is installed, go to your backend menu System > Configuration > Advanced > MageTrashApp, then click on the tab "Extension Installed", select the drop down option "Uninstall" of the module Diglin_Ricento and press "Save Config" button to uninstall
If you use this module, you don't need to make any queries in your database as explained below in case of manually uninstallation.

### Via Magento Connect or manually

TODO

## Support / Author

* [Documentation & FAQ](https://diglin.zendesk.com)
* Contact for support is: support at diglin.com (fee may apply - [read more about it](https://diglin.zendesk.com/hc/en-us/articles/201655882-Is-the-extension-free-))
* Sylvain Rayé
* http://www.diglin.com/
* [@diglin_](https://twitter.com/diglin_)
* [Follow me on github!](https://github.com/diglin)

# Ricento - The Ricardo extension for Magento eCommerce Platform - Developed by Diglin GmbH #


### System requirements ###
- Ricardo API Token: please visit the [Ricardo API website](http://api.ricardo.ch)
- Magento CE 1.6 - 1.9.x
- PHP > 5.3.2
- PHP Curl Library
- Cron enabled and configured for Magento (@todo create a How-To)

### Features ###

- Synchronize and sell your products to ricardo.ch marketplace
- Synchronize orders passed on ricardo.ch to your Magento shop
- Create products listing to elaborate different sales options and rules
- Configuration of the sales options and rules at products listing or products level
- Add products to a products listing from selected categories
- Support of Magento product types: simple, configurable and grouped
- Interface in French, German and English
- Data Synchronization in French and/or German
- Support ONLY the Swiss Franc currency
- Check the product data and products listing configuration before to publish

### Language Synchronization ###

Each products listing can have its own language settings. The Ricardo.ch platform allows to publish product content in two languages: German and French
You can define if you want to publish the content in one or two languages and set which language is the default one.

To help you to configure the setting when you create a product listing, the extension will detect automatically which language you use for the current website and its store views.
Those information will be saved automatically into the products listing and you will have the possibility to change it.

To change the settings of the language in a product listing. Edit a products listing and activate the tab "General" and search the language block.
- Product languages to synchronize to Ricardo.ch: select if you want to publish the content in Ricardo on the french or german ricardo.ch website
- Default language to publish: if you want to publish your products on all ricardo.ch websites, you can set which language to use by default in case you do not have the content of your product in the target language
- Store View for XYZ: select in which store view you have the language XYZ. If you have set your Magento language settings at the level of "Websites", please create a new products listing for this website

### Add products to a products listing from selected categories ###

When you select a category, only the products belonging to this category will be added to the products listing, not the one belonging to the sub-categories.
If you also want to add the products belonging to one or more sub categories, you will have to select them too.
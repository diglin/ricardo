# Ricento Magento Extension FAQ #

## Which price is published on ricardo.ch ?

You can define at the products listing level or at the product configuration level (into the products listing) which product attribute source to use for the price.
It can be the "price" attribute or the "Special Price" attribute. The price from the default store view will be used and you can in the products list a specific price variation relative or absolute.

## Does the extension support the Magento Custom Options?

No. ricardo.ch doesn't offer a solution for a customer to choose an option. Plus, there are some many combination possible of custom options and custom attributes that
it will make hard to define a proper process to publish those options on ricardo.ch. However, we will try to find a global solutions after to have several feedbacks.
Do not hesitate to contact us to share your needs, we may use them for a future version.

## Why do you support only CHF / Swiss Franc ?

ricardo.ch platform supports only CHF (Swiss Franc). For this reason, your Magento shop must have as base currency CHF. The version 1.0 of the extension doesn't support price conversion.
If you are interested for such a feature please contact us, we will do an offer to support it.

## Which pictures are taken from a Magento Product?

Base image, picture set as part of the gallery in Magento backend and not disabled are used to be synced to ricardo.ch. Thumbnail and small images defined as image product in Magento are ignored. See screenshot

## Why do I have to authorise the API to get access to the extension?

For security reasons, the API token is valid 2 weeks at the time of writting and will be prolongate until 2 months in future. It means you have to authorize and validate that
you are the owner of the API. A message will be displayed in the Magento backend or an email sent when the API token is not anymore available valid.
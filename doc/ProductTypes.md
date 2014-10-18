# Product types #

Globally, no custom options are sent to ricardo.ch as it is not possible to display any interface to make a choice of those options. A warning message is displayed when products have custom options.
However some attributes depending on the product types are sent or used during the process, you will find the list below.

NOTE: attributes with a "*" are required.

## Simple ##

This type of product is pushed into ricardo as a single product.

### Attribute synchronized ###
- Ricardo Title or Name as fallback (*)
- Ricardo Subtitle
- Ricardo Condition (*)
- Ricardo Description or Description as fallback (*)
- Price or Special Price with ricardo price variation if configured (*)
- Ricardo Category (*)
- Qty (*)

## Grouped ##

This type of product is pushed into ricardo as a single product.
If the shop owner wants to split the products, he should use their simple product or create a separate grouped product.

For example, you sell 1 x sofa 200Fr. + 2 x chairs 400Fr./pcs. + 1 x table 500Fr., on ricardo.ch you will have one product with those three objects together with their default qty (1+2+1) for a price of 1500Fr. (200+400*2+500).
If this default quantity is not defined when you edit your product in Magento backend, the default quantity of "1" for each will be used.

### Attribute synchronized ###
- Ricardo Title or Name as fallback (*)
- Ricardo Subtitle
- Ricardo Condition (*)
- Ricardo Description or Description as fallback (*)
- Price: based on the associated products and their quantity (if defined - default is 1) and their products listing price variation. Special price is not supported by Magento for this type of product, so the price source is set to "Special Price" into the products listing, the normal price will be taken.
- Ricardo Category
- Qty: if the inventory is managed: the quantity is based on the default quantity set for each associated product, if empty set it to 1. If the inventory is not managed 1) take the quantity of the configuration of your products listing or 2) if the products listing configuration not set, do not allow any sync

## Configurable ##

With this type of product, each associated product is pushed into ricardo as a single product with the price and price changes defined in the parent and the ricardo price change defined in your products listing.

For example, you sell T-Shirts at 15Fr. each, in three sizes M, L, XL and in two colors blue and red. The T-shirt with the size XL cost 10% more.
On ricardo.ch, your configurable products will be separated in 6 single products if they are in stock and an available quantity greater than 1: M + red, M + blue, L + red, L + blue, etc
The price of each T-Shirt on ricardo.ch will be 15Fr. except for the XL one which will be 16.5Fr.

### Attribute synchronized Parent ###
- Ricardo Title or Name as fallback (*)
- Ricardo Subtitle
- Ricardo Condition (*)
- Ricardo Description or Description as fallback (*)
- Ricardo Category (*)
- Price: based price + 1) option price variation (see "Super product attributes configuration") + 2) ricardo price variation in products listing

### Attribute synchronized Child ###
- Qty: based on the setting of each simple product
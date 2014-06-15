### Ricardo API

Depending on the service, the token MUST NOT or CAN BE anonymous
Some need API methods needs antiforgery token (the list is too long or hazardous to define all of them)

## Anonymous Token

- SystemService: This service exposes referential data about the ricardo's system, for example available languages, list of categories, list of possible packages
- ArticleService: This service exposes methods related to the articles, for example the informations needed to display an article.
- SearchService: This service exposes all the methods that have to be used to perform a search within our data, for example getting the information about an auction, get all the articles from a specified seller
- BrandingService: This service is to be used to retreive all the information specific to a Cars&Bike article.

## Identified Token

- CustomerService: This service is to be used to manage all the generic data about your account: modify your password, archive your messages, get your preferences
- SellerAccountService: This service is to be used for everything that refers to your account as a seller: get all your open articles, get your sold articles, get your articles that haven't been sold
- SellService: This service is to be used to manage your articles as a seller: you can list, relist, modify, close an article
- BuyerAccountService: This service is to be used for everything that refers to your account as a buyer: get the bid you have placed, get the auctions you have won, get your prefered sellers
- BuyService: This service is to be used to handle your activity as a buyer: you can place a bid, or immediately buy an article.
- RatingService: This service is to be used for everything related to the ratings: get different kind of ratings (to give, to receive , ...), insert a new rating
- NotifyService: This service is to be used only for mobile purpose: you can register a device identifier, store the device preferences

## Usage of the Diglin Ricardo library


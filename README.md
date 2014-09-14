# Introduction

Diglin_Ricardo source code is a PHP Library to get access to the Ricardo.ch API which is a .NET Webservice sending Json or SOAP requests.
With this library we handle only json requests as it is faster.

## Installation

### Install for custom framework

To start you have to include in your PHP include path, the folder of your library folder where you are going to install this library and then set the autoloader.
Here is an example with a provided autoloader:

```
require_once __DIR__ . '/src/SplAutoloader.php';
$autoload = new SplAutoloader(null, realpath(dirname(__DIR__) . '/src'));
$autoload->register();
```

### Install via Composer

Add the following requirements into your composer.json at root project level. You do not need to add an autoloader, composer will handle it for you if your application is compatible with it.

```
 {
    "require" : {
        "diglin/ricardo": "1.*"
    },
    "repositories" : [
        {
            "type": "vcs",
            "url": "git@bitbucket.org:ricento/diglin_ricardo.git"
        }
    ]
 }
 ```

### Magento Composer Installer

 In your `composer.json` at the Magento project level, you will have to set the following informations:

 ```
 {
    "require" : {
        "magento-hackathon/magento-composer-installer" : "*",
        "diglin/ricardo": "1.*"
    },
    "repositories" : [
		{
            "type" : "composer",
            "url" : "http://packages.firegento.com"
        },
        {
            "type": "vcs",
            "url": "git@bitbucket.org:ricento/diglin_ricardo.git"
        }
    ],
    "extra" : {
        "magento-root-dir" : "./"
    },
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
    }
 }
 ```
 
 ## How to use it
 
 TODO

 ## Tests

 ### How to configure the test case

To configure the test, please create an ini file in `tests/conf/config.ini` with the following content:
Pay attention, you need two different configurations for each interface language. During the tests, only the German section is supported. So use only this one.


// Ricardo API Config for German version (for example)
```
[GERMAN]
host = ws.betaqxl.com
partnership_id = YOUR_PARTNER_ID
partnership_passwd = YOUR_PARTNER_PASS
partner_url = YOUR_WEBSITE_URL
allow_authorization_simulation = true
customer_username =
customer_password = ''
debug = true
display_test_content = true

[FRENCH]
host = ws.betaqxl.com
partnership_id = YOUR_PARTNER_ID
partnership_passwd = YOUR_PARTNER_PASS
partner_url = YOUR_WEBSITE_URL
allow_authorization_simulation = true
customer_username =
customer_password = ''
debug = true
display_test_content = true
```
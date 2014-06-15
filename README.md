### Install via Composer

```
 {
    "require" : {
        "diglin/ricardo": "*"
    },
    "repositories" : [
        {
            "type": "vcs",
            "url": "git@bitbucket.org:diglin/diglin_ricardo.git"
        }
    ]
 }
 ```

## Magento Composer Installer

 In your `composer.json` at Magento project level, you will have to set the following informations:

 ```
 {
    "require" : {
        "magento-hackathon/magento-composer-installer" : "*",
        "diglin/ricardo": "*"
    },
    "repositories" : [
		{
            "type" : "composer",
            "url" : "http://packages.firegento.com"
        },
        {
            "type": "vcs",
            "url": "git@bitbucket.org:diglin/diglin_ricardo.git"
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
        "post-package-uninstall": [
            "Diglin\\Ricardo\\Composer\\Magento::cleanPackageAction"
        ]
    }
 }
 ```
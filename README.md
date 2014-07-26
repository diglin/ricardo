# README #

This README would normally document whatever steps are necessary to get your application up and running.

### What is this repository for? ###

* Quick summary
* Version
* [Learn Markdown](https://bitbucket.org/tutorials/markdowndemo)

### How do I get set up? ###

* Summary of set up
* Configuration
* Dependencies
* Database configuration
* How to run tests
* Deployment instructions

### Contribution guidelines ###

* Writing tests
* Code review
* Other guidelines

### Who do I talk to? ###

* Repo owner or admin

### Installation

#### Via Composer

```
 {
    "require" : {
        "diglin/diglin_ricento": "1.*"
    },
    "repositories" : [
        {
            "type": "vcs",
            "url": "git@bitbucket.org:ricento/diglin_ricardo.git"
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
     }
 }
 ```

# Mage2 Module Hyper Auth

    ``hyper/module-auth``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)
 - [Attributes](#markdown-header-attributes)


## Main Functionalities
Hyper_Frontend Extension, To include template file into Head tag in magento2.

## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

 - Unzip the zip file in `app/code/Hyper`
 - Enable the module by running `php bin/magento module:enable Hyper_Auth`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer

 - Make the module available in a composer repository for example:
    - private repository `repo.magento.com`
    - public repository `packagist.org`
    - public github repository as vcs
 - Add the composer repository to the configuration by running `composer config repositories.repo.magento.com composer https://repo.magento.com/`
 - Install the module composer by running `composer require hyper/module-auth`
 - enable the module by running `php bin/magento module:enable Hyper_Auth`
 - apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`


## Configuration

 - is_enable (auth/general/is_enable)


## Specifications

 - Controller
	- frontend > hyper/auth/login

 - Helper
	- Hyper\Auth\Helper\Data


## Attributes




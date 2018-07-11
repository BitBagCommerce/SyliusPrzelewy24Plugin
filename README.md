<h1 align="center">
    <a href="http://bitbag.shop" target="_blank">
        <img src="https://raw.githubusercontent.com/bitbager/BitBagCommerceAssets/master/SyliusPrzelewy24PluginLogo.png" />
    </a>
    <br />
    <a href="https://packagist.org/packages/bitbag/przelewy24-plugin" title="License" target="_blank">
        <img src="https://img.shields.io/packagist/l/bitbag/przelewy24-plugin.svg" />
    </a>
    <a href="https://packagist.org/packages/bitbag/przelewy24-plugin" title="Version" target="_blank">
        <img src="https://img.shields.io/packagist/v/bitbag/przelewy24-plugin.svg" />
    </a>
    <a href="http://travis-ci.org/BitBagCommerce/SyliusPrzelewy24Plugin" title="Build status" target="_blank">
        <img src="https://img.shields.io/travis/BitBagCommerce/SyliusPrzelewy24Plugin/master.svg" />
    </a>
    <a href="https://scrutinizer-ci.com/g/BitBagCommerce/SyliusPrzelewy24Plugin/" title="Scrutinizer" target="_blank">
        <img src="https://img.shields.io/scrutinizer/g/BitBagCommerce/SyliusPrzelewy24Plugin.svg" />
    </a>
    <a href="https://packagist.org/packages/bitbag/przelewy24-plugin" title="Total Downloads" target="_blank">
        <img src="https://poser.pugx.org/bitbag/przelewy24-plugin/downloads" />
    </a>
</h1>

## Overview 

This plugin allows you to integrate Przelewy24 payments in your Sylius instance.

## Support

We work on amazing eCommerce projects on top of Sylius and Pimcore. Need some help or additional resources for a project?
Write us an email on mikolaj.krol@bitbag.pl or visit [our website](https://bitbag.shop/)! :rocket:

## Demo

We created a demo app with some useful use-cases of the plugin! Visit [demo.bitbag.shop](https://demo.bitbag.shop) to take a look at it. 
The admin can be accessed under [demo.bitbag.shop/admin](https://demo.bitbag.shop/admin) link and `sylius: sylius` credentials.

## Installation

```bash
$ composer require bitbag/przelewy24-plugin
```
    
Add plugin dependencies to your AppKernel.php file:
```php
public function registerBundles()
{
    return array_merge(parent::registerBundles(), [
        ...
        
        new \BitBag\SyliusPrzelewy24Plugin\SyliusPrzelewy24Plugin(),
    ]);
}
```


## Usage

### Running plugin tests

  - PHPSpec

    ```bash
    $ bin/phpspec run
    ```

  - Behat (non-JS scenarios)

    ```bash
    $ bin/behat --tags="~@javascript"
    ```

  - Behat (JS scenarios)
 
    1. Download [Chromedriver](https://sites.google.com/a/chromium.org/chromedriver/)
    
    2. Run Selenium server with previously downloaded Chromedriver:
    
        ```bash
        $ bin/selenium-server-standalone -Dwebdriver.chrome.driver=chromedriver
        ```
    3. Run test application's webserver on `localhost:8080`:
    
        ```bash
        $ (cd tests/Application && bin/console server:run 127.0.0.1:8080 -d web -e test)
        ```
    
    4. Run Behat:
    
        ```bash
        $ bin/behat --tags="@javascript"
        ```

### Opening Sylius with your plugin

- Using `test` environment:

    ```bash
    $ (cd tests/Application && bin/console sylius:fixtures:load -e test)
    $ (cd tests/Application && bin/console server:run -d web -e test)
    ```
    
- Using `dev` environment:

    ```bash
    $ (cd tests/Application && bin/console sylius:fixtures:load -e dev)
    $ (cd tests/Application && bin/console server:run -d web -e dev)
    ```

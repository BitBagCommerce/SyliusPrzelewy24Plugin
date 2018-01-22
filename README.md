<h1 align="center">
    <a href="http://bitbag.shop" target="_blank">
        <img src="https://raw.githubusercontent.com/bitbager/BitBagCommerceAssets/master/SyliusAdyenPlugin.png" />
    </a>
    <br />
    <a href="https://packagist.org/packages/bitbag/adyen-plugin" title="License" target="_blank">
        <img src="https://img.shields.io/packagist/l/bitbag/adyen-plugin.svg" />
    </a>
    <a href="https://packagist.org/packages/bitbag/adyen-plugin" title="Version" target="_blank">
        <img src="https://img.shields.io/packagist/v/bitbag/adyen-plugin.svg" />
    </a>
    <a href="http://travis-ci.org/BitBagCommerce/SyliusAdyenPlugin" title="Build status" target="_blank">
        <img src="https://img.shields.io/travis/BitBagCommerce/SyliusAdyenPlugin/master.svg" />
    </a>
    <a href="https://scrutinizer-ci.com/g/BitBagCommerce/SyliusAdyenPlugin/" title="Scrutinizer" target="_blank">
        <img src="https://img.shields.io/scrutinizer/g/BitBagCommerce/SyliusAdyenPlugin.svg" />
    </a>
    <a href="https://packagist.org/packages/bitbag/adyen-plugin" title="Total Downloads" target="_blank">
        <img src="https://poser.pugx.org/bitbag/adyen-plugin/downloads" />
    </a>
</h1>

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
    
## Support

Do you want us to customize this plugin for your specific needs? Write us an email on mikolaj.krol@bitbag.pl :computer:

# Pagar.me SDK

PHP integration for [Pagar.me  API](https://docs.pagar.me/api/)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/4c34cc13-e52f-492e-a2f2-dbcd398135a2/mini.png)](https://insight.sensiolabs.com/projects/4c34cc13-e52f-492e-a2f2-dbcd398135a2)
[![Coverage Status](https://coveralls.io/repos/github/pagarme/pagarme-php/badge.svg?branch=V3)](https://coveralls.io/github/pagarme/pagarme-php?branch=V3)

## Installation
Via Composer
```sh
composer require 'pagarme/pagarme-php'
```

## Usage
### Basic
First you need to create an PagarMe object with your API-KEY (Avaliable on your [dashboard](https://dashboard.pagar.me/#/myaccount/apikeys))
```php
$apiKey = 'ak_test_grXijQ4GicOa2BLGZrDRTR5qNQxJW0';
$pagarMe =  new \PagarMe\Sdk\PagarMe($apiKey);
```
### Wiki
Check the [wiki](https://github.com/pagarme/pagarme-php/wiki) for detailed documentation.

### Contributing

**Also** checkout our [contributing guide](CONTRIBUTING.md) before you send us any contribution.

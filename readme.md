Laravel - USAePay
========

[![Latest Stable Version](https://poser.pugx.org/jundelleb/laravel-usaepay/v/stable)](https://packagist.org/packages/jundelleb/laravel-usaepay) [![Total Downloads](https://poser.pugx.org/jundelleb/laravel-usaepay/downloads)](https://packagist.org/packages/jundelleb/laravel-usaepay) [![Latest Unstable Version](https://poser.pugx.org/jundelleb/laravel-usaepay/v/unstable)](https://packagist.org/packages/jundelleb/laravel-usaepay) [![License](https://poser.pugx.org/jundelleb/laravel-usaepay/license)](https://packagist.org/packages/jundelleb/laravel-usaepay)

Installation
------------

Install using composer:

    composer require jundelleb/laravel-usaepay

Add the service provider in `app/config/app.php`:

    PhpUsaepay\ServiceProvider::class,

Configuration
-------------

Now publish the configuration files to config/usaepay.php:

    $ php artisan vendor:publish

This package supports configuration through the services configuration file located in `config/usaepay.php`:


Usage
-----

Basic Usage of USAePay:

```php
<?php

$sourcekey = 'your_source_key';
$sourcepin = 'your_source_pin';
$sandbox = true;
$options = [
    'debug' => true,
];

$usaepay = new \PhpUsaepay\Client($sourcekey, $sourcepin, $sandbox, $options);

```

Example
-------

This package takes care of the creation of `ueSecurityToken`.

#### Find CustNum using `searchCustomerID` method

```php
<?php

$custID = '21021';

$custNum = $usaepay->searchCustomerID($custID);

```
Reference: https://wiki.usaepay.com/developer/soap-1.6/methods/searchcustomerid

#### Run sale using `runTransaction` method

```php
<?php

$request = [
    'Command' => 'sale',
    'AccountHolder' => 'John Doe',
    'Details' => [
      'Description' => 'Example Transaction',
      'Amount' => '4.00',
      'Invoice' => '44539'
    ],
    'CreditCardData' => [
      'CardNumber' => '4444555566667779',
      'CardExpiration' => '0919',
      'AvsStreet' => '1234 Main Street',
      'AvsZip' => '99281',
      'CardCode' => '999'
    ]
];

$result = $usaepay->runTransaction($request);

```
Reference: https://wiki.usaepay.com/developer/soap-1.6/methods/runtransaction

Refer to this link for the complete lists of methods:
http://wiki.usaepay.com/developer/soap-1.6/Support#methods

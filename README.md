# php-appboy

A PHP client for sending push notifications via the [Appboy](https://www.appboy.com/documentation/REST_API) API

[![Author](http://img.shields.io/badge/author-@superbalist-blue.svg?style=flat-square)](https://twitter.com/superbalist)
[![Build Status](https://img.shields.io/travis/Superbalist/php-appboy/master.svg?style=flat-square)](https://travis-ci.org/Superbalist/php-appboy)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Packagist Version](https://img.shields.io/packagist/v/superbalist/php-appboy.svg?style=flat-square)](https://packagist.org/packages/superbalist/php-appboy)
[![Total Downloads](https://img.shields.io/packagist/dt/superbalist/php-appboy.svg?style=flat-square)](https://packagist.org/packages/superbalist/php-appboy)


## Installation

```bash
composer require superbalist/php-appboy
```

## Usage

```php
$client = new \GuzzleHttp\Client();
$appboy = new \Superbalist\Appboy\Appboy($client, 'your-app-group-id');

// send a push message
$appboy->sendMessage(
    (new NotificationBuilder())
        ->toUsers([1, 2])
        ->setCampaign('my_campaign')
        ->ignoreFrequencyCapping()
        ->setSubscriptionState('opted_in')
        ->withMessage(
            (new AppleMessageBuilder())
                ->setAlert('Hello World!')
                ->setSound('custom_sound')
                ->withExtraAttributes(['is_test' => true])
                ->setCategory('shipping_notification')
                ->setExpiryDate(new \DateTime('2017-05-29 10:00:00', new \DateTimeZone('Africa/Johannesburg')))
                ->setUri('http://superbalist.com')
                ->setMessageVariation('group_a')
                ->setAsset('file://image.jpg', 'jpg')
                ->build()
        )
    ->build()
);

```

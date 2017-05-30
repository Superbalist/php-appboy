<?php

// usage:
// APPBOY_APP_GROUP_ID='your app group id' php examples/SendPush.php

include __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;
use Superbalist\Appboy\Appboy;
use Superbalist\Appboy\NotificationBuilder;
use Superbalist\Appboy\Messages\AndroidMessageBuilder;
use Superbalist\Appboy\Messages\AppleMessageBuilder;

$client = new Client();
$appboy = new Appboy($client, getenv('APPBOY_APP_GROUP_ID'));

// send a push message
$response = $appboy->sendMessage(
    (new NotificationBuilder())
        ->toUser(2)
        ->withMessages([
            'apple_push' => (new AppleMessageBuilder())
                ->setAlert('This is a test message')
                ->withExtraAttributes(['is_test' => true])
                ->setCategory('matthew_test')
                ->expiresAt(new \DateTime('+1 hour', new \DateTimeZone('Africa/Johannesburg')))
                ->setUri('http://superbalist.com')
                ->build(),
            'android_push' => (new AndroidMessageBuilder())
                ->setAlert('This is a test message')
                ->setTitle('Message Title')
                ->withExtraAttributes(['is_test' => true])
                ->setUri('http://superbalist.com')
                ->setSummaryText('This is a summary line')
                ->build(),
        ])
        ->build()
);
var_dump($response);
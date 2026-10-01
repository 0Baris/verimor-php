<?php

declare(strict_types=1);

use BarisCemant\Verimor\Sms\Config\SmsConfig;
use BarisCemant\Verimor\Sms\SmsClient;

require dirname(__DIR__, 2) . '/vendor/autoload.php';

$client = new SmsClient(new SmsConfig(
    (string) getenv('VERIMOR_SMS_USERNAME'),
    (string) getenv('VERIMOR_SMS_PASSWORD'),
    getenv('VERIMOR_SMS_SOURCE_ADDR') ?: 'VERIMOR'
));

$campaignId = $client->send([
    'messages' => [[
        'msg' => 'Merhaba!',
        'dest' => (string) getenv('VERIMOR_TEST_DESTINATION'),
    ]],
    'custom_id' => 'ornek-' . date('YmdHis'),
]);

echo $campaignId . PHP_EOL;

<?php

declare(strict_types=1);

use BarisCemant\Verimor\Sms\Config\SmsConfig;
use BarisCemant\Verimor\Sms\SmsClient;

require dirname(__DIR__, 2) . '/vendor/autoload.php';

$client = new SmsClient(new SmsConfig(
    (string) getenv('VERIMOR_SMS_USERNAME'),
    (string) getenv('VERIMOR_SMS_PASSWORD')
));

$client->balance();
echo "Bakiye isteği tamamlandı.\n";

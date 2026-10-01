<?php

declare(strict_types=1);

use BarisCemant\Verimor\Sms\Config\SmsConfig;
use BarisCemant\Verimor\Sms\SmsClient;

require dirname(__DIR__, 2) . '/vendor/autoload.php';

$client = new SmsClient(new SmsConfig(
    (string) getenv('VERIMOR_SMS_USERNAME'),
    (string) getenv('VERIMOR_SMS_PASSWORD')
));

$campaignId = (int) getenv('VERIMOR_SMS_CAMPAIGN_ID');
$result = $client->statusById($campaignId);
// Özel ID kullandıysanız: $client->statusByCustomId((string) getenv('VERIMOR_SMS_CUSTOM_ID'));
var_export($result);

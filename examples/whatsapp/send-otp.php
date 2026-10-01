<?php

declare(strict_types=1);

use BarisCemant\Verimor\WhatsApp\Config\WhatsAppConfig;
use BarisCemant\Verimor\WhatsApp\Dto\SendOtpRequest;
use BarisCemant\Verimor\WhatsApp\WhatsAppClient;

require dirname(__DIR__, 2) . '/vendor/autoload.php';

$client = new WhatsAppClient(new WhatsAppConfig((string) getenv('VERIMOR_WHATSAPP_API_KEY')));
$request = SendOtpRequest::fromArray([
    'templateName' => (string) getenv('VERIMOR_WHATSAPP_OTP_TEMPLATE'),
    'to' => (string) getenv('VERIMOR_TEST_DESTINATION'),
    'language' => 'tr',
    'parameters' => [(string) getenv('VERIMOR_TEST_OTP')],
]);

echo $client->sendOtp($request)->getId() . PHP_EOL;

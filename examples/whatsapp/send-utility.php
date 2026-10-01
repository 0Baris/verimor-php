<?php

declare(strict_types=1);

use BarisCemant\Verimor\WhatsApp\Config\WhatsAppConfig;
use BarisCemant\Verimor\WhatsApp\Dto\SendUtilityRequest;
use BarisCemant\Verimor\WhatsApp\WhatsAppClient;

require dirname(__DIR__, 2) . '/vendor/autoload.php';

$client = new WhatsAppClient(new WhatsAppConfig((string) getenv('VERIMOR_WHATSAPP_API_KEY')));
$request = SendUtilityRequest::fromArray([
    'templateName' => (string) getenv('VERIMOR_WHATSAPP_UTILITY_TEMPLATE'),
    'to' => (string) getenv('VERIMOR_TEST_DESTINATION'),
    'language' => 'tr',
    'parameters' => [(string) getenv('VERIMOR_TEST_PARAMETER')],
]);

echo $client->sendUtility($request)->getId() . PHP_EOL;

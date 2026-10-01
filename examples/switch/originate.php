<?php

declare(strict_types=1);

use BarisCemant\Verimor\SwitchApi\Config\SwitchConfig;
use BarisCemant\Verimor\SwitchApi\Dto\OriginateRequest;
use BarisCemant\Verimor\SwitchApi\SwitchClient;

require dirname(__DIR__, 2) . '/vendor/autoload.php';

$client = new SwitchClient(new SwitchConfig((string) getenv('VERIMOR_SWITCH_API_KEY')));
$request = new OriginateRequest(
    (string) getenv('VERIMOR_SWITCH_DESTINATION'),
    (string) getenv('VERIMOR_SWITCH_EXTENSION')
);

echo $client->originate($request) . PHP_EOL;

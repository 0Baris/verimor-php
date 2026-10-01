<?php

declare(strict_types=1);

namespace BarisCemant\Verimor\Http;

use BarisCemant\Verimor\Config\ProductConfig;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

final class ClientFactory
{
    public static function create(ProductConfig $config): ClientInterface
    {
        return new Client([
            'base_uri' => $config->baseUrl(),
            'timeout' => $config->timeout(),
            'connect_timeout' => $config->timeout(),
            'http_errors' => false,
        ]);
    }
}

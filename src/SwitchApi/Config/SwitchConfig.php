<?php

declare(strict_types=1);

namespace BarisCemant\Verimor\SwitchApi\Config;

use BarisCemant\Verimor\Config\ProductConfig;

final class SwitchConfig extends ProductConfig
{
    private const DEFAULT_BASE_URL = 'https://api.bulutsantralim.com';

    /** @var string */
    private $apiKey;

    public function __construct(string $apiKey, ?string $baseUrl = null, float $timeout = 30.0)
    {
        parent::__construct($baseUrl ?? self::DEFAULT_BASE_URL, $timeout);
        $this->apiKey = self::requireCredential($apiKey, 'apiKey');
    }

    public function apiKey(): string
    {
        return $this->apiKey;
    }
}

<?php

declare(strict_types=1);

namespace BarisCemant\Verimor\WhatsApp\Config;

use BarisCemant\Verimor\Config\ProductConfig;

final class WhatsAppConfig extends ProductConfig
{
    private const DEFAULT_BASE_URL = 'https://wapi.verimor.com.tr';

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

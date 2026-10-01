<?php

declare(strict_types=1);

namespace BarisCemant\Verimor\Sms\Config;

use BarisCemant\Verimor\Config\ProductConfig;

final class SmsConfig extends ProductConfig
{
    private const DEFAULT_BASE_URL = 'https://sms.verimor.com.tr';

    /** @var string */
    private $username;
    /** @var string */
    private $password;
    /** @var string|null */
    private $sourceAddr;

    public function __construct(
        string $username,
        string $password,
        ?string $sourceAddr = null,
        ?string $baseUrl = null,
        float $timeout = 30.0
    ) {
        parent::__construct($baseUrl ?? self::DEFAULT_BASE_URL, $timeout);
        $this->username = self::requireCredential($username, 'username');
        $this->password = self::requireCredential($password, 'password');
        $this->sourceAddr = $sourceAddr;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function sourceAddr(): ?string
    {
        return $this->sourceAddr;
    }
}

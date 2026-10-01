<?php

declare(strict_types=1);

namespace BarisCemant\Verimor\Config;

use InvalidArgumentException;

abstract class ProductConfig
{
    /** @var string */
    private $baseUrl;
    /** @var float */
    private $timeout;

    protected function __construct(string $baseUrl, float $timeout)
    {
        $scheme = parse_url($baseUrl, PHP_URL_SCHEME);
        if (!filter_var($baseUrl, FILTER_VALIDATE_URL) || !in_array($scheme, ['http', 'https'], true)) {
            throw new InvalidArgumentException('baseUrl must be an absolute HTTP(S) URL');
        }
        if ($timeout <= 0.0) {
            throw new InvalidArgumentException('timeout must be greater than zero');
        }
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->timeout = $timeout;
    }

    final public function baseUrl(): string
    {
        return $this->baseUrl;
    }

    final public function timeout(): float
    {
        return $this->timeout;
    }

    final protected static function requireCredential(string $value, string $name): string
    {
        if ($value === '') {
            throw new InvalidArgumentException($name . ' must not be empty');
        }
        return $value;
    }
}

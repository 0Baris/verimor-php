<?php

declare(strict_types=1);

namespace BarisCemant\Verimor\Http;

use BarisCemant\Verimor\Exception\VerimorApiException;
use Throwable;

final class ErrorMapper
{
    public static function map(string $product, string $operationId, Throwable $error): Throwable
    {
        if (!method_exists($error, 'getResponseBody') || !method_exists($error, 'getResponseHeaders')) {
            return $error;
        }

        /** @var array<string, string[]>|null $headers */
        $headers = $error->getResponseHeaders();
        $contentType = self::contentType($headers ?? []);
        $body = self::normalizeBody($error->getResponseBody(), $contentType);

        return new VerimorApiException($product, (int) $error->getCode(), $body, $operationId);
    }

    /** @param array<string, string[]> $headers */
    private static function contentType(array $headers): ?string
    {
        foreach ($headers as $name => $values) {
            if (strtolower($name) === 'content-type') {
                return strtolower(implode(', ', $values));
            }
        }
        return null;
    }

    /**
     * @param mixed $body
     * @return array<mixed>|string|null
     */
    private static function normalizeBody($body, ?string $contentType)
    {
        if ($body === null) {
            return null;
        }
        if (is_array($body)) {
            return $body;
        }
        if (is_object($body)) {
            return json_decode((string) json_encode($body), true);
        }
        $text = (string) $body;
        $trimmed = trim($text);
        if ($trimmed === '') {
            return null;
        }
        $first = substr($trimmed, 0, 1);
        $looksJson = $first === '{' || $first === '[';
        if (($contentType !== null && strpos($contentType, 'json') !== false) || $looksJson) {
            $decoded = json_decode($trimmed, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
        }
        return $text;
    }
}

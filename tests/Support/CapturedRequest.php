<?php

declare(strict_types=1);

namespace BarisCemant\Verimor\Tests\Support;

final class CapturedRequest
{
    /** @var string */ public $method;
    /** @var string */ public $path;
    /** @var array<string, string> */ public $query;
    /** @var array<string, string> */ public $headers;
    /** @var string */ public $body;

    /** @param array<string, mixed> $value */
    public static function fromArray(array $value): self
    {
        $request = new self();
        $request->method = (string) $value['method'];
        $request->path = (string) $value['path'];
        $request->query = is_array($value['query']) ? $value['query'] : [];
        $request->headers = is_array($value['headers']) ? $value['headers'] : [];
        $request->body = (string) $value['body'];
        return $request;
    }

    public function header(string $name): string
    {
        foreach ($this->headers as $header => $value) {
            if (strtolower($header) === strtolower($name)) {
                return $value;
            }
        }
        return '';
    }
}

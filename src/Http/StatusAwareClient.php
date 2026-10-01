<?php

declare(strict_types=1);

namespace BarisCemant\Verimor\Http;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class StatusAwareClient implements ClientInterface
{
    /** @var ClientInterface */
    private $inner;

    public function __construct(ClientInterface $inner)
    {
        $this->inner = $inner;
    }

    /** @param array<string, mixed> $options */
    public function send(RequestInterface $request, array $options = []): ResponseInterface
    {
        $options['http_errors'] = true;
        return $this->inner->send($request, $options);
    }

    /** @param array<string, mixed> $options */
    public function sendAsync(RequestInterface $request, array $options = []): PromiseInterface
    {
        $options['http_errors'] = true;
        return $this->inner->sendAsync($request, $options);
    }

    /** @param array<string, mixed> $options */
    public function request(string $method, $uri, array $options = []): ResponseInterface
    {
        $options['http_errors'] = true;
        return $this->inner->request($method, $uri, $options);
    }

    /** @param array<string, mixed> $options */
    public function requestAsync(string $method, $uri, array $options = []): PromiseInterface
    {
        $options['http_errors'] = true;
        return $this->inner->requestAsync($method, $uri, $options);
    }

    public function getConfig(?string $option = null)
    {
        return $this->inner->getConfig($option);
    }
}

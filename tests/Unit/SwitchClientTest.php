<?php

declare(strict_types=1);

namespace BarisCemant\Verimor\Tests\Unit;

use BarisCemant\Verimor\SwitchApi\Config\SwitchConfig;
use BarisCemant\Verimor\SwitchApi\Dto\OriginateRequest;
use BarisCemant\Verimor\SwitchApi\SwitchClient;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class SwitchClientTest extends TestCase
{
    public function testOriginateDelegatesOnceWithApiKey(): void
    {
        $history = [];
        $stack = HandlerStack::create(new MockHandler([new Response(200, [], 'ok')]));
        $stack->push(Middleware::history($history));
        $client = new Client(['handler' => $stack]);
        $switch = new SwitchClient(new SwitchConfig('switch-key'), $client);
        $request = OriginateRequest::fromArray(['extension' => '101', 'destination' => '905000000000']);

        self::assertSame('ok', $switch->originate($request));
        self::assertCount(1, $history);
        parse_str($history[0]['request']->getUri()->getQuery(), $query);
        self::assertSame('switch-key', $query['key']);
        self::assertSame('101', json_decode((string) $history[0]['request']->getBody(), true)['extension']);
    }
}

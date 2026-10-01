<?php

declare(strict_types=1);

namespace BarisCemant\Verimor\Tests\Unit;

use BarisCemant\Verimor\Sms\Config\SmsConfig;
use BarisCemant\Verimor\Sms\Dto\SendRequest;
use BarisCemant\Verimor\Sms\SmsClient;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class SmsClientTest extends TestCase
{
    public function testSendUsesOverrideThenDefaultWithoutMutatingInputs(): void
    {
        $history = [];
        $client = $this->mockClient([new Response(200, [], 'first'), new Response(200, [], 'second')], $history);
        $sms = new SmsClient(new SmsConfig('user', 'pass', 'DEFAULT'), $client);
        $input = [
            'source_addr' => 'OVERRIDE',
            'messages' => [['msg' => 'Merhaba', 'dest' => '905000000000']],
        ];
        $original = $input;

        self::assertSame('first', $sms->send($input));
        self::assertSame($original, $input);
        $request = SendRequest::fromArray([
            'messages' => [['msg' => 'İkinci', 'dest' => '905000000001']],
        ]);
        $before = $request->toArray();
        self::assertSame('second', $sms->sendRequest($request));
        self::assertSame($before, $request->toArray());

        $first = json_decode((string) $history[0]['request']->getBody(), true);
        $second = json_decode((string) $history[1]['request']->getBody(), true);
        self::assertSame('OVERRIDE', $first['source_addr']);
        self::assertSame('DEFAULT', $second['source_addr']);
        self::assertSame('user', $first['username']);
        self::assertSame('pass', $first['password']);
    }

    public function testBalanceAndStatusConveniencesMapCredentialsAndSelectors(): void
    {
        $history = [];
        $client = $this->mockClient([
            new Response(200, [], '10'),
            new Response(200, ['Content-Type' => 'application/json'], '[]'),
            new Response(200, ['Content-Type' => 'application/json'], '[]'),
        ], $history);
        $sms = new SmsClient(new SmsConfig('u+ser', 'p&ss'), $client);

        $sms->balance();
        $sms->statusById(12345);
        $sms->statusByCustomId('siparis-42');

        parse_str($history[0]['request']->getUri()->getQuery(), $balanceQuery);
        parse_str($history[1]['request']->getUri()->getQuery(), $idQuery);
        parse_str($history[2]['request']->getUri()->getQuery(), $customQuery);
        self::assertSame(['username' => 'u+ser', 'password' => 'p&ss'], $balanceQuery);
        self::assertSame('12345', $idQuery['id']);
        self::assertArrayNotHasKey('custom_id', $idQuery);
        self::assertSame('siparis-42', $customQuery['custom_id']);
        self::assertArrayNotHasKey('id', $customQuery);
    }

    /**
     * @param Response[] $responses
     * @param array<int, array<string, mixed>> $history
     */
    private function mockClient(array $responses, array &$history): Client
    {
        $stack = HandlerStack::create(new MockHandler($responses));
        $stack->push(Middleware::history($history));
        return new Client(['handler' => $stack]);
    }
}

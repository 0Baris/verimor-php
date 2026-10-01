<?php

declare(strict_types=1);

namespace BarisCemant\Verimor\Tests\Integration;

use BarisCemant\Verimor\Sms\Config\SmsConfig;
use BarisCemant\Verimor\Sms\SmsClient;
use BarisCemant\Verimor\Tests\Support\LocalHttpServer;
use PHPUnit\Framework\TestCase;

final class IsolationTest extends TestCase
{
    public function testSmsClientCredentialsRemainIsolated(): void
    {
        $server = new LocalHttpServer();
        try {
            $first = new SmsClient(new SmsConfig('first', 'one', null, $server->url()));
            $second = new SmsClient(new SmsConfig('second', 'two', null, $server->url()));
            $first->balance();
            $second->balance();
            $first->balance();
            $requests = $server->captured();
            self::assertSame('first', $requests[0]->query['username']);
            self::assertSame('second', $requests[1]->query['username']);
            self::assertSame('first', $requests[2]->query['username']);
        } finally {
            $server->stop();
        }
    }
}

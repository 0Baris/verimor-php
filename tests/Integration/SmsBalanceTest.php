<?php

declare(strict_types=1);

namespace BarisCemant\Verimor\Tests\Integration;

use BarisCemant\Verimor\Sms\Config\SmsConfig;
use BarisCemant\Verimor\Sms\SmsClient;
use BarisCemant\Verimor\Tests\Support\LocalHttpServer;
use PHPUnit\Framework\TestCase;

final class SmsBalanceTest extends TestCase
{
    public function testBalanceReturnsTheTextTheApiSends(): void
    {
        $server = new LocalHttpServer();
        try {
            $server->respond(200, "42.50\n", 'text/plain');
            $sms = new SmsClient(new SmsConfig('user', 'pass', null, $server->url()));

            self::assertSame("42.50\n", $sms->balance());
            self::assertSame("42.50\n", $sms->balances()->balance());
        } finally {
            $server->stop();
        }
    }

    public function testACustomServerUrlKeepsItsPathPrefix(): void
    {
        $server = new LocalHttpServer();
        try {
            $server->respond(200, '1', 'text/plain');
            $sms = new SmsClient(new SmsConfig('user', 'pass', null, $server->url() . '/verimor/'));
            $sms->balance();

            self::assertSame('/verimor/v2/balance', $server->captured()[0]->path);
        } finally {
            $server->stop();
        }
    }
}

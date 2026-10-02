<?php

declare(strict_types=1);

namespace BarisCemant\Verimor\Tests\Integration;

use BarisCemant\Verimor\Sms\Config\SmsConfig;
use BarisCemant\Verimor\Sms\Dto\SubmitIysConsentsRequest;
use BarisCemant\Verimor\Sms\SmsClient;
use BarisCemant\Verimor\Tests\Support\LocalHttpServer;
use PHPUnit\Framework\TestCase;

final class SmsDefaultSenderTest extends TestCase
{
    public function testIysConsentsUseTheConfiguredSenderWhenTheRequestOmitsIt(): void
    {
        $server = new LocalHttpServer();
        try {
            $server->respond(200, '1', 'text/plain');
            $sms = new SmsClient(new SmsConfig('user', 'pass', 'VERIMOR', $server->url()));
            $sms->iys()->submitIysConsents(SubmitIysConsentsRequest::fromArray([
                'consents' => [['type' => 'MESAJ', 'recipient' => '905001112233']],
            ]));

            $body = json_decode($server->captured()[0]->body, true);
            self::assertSame('VERIMOR', $body['source_addr']);
        } finally {
            $server->stop();
        }
    }

    public function testIysConsentsWithoutAnySenderFailBeforeSending(): void
    {
        $server = new LocalHttpServer();
        try {
            $sms = new SmsClient(new SmsConfig('user', 'pass', null, $server->url()));

            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage('sourceAddr');
            try {
                $sms->iys()->submitIysConsents(SubmitIysConsentsRequest::fromArray([
                    'consents' => [['type' => 'MESAJ', 'recipient' => '905001112233']],
                ]));
            } finally {
                self::assertSame([], $server->captured());
            }
        } finally {
            $server->stop();
        }
    }
}

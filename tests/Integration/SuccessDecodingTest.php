<?php

declare(strict_types=1);

namespace BarisCemant\Verimor\Tests\Integration;

use BarisCemant\Verimor\Exception\UnexpectedResponseException;
use BarisCemant\Verimor\Sms\Config\SmsConfig;
use BarisCemant\Verimor\Sms\SmsClient;
use BarisCemant\Verimor\Tests\Support\LocalHttpServer;
use BarisCemant\Verimor\WhatsApp\Config\WhatsAppConfig;
use BarisCemant\Verimor\WhatsApp\Dto\SendOtpRequest;
use BarisCemant\Verimor\WhatsApp\Generated\Model\MessageResponse;
use BarisCemant\Verimor\WhatsApp\WhatsAppClient;
use PHPUnit\Framework\TestCase;

final class SuccessDecodingTest extends TestCase
{
    public function testTextEmptyAndDocumented202Successes(): void
    {
        $server = new LocalHttpServer();
        try {
            $server->respond(200, 'campaign-42', 'text/plain');
            $sms = new SmsClient(new SmsConfig('user', 'pass', null, $server->url()));
            self::assertSame('campaign-42', $sms->send([
                'messages' => [['msg' => 'Merhaba', 'dest' => '905000000000']],
            ]));
            $server->respond(200, '', null);
            $sms->balance();
            self::assertCount(2, $server->captured());
            $server->respond(
                202,
                '{"id":"00000000-0000-4000-8000-000000000001","status":"accepted"}',
                'application/json'
            );
            $result = (new WhatsAppClient(new WhatsAppConfig('key', $server->url())))->sendOtp(
                new SendOtpRequest('905000000000', 'otp')
            );
            self::assertInstanceOf(MessageResponse::class, $result);
            self::assertSame('accepted', $result->getStatus());
        } finally {
            $server->stop();
        }
    }

    /** @dataProvider invalidWhatsAppProvider */
    public function testInvalidWhatsAppSuccessIsRejected(string $body): void
    {
        $server = new LocalHttpServer();
        try {
            $server->respond(202, $body, 'application/json');
            $this->expectException(UnexpectedResponseException::class);
            (new WhatsAppClient(new WhatsAppConfig('key', $server->url())))->sendOtp(
                new SendOtpRequest('905000000000', 'otp')
            );
        } finally {
            $server->stop();
        }
    }

    /** @return array<string, array{string}> */
    public function invalidWhatsAppProvider(): array
    {
        return [
            'malformed JSON' => ['{"id":'],
            'wrong container' => ['[]'],
            'missing required fields' => ['{}'],
        ];
    }
}

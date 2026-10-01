<?php

declare(strict_types=1);

namespace BarisCemant\Verimor\Tests\Integration;

use BarisCemant\Verimor\Exception\VerimorApiException;
use BarisCemant\Verimor\Sms\Config\SmsConfig;
use BarisCemant\Verimor\Sms\SmsClient;
use BarisCemant\Verimor\SwitchApi\Config\SwitchConfig;
use BarisCemant\Verimor\SwitchApi\Dto\OriginateRequest;
use BarisCemant\Verimor\SwitchApi\SwitchClient;
use BarisCemant\Verimor\Tests\Support\LocalHttpServer;
use BarisCemant\Verimor\WhatsApp\Config\WhatsAppConfig;
use BarisCemant\Verimor\WhatsApp\Dto\SendOtpRequest;
use BarisCemant\Verimor\WhatsApp\WhatsAppClient;
use PHPUnit\Framework\TestCase;

final class HttpErrorTest extends TestCase
{
    /**
     * @dataProvider errorProvider
     * @param array<mixed>|string|null $expectedBody
     */
    public function testHttpErrorsAreNormalized(
        string $product,
        int $status,
        string $body,
        ?string $contentType,
        $expectedBody
    ): void {
        $server = new LocalHttpServer();
        try {
            $server->respond($status, $body, $contentType);
            try {
                $this->invoke($product, $server->url());
                self::fail('Expected VerimorApiException');
            } catch (VerimorApiException $error) {
                self::assertSame($product, $error->product());
                self::assertSame($status, $error->statusCode());
                self::assertSame($expectedBody, $error->body());
                self::assertSame($this->operationId($product), $error->operationId());
            }
            self::assertCount(1, $server->captured());
        } finally {
            $server->stop();
        }
    }

    /** @return array<string, array{string, int, string, string|null, array<mixed>|string|null}> */
    public function errorProvider(): array
    {
        $cases = [];
        $bodies = [
            'json' => ['{"message":"invalid"}', 'application/json', ['message' => 'invalid']],
            'text' => ['upstream failed', 'text/plain', 'upstream failed'],
            'empty' => ['', null, null],
            'malformed' => ['{"message":', 'application/json', '{"message":'],
        ];
        foreach (['sms', 'switch', 'whatsapp'] as $product) {
            foreach ([400, 401, 403, 404, 429, 500, 503] as $status) {
                foreach ($bodies as $name => [$body, $contentType, $expected]) {
                    $cases[$product . '-' . $status . '-' . $name] = [
                        $product, $status, $body, $contentType, $expected,
                    ];
                }
            }
        }
        return $cases;
    }

    private function invoke(string $product, string $url): void
    {
        if ($product === 'sms') {
            (new SmsClient(new SmsConfig('user', 'pass', null, $url)))->balance();
            return;
        }
        if ($product === 'switch') {
            (new SwitchClient(new SwitchConfig('key', $url)))->originate(
                new OriginateRequest('905000000000', '101')
            );
            return;
        }
        (new WhatsAppClient(new WhatsAppConfig('key', $url)))->sendOtp(
            new SendOtpRequest('905000000000', 'otp')
        );
    }

    private function operationId(string $product): string
    {
        return [
            'sms' => 'get_v2_balance',
            'switch' => 'originateCallPost',
            'whatsapp' => 'send_otp_v1_messages_otp_post',
        ][$product];
    }
}

<?php

declare(strict_types=1);

namespace BarisCemant\Verimor\Tests\Integration;

use BarisCemant\Verimor\Exception\VerimorApiException;
use BarisCemant\Verimor\Sms\Config\SmsConfig;
use BarisCemant\Verimor\Sms\SmsClient;
use BarisCemant\Verimor\Tests\Support\LocalHttpServer;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;
use Throwable;

final class NetworkFailureTest extends TestCase
{
    public function testConnectionRefusalRemainsNativeGuzzleError(): void
    {
        $server = new LocalHttpServer();
        $url = $server->url();
        $server->stop();

        try {
            (new SmsClient(new SmsConfig('user', 'pass', null, $url, 0.1)))->balance();
            self::fail('Expected native network error');
        } catch (Throwable $error) {
            self::assertInstanceOf(GuzzleException::class, $error);
            self::assertNotInstanceOf(VerimorApiException::class, $error);
        }
    }

    /** @dataProvider noRetryProvider */
    public function testFailuresAreNeverRetried(int $status, int $delayMilliseconds): void
    {
        $server = new LocalHttpServer();
        try {
            $server->respond($status, 'failed', 'text/plain', $delayMilliseconds);
            try {
                (new SmsClient(new SmsConfig('user', 'pass', null, $server->url(), 0.05)))->balance();
            } catch (Throwable $error) {
                self::assertTrue(
                    $error instanceof GuzzleException || $error instanceof VerimorApiException
                );
            }
            self::assertCount(1, $server->captured());
        } finally {
            $server->stop();
        }
    }

    /** @return array<string, array{int, int}> */
    public function noRetryProvider(): array
    {
        return [
            'status-429' => [429, 0],
            'status-500' => [500, 0],
            'timeout' => [200, 200],
        ];
    }
}

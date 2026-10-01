<?php

declare(strict_types=1);

namespace BarisCemant\Verimor\Tests\Contract;

use BarisCemant\Verimor\Sms\SmsClient;
use BarisCemant\Verimor\SwitchApi\SwitchClient;
use BarisCemant\Verimor\WhatsApp\WhatsAppClient;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

final class PublicSurfaceTest extends TestCase
{
    /**
     * @dataProvider methodProvider
     * @param class-string $class
     */
    public function testPublicMethodsDeclareReturnContracts(string $class, string $method): void
    {
        $reflection = new ReflectionClass($class);
        $publicMethod = $reflection->getMethod($method);
        $doc = $publicMethod->getDocComment() ?: '';

        self::assertTrue(
            $publicMethod->hasReturnType() || strpos($doc, '@return') !== false,
            $class . '::' . $method
        );
    }

    /** @return array<int, array{class-string, string}> */
    public function methodProvider(): array
    {
        return [
            [SmsClient::class, 'campaigns'],
            [SmsClient::class, 'balances'],
            [SmsClient::class, 'send'],
            [SmsClient::class, 'sendRequest'],
            [SmsClient::class, 'balance'],
            [SmsClient::class, 'statusById'],
            [SmsClient::class, 'statusByCustomId'],
            [SmsClient::class, 'raw'],
            [SwitchClient::class, 'calls'],
            [SwitchClient::class, 'contacts'],
            [SwitchClient::class, 'originate'],
            [SwitchClient::class, 'raw'],
            [WhatsAppClient::class, 'sendOtp'],
            [WhatsAppClient::class, 'sendUtility'],
            [WhatsAppClient::class, 'raw'],
        ];
    }
}

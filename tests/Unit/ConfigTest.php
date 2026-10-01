<?php

declare(strict_types=1);

namespace BarisCemant\Verimor\Tests\Unit;

use BarisCemant\Verimor\Sms\Config\SmsConfig;
use BarisCemant\Verimor\SwitchApi\Config\SwitchConfig;
use BarisCemant\Verimor\WhatsApp\Config\WhatsAppConfig;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

final class ConfigTest extends TestCase
{
    public function testProductConfigurationsExposeValidatedValues(): void
    {
        $sms = new SmsConfig('user', 'pass', 'VERIMOR', 'http://127.0.0.1:8080', 30.0);
        self::assertSame('user', $sms->username());
        self::assertSame('pass', $sms->password());
        self::assertSame('VERIMOR', $sms->sourceAddr());
        self::assertSame('http://127.0.0.1:8080', $sms->baseUrl());
        self::assertSame(30.0, $sms->timeout());

        $switch = new SwitchConfig('switch-key', 'http://127.0.0.1:8080', 12.5);
        self::assertSame('switch-key', $switch->apiKey());
        self::assertSame(12.5, $switch->timeout());

        $whatsapp = new WhatsAppConfig('wa-key', 'http://127.0.0.1:8080', 8.0);
        self::assertSame('wa-key', $whatsapp->apiKey());
        self::assertSame(8.0, $whatsapp->timeout());
    }

    /** @dataProvider invalidConfigProvider */
    public function testInvalidConfigurationIsRejected(callable $factory): void
    {
        $this->expectException(InvalidArgumentException::class);
        $factory();
    }

    /** @return array<string, array{callable(): void}> */
    public function invalidConfigProvider(): array
    {
        return [
            'empty SMS username' => [static function (): void {
                new SmsConfig('', 'pass');
            }],
            'empty SMS password' => [static function (): void {
                new SmsConfig('user', '');
            }],
            'empty Switch key' => [static function (): void {
                new SwitchConfig('');
            }],
            'empty WhatsApp key' => [static function (): void {
                new WhatsAppConfig('');
            }],
            'invalid base URL' => [static function (): void {
                new SmsConfig('u', 'p', null, 'ftp://example.test');
            }],
            'zero timeout' => [static function (): void {
                new SwitchConfig('key', null, 0.0);
            }],
            'negative timeout' => [static function (): void {
                new WhatsAppConfig('key', null, -1.0);
            }],
        ];
    }

    /**
     * @dataProvider configClassProvider
     * @param class-string $class
     */
    public function testConfigurationPropertiesArePrivate(string $class): void
    {
        $reflection = new ReflectionClass($class);
        foreach ($reflection->getProperties() as $property) {
            self::assertTrue($property->isPrivate(), $property->getName());
        }
    }

    /** @return array<int, array{class-string}> */
    public function configClassProvider(): array
    {
        return [[SmsConfig::class], [SwitchConfig::class], [WhatsAppConfig::class]];
    }
}

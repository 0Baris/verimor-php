<?php

declare(strict_types=1);

namespace BarisCemant\Verimor\Tests\Integration;

use BarisCemant\Verimor\Tests\Contract\OperationProvider;
use BarisCemant\Verimor\Tests\Support\LocalHttpServer;
use PHPUnit\Framework\TestCase;
use Throwable;

final class OperationMatrixTest extends TestCase
{
    /** @var LocalHttpServer|null */ private static $server;

    public static function setUpBeforeClass(): void
    {
        self::$server = new LocalHttpServer();
    }

    public static function tearDownAfterClass(): void
    {
        if (self::$server !== null) {
            self::$server->stop();
            self::$server = null;
        }
    }

    /**
     * @dataProvider operationProvider
     * @param array<string, string> $operation
     */
    public function testOperationSendsExpectedMethodAndPath(array $operation): void
    {
        self::assertNotNull(self::$server);
        self::$server->clear();
        $client = OperationProvider::client($operation['product'], self::$server->url());
        $failure = null;
        try {
            OperationProvider::invoke($client, $operation);
        } catch (Throwable $error) {
            $failure = $error;
        }
        $captured = self::$server->captured();
        if ($captured === [] && $failure !== null) {
            throw $failure;
        }
        self::assertCount(1, $captured, $operation['operationId']);
        self::assertSame($operation['method'], $captured[0]->method, $operation['operationId']);
        $pattern = '#^' . preg_replace('#\\\{[^}]+\\\}#', '[^/]+', preg_quote($operation['path'], '#')) . '$#';
        self::assertMatchesRegularExpression($pattern, $captured[0]->path, $operation['operationId']);
        if (in_array($operation['method'], ['POST', 'PUT', 'PATCH'], true)) {
            self::assertNotSame('', $captured[0]->header('Content-Type'), $operation['operationId']);
        }
        self::assertNull(
            $failure,
            $operation['operationId'] . ($failure === null ? '' : ': ' . $failure->getMessage())
        );
    }

    /** @return array<string, array{array<string, string>}> */
    public function operationProvider(): array
    {
        return OperationProvider::cases();
    }

    public function testManifestHasCompleteUniqueCoverage(): void
    {
        $operations = OperationProvider::operations();
        $counts = ['sms' => 0, 'switch' => 0, 'whatsapp' => 0];
        foreach ($operations as $operation) {
            ++$counts[$operation['product']];
        }
        self::assertCount(72, $operations);
        self::assertSame(['sms' => 14, 'switch' => 52, 'whatsapp' => 6], $counts);
        self::assertCount(72, array_unique(array_column($operations, 'operationId')));
        self::assertCount(72, array_unique(array_column($operations, 'proxy')));
    }
}

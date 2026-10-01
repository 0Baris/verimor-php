<?php

declare(strict_types=1);

namespace BarisCemant\Verimor\Tests\Unit;

use BarisCemant\Verimor\Exception\VerimorApiException;
use BarisCemant\Verimor\Http\ErrorMapper;
use BarisCemant\Verimor\Sms\Generated\ApiException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;

final class ErrorMapperTest extends TestCase
{
    public function testMapsJsonWithoutLeakingOriginalMessage(): void
    {
        $source = new ApiException(
            'secret-password',
            401,
            ['Content-Type' => ['application/json']],
            '{"message":"invalid"}'
        );

        $error = ErrorMapper::map('sms', 'balance', $source);

        self::assertInstanceOf(VerimorApiException::class, $error);
        self::assertSame('sms', $error->product());
        self::assertSame(401, $error->statusCode());
        self::assertSame(['message' => 'invalid'], $error->body());
        self::assertSame('balance', $error->operationId());
        self::assertStringNotContainsString('secret-password', $error->getMessage());
    }

    /**
     * @dataProvider bodyProvider
     * @param mixed $sourceBody
     * @param mixed $expectedBody
     */
    public function testNormalizesOtherBodies($sourceBody, $expectedBody): void
    {
        $source = new ApiException('unsafe', 500, [], $sourceBody);
        $mapped = ErrorMapper::map('switch', 'calls.originate', $source);

        self::assertInstanceOf(VerimorApiException::class, $mapped);
        self::assertSame($expectedBody, $mapped->body());
    }

    /** @return array<string, array{mixed, mixed}> */
    public function bodyProvider(): array
    {
        return [
            'text' => ['upstream failed', 'upstream failed'],
            'empty' => ['  ', null],
            'malformed JSON' => ['{"message":', '{"message":'],
            'JSON inferred by body' => [' [1, 2] ', [1, 2]],
            'decoded object' => [(object) ['message' => 'bad'], ['message' => 'bad']],
        ];
    }

    public function testNativeConnectionErrorPassesThroughUnchanged(): void
    {
        $source = new ConnectException('connection failed', new Request('GET', 'http://127.0.0.1'));

        self::assertSame($source, ErrorMapper::map('sms', 'balance', $source));
    }
}

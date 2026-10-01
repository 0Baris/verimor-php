<?php

declare(strict_types=1);

namespace BarisCemant\Verimor\Tests\Unit;

use BarisCemant\Verimor\WhatsApp\Config\WhatsAppConfig;
use BarisCemant\Verimor\WhatsApp\Dto\SendOtpRequest;
use BarisCemant\Verimor\WhatsApp\Dto\SendUtilityRequest;
use BarisCemant\Verimor\WhatsApp\Generated\Model\MessageResponse;
use BarisCemant\Verimor\WhatsApp\WhatsAppClient;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class WhatsAppClientTest extends TestCase
{
    public function testMessageConveniencesUseHeaderAndDocumented202(): void
    {
        $history = [];
        $response = static function (string $id): Response {
            return new Response(202, ['Content-Type' => 'application/json'], (string) json_encode([
                'id' => $id,
                'status' => 'accepted',
            ]));
        };
        $stack = HandlerStack::create(new MockHandler([$response('one'), $response('two')]));
        $stack->push(Middleware::history($history));
        $whatsapp = new WhatsAppClient(new WhatsAppConfig('wa-key'), new Client(['handler' => $stack]));
        $payload = ['to' => '905000000000', 'templateName' => 'otp'];

        $otp = $whatsapp->sendOtp(SendOtpRequest::fromArray($payload));
        $utility = $whatsapp->sendUtility(SendUtilityRequest::fromArray($payload));

        self::assertInstanceOf(MessageResponse::class, $otp);
        self::assertInstanceOf(MessageResponse::class, $utility);
        self::assertSame('one', $otp->getId());
        self::assertSame('two', $utility->getId());
        self::assertSame('wa-key', $history[0]['request']->getHeaderLine('x-api-key'));
        self::assertSame('wa-key', $history[1]['request']->getHeaderLine('x-api-key'));
    }
}

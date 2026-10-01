<?php

declare(strict_types=1);

namespace BarisCemant\Verimor\Tests\Integration;

use BarisCemant\Verimor\Sms\Config\SmsConfig;
use BarisCemant\Verimor\Sms\SmsClient;
use BarisCemant\Verimor\SwitchApi\Config\SwitchConfig;
use BarisCemant\Verimor\SwitchApi\Dto\ListAgentStatusesRequest;
use BarisCemant\Verimor\SwitchApi\SwitchClient;
use BarisCemant\Verimor\Tests\Support\LocalHttpServer;
use BarisCemant\Verimor\WhatsApp\Config\WhatsAppConfig;
use BarisCemant\Verimor\WhatsApp\Dto\SendOtpRequest;
use BarisCemant\Verimor\WhatsApp\WhatsAppClient;
use PHPUnit\Framework\TestCase;

final class AuthMappingTest extends TestCase
{
    public function testCriticalAuthLocationsAreExplicit(): void
    {
        $server = new LocalHttpServer();
        try {
            $sms = new SmsClient(new SmsConfig('kullanıcı+ &%', 'şifre/ %', null, $server->url()));
            $sms->balance();
            $sms->send(['messages' => [['msg' => 'Merhaba', 'dest' => '905000000000']]]);
            $switch = new SwitchClient(new SwitchConfig('anahtar+ &%/ç', $server->url()));
            $switch->users()->listAgentStatuses(new ListAgentStatusesRequest('101'));
            $whatsapp = new WhatsAppClient(new WhatsAppConfig('wa+ &%/ç', $server->url()));
            $whatsapp->sendOtp(SendOtpRequest::fromArray([
                'to' => '905000000000',
                'templateName' => 'otp',
            ]));
            $whatsapp->health()->health();
            $requests = $server->captured();
            self::assertCount(5, $requests);
            self::assertSame('kullanıcı+ &%', $requests[0]->query['username']);
            self::assertSame('şifre/ %', $requests[0]->query['password']);
            $smsBody = json_decode($requests[1]->body, true);
            self::assertSame('kullanıcı+ &%', $smsBody['username']);
            self::assertSame('şifre/ %', $smsBody['password']);
            self::assertSame('anahtar+ &%/ç', $requests[2]->query['key']);
            self::assertSame('wa+ &%/ç', $requests[3]->header('x-api-key'));
            self::assertSame('', $requests[4]->header('x-api-key'));
        } finally {
            $server->stop();
        }
    }
}

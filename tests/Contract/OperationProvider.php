<?php

declare(strict_types=1);

namespace BarisCemant\Verimor\Tests\Contract;

use BarisCemant\Verimor\Sms\Config\SmsConfig;
use BarisCemant\Verimor\Sms\SmsClient;
use BarisCemant\Verimor\SwitchApi\Config\SwitchConfig;
use BarisCemant\Verimor\SwitchApi\SwitchClient;
use BarisCemant\Verimor\WhatsApp\Config\WhatsAppConfig;
use BarisCemant\Verimor\WhatsApp\WhatsAppClient;
use GuzzleHttp\Client;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use RuntimeException;
use SplFileObject;

final class OperationProvider
{
    /** @return array<int, array<string, string>> */
    public static function operations(): array
    {
        $value = json_decode((string) file_get_contents(__DIR__ . '/../../contracts/operations.json'), true);
        if (!is_array($value)) {
            throw new RuntimeException('Cannot load operation contract');
        }
        return $value;
    }

    /** @return array<string, array{array<string, string>}> */
    public static function cases(): array
    {
        $cases = [];
        foreach (self::operations() as $operation) {
            $cases[$operation['operationId']] = [$operation];
        }
        return $cases;
    }

    /** @return SmsClient|SwitchClient|WhatsAppClient */
    public static function client(string $product, string $url)
    {
        $http = new Client();
        if ($product === 'sms') {
            return new SmsClient(new SmsConfig('user+ç', 'pass&% /', 'VERIMOR', $url), $http);
        }
        if ($product === 'switch') {
            return new SwitchClient(new SwitchConfig('switch+&% /ç', $url), $http);
        }
        return new WhatsAppClient(new WhatsAppConfig('wa+&% /ç', $url), $http);
    }

    /**
     * @param SmsClient|SwitchClient|WhatsAppClient $client
     * @param array<string, string> $operation
     */
    public static function invoke($client, array $operation): void
    {
        [$serviceName, $methodName] = explode('.', $operation['proxy'], 2);
        $service = $client->{$serviceName}();
        $method = new ReflectionMethod($service, $methodName);
        $parameters = $method->getParameters();
        if ($parameters === []) {
            $method->invoke($service);
            return;
        }
        $type = $parameters[0]->getType();
        if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
            throw new RuntimeException($operation['operationId'] . ' has no typed request DTO');
        }
        $class = $type->getName();
        if (!class_exists($class)) {
            throw new RuntimeException($operation['operationId'] . ' request DTO does not exist');
        }
        $dto = self::dto($class);
        $method->invoke($service, $dto);
    }

    /**
     * @param class-string $class
     * @return object
     */
    private static function dto(string $class)
    {
        $reflection = new ReflectionClass($class);
        $constructor = $reflection->getConstructor();
        if ($constructor === null) {
            return $reflection->newInstance();
        }
        $arguments = [];
        foreach ($constructor->getParameters() as $parameter) {
            $arguments[] = self::value($parameter->getName(), $parameter->getType());
        }
        return $reflection->newInstanceArgs($arguments);
    }

    /**
     * @param \ReflectionType|null $type
     * @return mixed
     */
    private static function value(string $name, $type)
    {
        if ($name === 'filedata') {
            return new SplFileObject(__FILE__);
        }
        if ($type instanceof ReflectionNamedType) {
            if ($type->getName() === 'int') {
                return in_array($name, ['page', 'limit'], true) ? 1 : 123;
            }
            if ($type->getName() === 'float') {
                return 1.5;
            }
            if ($type->getName() === 'bool') {
                return true;
            }
            if ($type->getName() === 'array') {
                return self::arrayValue($name);
            }
        }
        $values = [
            'id' => '00000000-0000-4000-8000-000000000123',
            'dest' => '905000000000',
            'destination' => '905000000000',
            'phone' => '905000000000',
            'to' => '905000000000',
            'extension' => '101',
            'source' => '101',
            'templateName' => 'template_name',
            'email' => 'test@example.com',
            'state' => 'true',
        ];
        return $values[$name] ?? ('fixture-' . $name);
    }

    /** @return array<mixed> */
    private static function arrayValue(string $name): array
    {
        if ($name === 'messages') {
            return [['msg' => 'Merhaba', 'dest' => '905000000000']];
        }
        if ($name === 'consents') {
            return [['recipient' => '905000000000', 'status' => 'ONAY']];
        }
        if ($name === 'phoneList') {
            return [['phone' => '905000000000']];
        }
        if ($name === 'parameters') {
            return ['123456'];
        }
        return ['fixture'];
    }
}

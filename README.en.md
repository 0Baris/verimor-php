# Verimor PHP SDK

[Türkçe](README.md)

A PHP 7.4+ unofficial community SDK for the Verimor SMS, Switch, and WhatsApp APIs. It combines a stable hand-written client surface with a generated `raw()` layer covering all 72 OpenAPI operations.

> This project is community-maintained and unofficial. It does not claim support or compatibility guarantees on behalf of Verimor.
>
> This release is verified with offline contract and localhost tests; it has not yet been validated against the live Verimor service.

## Installation

```bash
composer require bariscemant/verimor
```

Requirements: PHP `^7.4 || ^8.0`, Composer 2, and Guzzle 7.4+. Keep credentials on servers only, preferably in environment variables or a secret manager.

## Quick start

### SMS

```php
use BarisCemant\Verimor\Sms\Config\SmsConfig;
use BarisCemant\Verimor\Sms\SmsClient;

$sms = new SmsClient(new SmsConfig(
    getenv('VERIMOR_SMS_USERNAME'),
    getenv('VERIMOR_SMS_PASSWORD'),
    'VERIMOR'
));
$campaignId = $sms->send([
    'messages' => [['msg' => 'Hello', 'dest' => '905000000000']],
]);
$sms->balance();
$status = $sms->statusById(12345);
// Or: $sms->statusByCustomId('order-42');
```

The default sender configured in `SmsConfig` is applied to each send. Override it per request with either `source_addr` or `sourceAddr`; providing both is an error.

### Switch

```php
use BarisCemant\Verimor\SwitchApi\Config\SwitchConfig;
use BarisCemant\Verimor\SwitchApi\Dto\OriginateRequest;
use BarisCemant\Verimor\SwitchApi\SwitchClient;

$switch = new SwitchClient(new SwitchConfig(getenv('VERIMOR_SWITCH_API_KEY')));
$callId = $switch->originate(new OriginateRequest('905000000000', '101'));
```

Switch operations are grouped by domain services such as `calls()`, `contacts()`, `queues()`, `records()`, and `users()`.

### WhatsApp

```php
use BarisCemant\Verimor\WhatsApp\Config\WhatsAppConfig;
use BarisCemant\Verimor\WhatsApp\Dto\SendOtpRequest;
use BarisCemant\Verimor\WhatsApp\WhatsAppClient;

$whatsapp = new WhatsAppClient(new WhatsAppConfig(getenv('VERIMOR_WHATSAPP_API_KEY')));
$result = $whatsapp->sendOtp(SendOtpRequest::fromArray([
    'templateName' => 'otp_template',
    'to' => '905000000000',
    'parameters' => ['123456'],
]));
```

Use `sendUtility()` in the same way for utility templates. The documented successful OTP response is HTTP `202`.

## Behavior contract

- The default timeout is 30 seconds and can be changed with the final config constructor argument.
- The SDK does not retry automatically. After `429`, timeouts, or `5xx`, delivery may be uncertain; a blind retry can cause duplicate delivery or calls.
- HTTP failures are normalized as `VerimorApiException`, exposing `product()`, `operationId()`, `statusCode()`, and `body()`.
- Connection, DNS, and timeout failures remain native Guzzle exception types.
- All 72 operations are available through `raw()`. The raw layer exposes generated signatures and response models; prefer typed services when possible.
- The SDK has no rate limiter. Handle `429` using your queue, idempotency policy, and applicable Verimor limits.

## Documentation

- [Installation](docs/en/installation.md) · [Configuration](docs/en/configuration.md)
- [SMS](docs/en/sms.md) · [Switch](docs/en/switch.md) · [WhatsApp](docs/en/whatsapp.md)
- [Errors](docs/en/errors.md) · [Raw API](docs/en/raw-api.md)
- [Testing and safety](docs/en/testing-and-safety.md) · [72 operations](docs/en/operations.md)

No Laravel or Symfony integration package is required. Register configs and clients in your container as services or singletons; the clients are framework-neutral.

## License

[MIT](LICENSE)

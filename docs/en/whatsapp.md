# WhatsApp

The WhatsApp client sends its API key in the `X-API-Key` header.

## OTP

```php
$result = $whatsapp->sendOtp(SendOtpRequest::fromArray([
    'templateName' => 'otp_template',
    'to' => '905000000000',
    'language' => 'en',
    'parameters' => ['123456'],
]));
```

The documented successful OTP status is HTTP `202`. If the response does not contain a valid `id` and `status`, the SDK does not silently accept it; it throws `UnexpectedResponseException`.

## Utility message

```php
$result = $whatsapp->sendUtility(SendUtilityRequest::fromArray([
    'templateName' => 'order_ready',
    'to' => '905000000000',
    'language' => 'en',
    'parameters' => ['42'],
]));
```

The health operation is available through `health()->health()`, message operations through `messages()`, and the full generated surface through `raw()`.

Template name, language, and parameter order must match an approved template on your account. There is no automatic retry after `429`, a timeout, or `5xx`; check your idempotency record before resending because the message may already be queued.

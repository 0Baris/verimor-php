# SMS

## Client

```php
$sms = new SmsClient(new SmsConfig($username, $password, 'VERIMOR'));
```

SMS credentials are inserted into the request body or query as required by each official operation contract. Redact URL query and body values in logs, exceptions, and telemetry.

## Send a message

```php
$campaignId = $sms->send([
    'messages' => [
        ['msg' => 'Your order is ready.', 'dest' => '905000000000'],
    ],
    'custom_id' => 'order-42',
]);
```

`send()` is the convenient array entry point and performs the same validation as `SendRequest::fromArray()`. Typed usage:

```php
$campaignId = $sms->sendRequest(SendRequest::fromArray($payload));
```

The config sender default is sent as `source_addr`. Override it per call using `source_addr` or camelCase `sourceAddr`, but never provide both. The sender must be configured for your account.

## Balance and status

```php
$sms->balance();
$byId = $sms->statusById(12345);
$byCustomId = $sms->statusByCustomId('order-42');
```

`statusById()` and `statusByCustomId()` are explicit, mutually exclusive facades. The lower-level `reports()->status(StatusRequest $request)` supports the detailed filters.

## Other domains

- `campaigns()`: send, legacy send, and cancel
- `balances()`: balance
- `reports()`: status and inbound messages
- `senderIds()`: configured sender IDs
- `blacklist()`: blacklist management
- `iys()`: IYS campaigns and consents

See the [operation table](operations.md) for the complete list. A timed-out send might still have reached the server. The SDK does not retry automatically; use `custom_id`, persistent application state, and controlled status checks to prevent duplicate delivery.

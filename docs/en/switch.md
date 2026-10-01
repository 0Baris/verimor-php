# Switch

```php
$switch = new SwitchClient(new SwitchConfig($apiKey));
$callId = $switch->originate(new OriginateRequest('905000000000', '101'));
```

The API key is inserted into the `key` query parameter defined by the Switch contract. Redact query parameters in URL and access logs.

## Services

- `calls()`: originate, answer, bridge, transfer, mute, and hangup
- `contacts()`: contacts and contact groups
- `queues()`: queues and user management
- `records()`: CDR, recordings, and voicemail
- `users()`: extensions, agent status, DND, and webphone tokens
- `announcements()`, `blacklist()`, `callerIds()`, `crm()`, `fax()`, `ivrCampaigns()`

Each method accepts an operation-specific DTO. Required fields lead the constructor, while `fromArray()` rejects unknown keys:

```php
$request = OriginateRequest::fromArray([
    'destination' => '905000000000',
    'extension' => '101',
    'callerId' => '908501234567',
    'timeout' => 30,
]);
$switch->calls()->originate($request);
```

For side-effecting operations such as call origination, a timeout or `5xx` does not prove failure. The SDK does not retry automatically. Check CDR/application state before retrying and implement business-level idempotency.

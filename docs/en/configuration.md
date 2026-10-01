# Configuration

Each product has a separate config and client. Never hard-code credentials; load them from environment variables or a secret manager.

```php
$smsConfig = new SmsConfig($username, $password, 'VERIMOR', null, 30.0);
$switchConfig = new SwitchConfig($switchApiKey, null, 30.0);
$whatsAppConfig = new WhatsAppConfig($whatsAppApiKey, null, 30.0);
```

Constructor order:

- `SmsConfig(username, password, sourceAddr?, baseUrl?, timeout?)`
- `SwitchConfig(apiKey, baseUrl?, timeout?)`
- `WhatsAppConfig(apiKey, baseUrl?, timeout?)`

The default timeout is 30 seconds and must be a positive `float`. Omitting `baseUrl` uses the product's default HTTPS endpoint. Tests and staging can provide an absolute `http://` or `https://` URL; trailing slashes are normalized.

## Dependency injection

Register clients in your container instead of rebuilding them inside every business method:

```php
$container->set(SmsClient::class, function () {
    return new SmsClient(new SmsConfig(
        (string) getenv('VERIMOR_SMS_USERNAME'),
        (string) getenv('VERIMOR_SMS_PASSWORD'),
        getenv('VERIMOR_SMS_SOURCE_ADDR') ?: null
    ));
});
```

Use a Laravel service provider singleton or a Symfony service definition. The SDK does not require PSR-18; inject a custom Guzzle `ClientInterface` as the client's second constructor argument when you need a proxy, middleware, or test handler.

Config objects do not mutate caller input, and credentials remain isolated between client instances.

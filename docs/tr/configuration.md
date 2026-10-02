# Yapılandırma

Her ürün ayrı config ve client kullanır. Credential'ları kaynak koda yazmayın; ortam değişkeni veya secret manager kullanın.

```php
$smsConfig = new SmsConfig($username, $password, 'VERIMOR', null, 30.0);
$switchConfig = new SwitchConfig($switchApiKey, null, 30.0);
$whatsAppConfig = new WhatsAppConfig($whatsAppApiKey, null, 30.0);
```

Constructor sırası:

- `SmsConfig(username, password, sourceAddr?, baseUrl?, timeout?)`
- `SwitchConfig(apiKey, baseUrl?, timeout?)`
- `WhatsAppConfig(apiKey, baseUrl?, timeout?)`

Varsayılan timeout 30 saniyedir. Timeout pozitif `float` olmalıdır. `baseUrl` verilmezse ürünün Verimor adresi kullanılır. Proxy veya test sunucusu gibi başka bir sunucu için mutlak bir `http://` ya da `https://` adresi verin; IP, port ve alt yol korunur, sondaki `/` normalize edilir.

## Dependency injection

Client'ı her iş metodu içinde yeniden kurmak yerine container'a kaydedin:

```php
$container->set(SmsClient::class, function () {
    return new SmsClient(new SmsConfig(
        (string) getenv('VERIMOR_SMS_USERNAME'),
        (string) getenv('VERIMOR_SMS_PASSWORD'),
        getenv('VERIMOR_SMS_SOURCE_ADDR') ?: null
    ));
});
```

Laravel'de service provider içindeki `singleton`, Symfony'de service definition kullanılabilir. SDK PSR-18 zorunlu kılmaz; özel Guzzle `ClientInterface` örneği client constructor'ının ikinci parametresi olarak enjekte edilebilir. Bu yol proxy, middleware veya test handler kullanımına uygundur.

Config nesneleri input değerlerini değiştirmez; iki client'ın credential'ları birbirine sızmaz.

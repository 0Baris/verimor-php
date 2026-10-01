# SMS

## Client

```php
$sms = new SmsClient(new SmsConfig($username, $password, 'VERIMOR'));
```

SMS kimlik bilgileri operasyonun resmî sözleşmesine göre body veya query içine eklenir. Log, exception veya telemetry sisteminizde URL query/body değerlerini maskeleyin.

## Mesaj gönderme

```php
$campaignId = $sms->send([
    'messages' => [
        ['msg' => 'Siparişiniz hazır.', 'dest' => '905000000000'],
    ],
    'custom_id' => 'siparis-42',
]);
```

`send()` pratik array girişidir ve `SendRequest::fromArray()` ile aynı doğrulamayı uygular. Typed kullanım:

```php
$campaignId = $sms->sendRequest(SendRequest::fromArray($payload));
```

Config'teki sender varsayılanı gönderime `source_addr` olarak taşınır. Çağrı bazında `source_addr` veya camelCase `sourceAddr` ile override edebilirsiniz. İki anahtarı birlikte vermeyin. Başlık hesabınızda tanımlı olmalıdır.

## Bakiye ve durum

```php
$sms->balance();
$byId = $sms->statusById(12345);
$byCustomId = $sms->statusByCustomId('siparis-42');
```

`statusById()` ile `statusByCustomId()` birbiri yerine kullanılan açık facade'lardır. Alt seviyede `reports()->status(StatusRequest $request)` daha ayrıntılı filtreleri destekler.

## Diğer alanlar

- `campaigns()`: gönderim, legacy gönderim, iptal
- `balances()`: bakiye
- `reports()`: durum ve gelen mesajlar
- `senderIds()`: tanımlı başlıklar
- `blacklist()`: kara liste
- `iys()`: İYS kampanya ve izinleri

Tam liste [operasyon tablosundadır](operations.md). Gönderim timeout aldığında sunucu isteği kabul etmiş olabilir. SDK otomatik retry yapmaz; `custom_id`, uygulama kaydı ve kontrollü sorgulama ile yinelenen gönderim riskini yönetin.

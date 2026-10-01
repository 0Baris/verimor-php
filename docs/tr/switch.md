# Switch

```php
$switch = new SwitchClient(new SwitchConfig($apiKey));
$callId = $switch->originate(new OriginateRequest('905000000000', '101'));
```

API anahtarı Switch sözleşmesindeki `key` query parametresine eklenir. URL ve erişim loglarında query parametrelerini maskeleyin.

## Servisler

- `calls()`: originate, answer, bridge, transfer, mute, hangup
- `contacts()`: kişi ve kişi grupları
- `queues()`: kuyruklar ve kullanıcı yönetimi
- `records()`: CDR, kayıt ve voicemail
- `users()`: dahili, agent durumu, DND ve webphone token
- `announcements()`, `blacklist()`, `callerIds()`, `crm()`, `fax()`, `ivrCampaigns()`

Her metot operasyon için üretilmiş DTO kabul eder. Zorunlu alanlar constructor'ın başında bulunur; `fromArray()` bilinmeyen anahtarları reddeder:

```php
$request = OriginateRequest::fromArray([
    'destination' => '905000000000',
    'extension' => '101',
    'callerId' => '908501234567',
    'timeout' => 30,
]);
$switch->calls()->originate($request);
```

Arama başlatma gibi yan etkili işlemlerde timeout veya `5xx` kesin başarısızlık anlamına gelmeyebilir. SDK otomatik retry yapmaz. Tekrar öncesi CDR/uygulama durumunu kontrol edin ve iş seviyesinde idempotency uygulayın.

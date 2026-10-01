# Verimor PHP SDK

[English](README.en.md)

Verimor SMS, Switch ve WhatsApp API'leri için PHP 7.4+ uyumlu, bağımsız topluluk SDK'sı. Paket; elle yazılmış, kararlı istemci yüzeyini OpenAPI'den üretilmiş 68 operasyonluk `raw()` katmanıyla birleştirir.

> Bu proje topluluk tarafından sürdürülür ve resmî değildir. Verimor adına destek veya uyumluluk garantisi vermez.
>
> Bu sürüm offline sözleşme ve localhost testleriyle doğrulanmıştır; canlı Verimor servisine karşı henüz doğrulanmamıştır.

## Kurulum

```bash
composer require bariscemant/verimor
```

Gereksinimler: PHP `^7.4 || ^8.0`, Composer 2 ve Guzzle 7.4+. Kimlik bilgilerini yalnız sunucu tarafında, ortam değişkeni veya secret manager içinde tutun.

## Hızlı başlangıç

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
    'messages' => [['msg' => 'Merhaba', 'dest' => '905000000000']],
]);
$sms->balance();
$status = $sms->statusById(12345);
// veya: $sms->statusByCustomId('siparis-42');
```

`SmsConfig` içindeki varsayılan başlık her gönderime uygulanır. Tek çağrıda `source_addr` veya `sourceAddr` vererek değiştirebilirsiniz; ikisini birlikte vermek hatadır.

### Switch

```php
use BarisCemant\Verimor\SwitchApi\Config\SwitchConfig;
use BarisCemant\Verimor\SwitchApi\Dto\OriginateRequest;
use BarisCemant\Verimor\SwitchApi\SwitchClient;

$switch = new SwitchClient(new SwitchConfig(getenv('VERIMOR_SWITCH_API_KEY')));
$callId = $switch->originate(new OriginateRequest('905000000000', '101'));
```

Switch operasyonları alanlara göre ayrılmış servislerde bulunur: `calls()`, `contacts()`, `queues()`, `records()`, `users()` ve diğerleri.

### WhatsApp

```php
use BarisCemant\Verimor\WhatsApp\Config\WhatsAppConfig;
use BarisCemant\Verimor\WhatsApp\Dto\SendOtpRequest;
use BarisCemant\Verimor\WhatsApp\WhatsAppClient;

$whatsapp = new WhatsAppClient(new WhatsAppConfig(getenv('VERIMOR_WHATSAPP_API_KEY')));
$result = $whatsapp->sendOtp(SendOtpRequest::fromArray([
    'templateName' => 'otp_sablonu',
    'to' => '905000000000',
    'parameters' => ['123456'],
]));
```

Utility şablonları için aynı biçimde `sendUtility()` kullanılır. OTP'nin belgelenmiş başarılı yanıtı HTTP `202`'dir.

## Davranış sözleşmesi

- Varsayılan timeout 30 saniyedir; config constructor'ının son parametresiyle değişir.
- SDK otomatik retry yapmaz. Özellikle `429`, timeout ve `5xx` sonrası isteğin sunucuya ulaşıp ulaşmadığı belirsiz olabilir; kör tekrar yinelenen gönderim veya çağrı oluşturabilir.
- HTTP hataları `VerimorApiException` olarak normalize edilir; `product()`, `operationId()`, `statusCode()` ve `body()` ile incelenir.
- Bağlantı, DNS ve timeout hataları Guzzle'ın yerel exception tipleriyle korunur.
- Tüm 68 operasyon `raw()` üzerinden erişilebilir. Raw katman generated imzaları ve response modellerini açar; mümkün olduğunda typed servisleri tercih edin.
- SDK rate limiter içermez. `429` yanıtını iş kuyruğunuz, idempotency stratejiniz ve Verimor limitlerinizle yönetin.

## Belgeler

- [Kurulum](docs/tr/installation.md) · [Yapılandırma](docs/tr/configuration.md)
- [SMS](docs/tr/sms.md) · [Switch](docs/tr/switch.md) · [WhatsApp](docs/tr/whatsapp.md)
- [Hatalar](docs/tr/errors.md) · [Raw API](docs/tr/raw-api.md)
- [Test ve güvenlik](docs/tr/testing-and-safety.md) · [68 operasyon](docs/tr/operations.md)

Laravel ve Symfony için özel bağımlılık zorunlu değildir. Config ve client nesnelerini container'ınıza singleton/service olarak kaydedebilirsiniz; istemciler framework bağımsızdır.

## Lisans

[MIT](LICENSE)

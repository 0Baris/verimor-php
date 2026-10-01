# WhatsApp

WhatsApp client API anahtarını `X-API-Key` header'ına ekler.

## OTP

```php
$result = $whatsapp->sendOtp(SendOtpRequest::fromArray([
    'templateName' => 'otp_sablonu',
    'to' => '905000000000',
    'language' => 'tr',
    'parameters' => ['123456'],
]));
```

OTP için belgelenmiş başarılı durum HTTP `202`'dir. Yanıtta geçerli `id` ve `status` yoksa SDK sessizce başarı kabul etmez; `UnexpectedResponseException` üretir.

## Utility mesajı

```php
$result = $whatsapp->sendUtility(SendUtilityRequest::fromArray([
    'templateName' => 'siparis_hazir',
    'to' => '905000000000',
    'language' => 'tr',
    'parameters' => ['42'],
]));
```

Health işlemi `health()->health()`, message operasyonları `messages()` servisindedir. Tam generated erişim `raw()` üzerinden sağlanır.

Template adı, dil ve parametre sırası Verimor hesabınızdaki onaylı template ile eşleşmelidir. `429`, timeout veya `5xx` sonrasında otomatik retry yoktur; mesaj daha önce kuyruğa alınmış olabileceği için tekrar göndermeden kendi idempotency kaydınızı kontrol edin.

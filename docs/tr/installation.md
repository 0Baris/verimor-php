# Kurulum

## Gereksinimler

- PHP 7.4 veya PHP 8.x
- Composer 2
- Guzzle 7.4+ (Composer otomatik kurar)
- PHP'nin JSON ve OpenSSL uzantıları

```bash
composer require bariscemant/verimor
```

Composer autoload'unu uygulamanızın girişinde yükleyin:

```php
require __DIR__ . '/vendor/autoload.php';
```

Paket framework bağımsızdır. Laravel service container, Symfony DependencyInjection veya düz PHP içinde aynı client sınıfları kullanılır. SDK'yı tarayıcı/mobile uygulamasına koymayın; SMS parolası ve API anahtarları yalnız sunucuda kalmalıdır.

## Sürüm politikası

Paket SemVer kullanır. `0.x` döneminde minor sürümler public API değişikliği içerebilir; yükseltmeden önce `CHANGELOG.md` dosyasını okuyun. `composer.lock` uygulamalarda commit edilmeli, kütüphane tüketiminde Composer'ın seçtiği sürüm gözden geçirilmelidir.

## Kurulumu doğrulama

```bash
composer show bariscemant/verimor
php -r "require 'vendor/autoload.php'; echo class_exists('BarisCemant\\Verimor\\Sms\\SmsClient') ? 'ok' : 'missing';"
```

Bu paket bağımsız ve resmî değildir. İlk sürüm offline/localhost testlerinden geçmiştir; canlı Verimor servisine karşı henüz doğrulanmamıştır.

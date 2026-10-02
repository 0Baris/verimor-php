# Değişiklik günlüğü / Changelog

Bu proje [Semantic Versioning](https://semver.org/) kullanır. / This project follows Semantic Versioning.

## 0.3.0

- 72 operasyonun her biri için çalıştırılabilir örnek: `examples/operations/<ürün>/<operasyon>.php`. Örnekler sözleşmeden üretilir; CI hepsini Verimor'a bağlanmadan yerel bir kayıt sunucusuna karşı çalıştırıp her birinin belgelenen isteği gönderdiğini doğrular (`scripts/run_examples.py php`).
- Yapay zekâ asistanları için tek dosyalık başvuru: `llms.md` (kurulum, kimlik doğrulama, sunucu adresi, hatalar ve her operasyonun çağrısı).
- Düzeltme: `SubmitIysConsentsRequest` artık `sourceAddr` istemez; verilmezse yapılandırmadaki varsayılan gönderici kullanılır. Ne istekte ne yapılandırmada gönderici yoksa istek gönderilmeden `InvalidArgumentException` atılır.
- Runnable example for each of the 72 operations: `examples/operations/<product>/<operation>.php`. The examples are generated from the contract; CI runs every one against a local recording server, without contacting Verimor, and checks each sends the documented request (`scripts/run_examples.py php`).
- A single-file reference for AI assistants: `llms.md` (install, authentication, server address, errors and the call for every operation).
- Fix: `SubmitIysConsentsRequest` no longer requires `sourceAddr`; when it is left out the configured default sender is used. With no sender in the request or the configuration it throws `InvalidArgumentException` before sending.

## 0.2.1

- Düzeltme: `SmsClient::balance()` ve `balances()->balance()` bakiyeyi döndürmüyor, `null` dönüyordu; artık API'nin gönderdiği metni döndürür.
- Sunucu adresi açıklamaları netleşti: varsayılan Verimor'un adresidir; kendi sunucunuz veya proxy için değiştirilebilir, IP, port ve alt yol korunur (testle doğrulandı).
- Fix: `SmsClient::balance()` and `balances()->balance()` returned `null` instead of the balance; they now return the text the API sends.
- Server URL docs clarified: Verimor's address is the default and can be changed to your own server or proxy; an IP, a port and a path prefix are kept (now tested).

## 0.2.0

- Verimor'un yeni operasyonları: SMS `campaigns()->sendOtp()` (`POST /v2/otp`); WhatsApp `messages()->sendBulk()`, `listMessages()` ve `getMessage()`. Kapsam 72 operasyon: SMS 14, Switch 52, WhatsApp 6.
- Verimor's new operations: SMS `campaigns()->sendOtp()` (`POST /v2/otp`); WhatsApp `messages()->sendBulk()`, `listMessages()` and `getMessage()`. Coverage is 72 operations: 14 SMS, 52 Switch, 6 WhatsApp.

## 0.1.0 - Yayın adayı / Release candidate

- PHP 7.4+ için SMS, Switch ve WhatsApp istemcileri.
- Typed DTO ve domain servisleri ile 68 operasyonluk `raw()` erişimi.
- HTTP hata normalizasyonu, 30 saniyelik timeout ve otomatik retry içermeyen güvenli varsayılanlar.
- Offline contract, localhost, paket içeriği ve temiz consumer testleri.

Çevrimdışı doğrulama (2026-10-01):

- PHP 7.4, 8.0, 8.1, 8.2, 8.3, 8.4 ve 8.5 (`php:<sürüm>-cli-alpine`, Composer 2.10.3) üzerinde temiz `vendor/` ile `composer validate --strict`, `composer install`, `composer check` ve katı PHPUnit (`--fail-on-warning --fail-on-risky --fail-on-skipped --fail-on-incomplete`) geçti: 214 test, 1191 assertion.
- `tests/Integration/NetworkFailureTest.php` PHP 7.4 ve 8.5 üzerinde 10 kez üst üste geçti.
- `scripts/build-archive.php` ile üretilen ZIP temiz bir consumer projesine kuruldu; SMS, Switch ve WhatsApp yerel sunucu çağrıları geçti.
- Arşivde ve depoda üretici depo adı, OpenAPI şeması, özel yol ve credential bulunmadı; testler yalnız `127.0.0.1` kullanır.
- Bu doğrulama sırasında `ArrayAccess` model imzalarındaki `mixed` parametre tipi PHP 7.4'te fatal hataya yol açtığı için düzeltildi.

Canlı Verimor servisi doğrulaması ve Packagist yayını henüz yapılmamıştır.

- SMS, Switch, and WhatsApp clients for PHP 7.4+.
- Typed DTO/domain services plus `raw()` access to all 68 operations.
- HTTP error normalization, a 30-second timeout, and no automatic retries.
- Offline contract, localhost, package-content, and clean-consumer tests.

Offline verification (2026-10-01):

- On PHP 7.4, 8.0, 8.1, 8.2, 8.3, 8.4, and 8.5 (`php:<version>-cli-alpine`, Composer 2.10.3) with a clean `vendor/`, `composer validate --strict`, `composer install`, `composer check`, and strict PHPUnit (`--fail-on-warning --fail-on-risky --fail-on-skipped --fail-on-incomplete`) passed: 214 tests, 1191 assertions.
- `tests/Integration/NetworkFailureTest.php` passed 10 consecutive runs on PHP 7.4 and 8.5.
- The ZIP built by `scripts/build-archive.php` installed into a clean consumer project; local-server calls for SMS, Switch, and WhatsApp passed.
- No generator repository name, OpenAPI schema, private path, or credential was found in the archive or repository; tests use `127.0.0.1` only.
- This verification found and fixed a `mixed` parameter type in `ArrayAccess` model signatures that was a fatal error on PHP 7.4.

Live Verimor validation and Packagist publication have not yet been performed.

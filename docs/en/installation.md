# Installation

## Requirements

- PHP 7.4 or PHP 8.x
- Composer 2
- Guzzle 7.4+ (installed by Composer)
- PHP JSON and OpenSSL extensions

```bash
composer require bariscemant/verimor
```

Load Composer's autoloader in your application entry point:

```php
require __DIR__ . '/vendor/autoload.php';
```

The package is framework-neutral. The same clients work with Laravel's service container, Symfony DependencyInjection, or plain PHP. Do not ship the SDK credentials in a browser or mobile application; SMS passwords and API keys belong on servers only.

## Version policy

The package follows SemVer. During `0.x`, minor releases can change public API; read `CHANGELOG.md` before upgrading. Applications should commit their `composer.lock` and review the resolved package version.

## Verify installation

```bash
composer show bariscemant/verimor
php -r "require 'vendor/autoload.php'; echo class_exists('BarisCemant\\Verimor\\Sms\\SmsClient') ? 'ok' : 'missing';"
```

This package is independent and unofficial. The initial release is verified offline and against localhost fixtures; it has not yet been validated against the live Verimor service.

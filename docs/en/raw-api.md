# Raw API

The typed facade is intended for normal use, while `raw()` exposes all 72 generated OpenAPI operations:

```php
$raw = $sms->raw();
$generatedApi = $raw->smsKampanyasi();
```

Switch and WhatsApp clients also provide `raw()`. Registry method names derive from OpenAPI tags. See the [operation table](operations.md) for every mapping.

Raw usage directly exposes generated method signatures, generated model classes, and generator decoding behavior. It is lower-level than the facade and more sensitive to upstream specification changes. Prefer typed services such as `campaigns()`, `calls()`, and `messages()`; use `raw()` only when the facade does not expose a needed control.

Keep generated types behind a small application adapter instead of spreading them across business code. Never edit generated files manually in `vendor/` or this repository.

# Raw API

Typed facade günlük kullanım içindir; `raw()` tüm 68 generated OpenAPI operasyonuna erişim sağlar:

```php
$raw = $sms->raw();
$generatedApi = $raw->smsKampanyasi();
```

Switch ve WhatsApp client'larında da `raw()` bulunur. Raw registry metotları OpenAPI tag'lerinden türetilir. Operasyon eşlemesi [operasyon tablosunda](operations.md) görülebilir.

Raw kullanımında generated metot imzası, generated model sınıfları ve generator'ın decode davranışı doğrudan public çağrınıza yansır. Bu yüzey façade göre daha düşük seviyelidir ve upstream spec değişikliklerinden daha fazla etkilenebilir. Önce `campaigns()`, `calls()`, `messages()` gibi typed servisleri; yalnız eksik bir kontrol gerektiğinde `raw()` kullanın.

Generated sınıfları uygulama katmanınızın her yerine yaymak yerine küçük bir adapter içinde sınırlandırmak yükseltmeyi kolaylaştırır. Generated dosyaları vendor içinde veya bu repoda elle düzenlemeyin.

# Hatalar

## HTTP hataları

Convenience ve domain servisleri `4xx/5xx` yanıtlarını `VerimorApiException` olarak sunar:

```php
try {
    $sms->send($payload);
} catch (VerimorApiException $error) {
    $product = $error->product();
    $operation = $error->operationId();
    $status = $error->statusCode();
    $body = $error->body();
}
```

`body()` JSON için decode edilmiş değer, metin için string, boş gövde için `null` olabilir. Hata gövdesinin biçimine güvenmeden önce tipini kontrol edin. `400`, `401`, `403`, `404`, `429`, `500` ve `503` aynı sözleşmeyle normalize edilir.

## Ağ hataları

DNS, bağlantı reddi, TLS ve timeout hataları Guzzle'ın native exception'ı olarak kalır. Bu ayrım, HTTP yanıtı alınmış hata ile hiç yanıt alınamayan durumu ayırmanızı sağlar.

## Bozuk başarılı yanıt

Bir `2xx` yanıtı şemaya uymuyorsa bazı generated operasyonlar `VerimorApiException`, doğrulanan WhatsApp convenience metotları ise `UnexpectedResponseException` üretebilir. Bu durum başarılı iş sonucu olarak kaydedilmemelidir.

SDK otomatik retry yapmaz. `429` için servis limitine uygun backoff uygulayın; yan etkili `send`/`originate` işlemlerinde timeout ve `5xx` sonrası kör retry yinelenen gönderim yaratabilir.

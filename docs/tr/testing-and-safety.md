# Test ve güvenlik

Bu paket topluluk tarafından sürdürülür ve resmî değildir. Bu sürüm offline sözleşme ve localhost testleriyle doğrulanmıştır; canlı Verimor servisine karşı henüz doğrulanmamıştır.

## Credential güvenliği

- Credential'ları yalnız sunucuda tutun; browser, mobil bundle veya public repoya koymayın.
- Ortam değişkeni/secret manager kullanın ve loglarda SMS body/query, Switch `key` query ve WhatsApp `X-API-Key` header değerlerini maskeleyin.
- Testlerde gerçek credential kullanmayın.

## Güvenli test yaklaşımı

Repo testleri localhost HTTP recorder kullanır ve canlı endpoint'e istek göndermez. Uygulamanızda custom `baseUrl` ile kendi mock sunucunuzu kullanabilirsiniz. En az başarı, `4xx`, `429`, `5xx`, timeout, bağlantı kopması ve bozuk response senaryolarını doğrulayın.

## Retry ve idempotency

SDK otomatik retry yapmaz ve rate limiter içermez. Timeout, `429` veya `5xx` yanıtı işlemin gerçekleşmediğini kanıtlamaz. SMS/WhatsApp gönderimi ve Switch çağrılarında iş anahtarı, kalıcı durum kaydı ve sorgulama kullanın; kontrolsüz tekrar yinelenen gönderim doğurabilir.

Varsayılan timeout 30 saniyedir. İş kuyruğunuzun görünürlük süresi ve üst seviye HTTP timeout'u bu değerden kısa olmamalıdır. Retry kararı uygulamanın sorumluluğundadır.

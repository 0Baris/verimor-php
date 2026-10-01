# Katkı rehberi

Bu repo dağıtılabilir PHP SDK'sıdır. Generated dosyalar elle değiştirilmez; OpenAPI veya proxy üretim değişiklikleri private generator reposunda yapılır ve kontrollü export edilir.

1. PHP 7.4 uyumluluğunu koruyun; union type, attribute ve named argument gibi daha yeni syntax kullanmayın.
2. Davranış değişikliğinden önce başarısız regresyon testi ekleyin.
3. `composer validate --strict`, `composer check` ve temiz consumer testini çalıştırın.
4. Gerçek credential veya canlı Verimor isteği kullanmayın; yalnız localhost fixture kullanın.
5. Public API değişikliklerini Türkçe ve İngilizce belgelerde birlikte güncelleyin.

Generated kod sorunu bildirirken ürün, operation ID, beklenen ve mevcut isteği paylaşın; secret değerlerini silin.

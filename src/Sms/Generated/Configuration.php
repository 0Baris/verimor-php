<?php
/**
 * Configuration
 * PHP version 8.1
 *
 * @category Class
 * @package  BarisCemant\Verimor\Sms\Generated
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */

/**
 * Verimor SMS API
 *
 * <p>Verimor SMS API, uygulamalarınız veya sunucu taraflı yazılımlarınız üzerinden SMS gönderimi ve yönetimi yapmanızı sağlayan bir HTTP arayüzüdür. API, farklı amaçlara yönelik (toplu gönderim, raporlama, bakiye sorgulama vb.) çeşitli endpoint'ler sunar.</p>  <h3>Kimlik Doğrulama (Authentication)</h3> <p>API'ye yapılan istekler, Verimor kullanıcı adı ve API şifreniz ile doğrulanır. Kimlik doğrulama yöntemi, isteğin türüne göre değişir:</p> <ul> <li><strong>POST İstekleri (örn: /v2/send.json):</strong> <code>username</code> ve <code>password</code> bilgileri, isteğin gövdesinde (request body) JSON formatında gönderilir.</li> <li><strong>GET İstekleri (örn: /v2/report):</strong> <code>username</code> ve <code>password</code> bilgileri, isteğin URL'ine query string parametresi olarak eklenir.</li> </ul> <p>API şifrenizi Verimor Online İşlem Merkezi (OİM) üzerinden oluşturabilirsiniz.</p>  <h3>Temel Yetenekler</h3> <p>API, aşağıdaki temel işlevleri desteklemektedir:</p> <ul> <li>Tekil veya toplu SMS gönderimi</li> <li>İleri tarihli SMS gönderimlerini programlama</li> <li>Gönderilen mesajların iletim durumlarını detaylı olarak sorgulama</li> <li>Hesapta kalan SMS kredisini öğrenme</li> <li>Zamanlanmış gönderimleri iptal etme</li> <li>Onaylanmış gönderici başlıklarını (alfanümerik) listeleme</li> </ul>  <h3>Teknik Formatlar</h3> <p>API, operasyona göre farklı veri formatları kullanır. Mesaj gönderme gibi <strong>POST</strong> işlemleri <code>application/json</code> formatında veri kabul eder ve yanıt döner. Raporlama gibi <strong>GET</strong> işlemleri ise parametreleri URL üzerinden alır ve yanıtı, isteğe bağlı olarak, varsayılan olarak <strong>boşluklarla ayrılmış düz metin (plain text)</strong> veya belirtilirse <strong>JSON</strong> formatında döndürebilir.</p>  <h3>Genel Notlar</h3> <ul> <li>/v2/send ve /v2/iys_consents.json aynı hız sınırı havuzunu paylaşır: dakikada toplam 240 istek gönderebilirsiniz (burst 80). 1 isteğin büyüklüğü 10 MB geçemez. Bu limitler dahilinde, isteğin yapısına bağlı olmakla birlikte dakikada 100.000.000 mesaj gönderilebilir.</li> <li>Yoğun OTP gönderimleri için kendi tarafınızda istekleri biriktirip saniyede bir post yöntemiyle sms gönderim isteği (çok kişiye çok mesaj isteği) yapmalısınız.</li> <li>Request limitlerini aştığınızda 429 (Too Many Requests) hatası döner.</li> <li>Paket boyutu limitini aştığınızda 413 (Request Entity Too Large) hatası döner.</li> <li>/v2/status, /v2/balance, /v2/cancel, /v2/headers, /v2/blacklists, /v2/inbound_messages ve /v2/iys/campaigns endpoint'leri kendi aralarında aynı hız sınırı havuzunu paylaşır: dakikada toplam 20 istek gönderebilirsiniz (burst 10). Önerimiz Push yöntemini kullanmanızdır.</li> <li>HTTPS olarak API'mizi kullanırken SSL bağlantısı için kullandığınız kütüphane sisteminizde kök sertifikalar yüklü olmadığından sertifikamızı doğrulamayabilir. Bu sorunu çözmek için lets-encrypt-r3.crt kök sertifika dosyasını <a href=\"https://github.com/verimor/SMS-API/blob/master/lets-encrypt-r3.crt\">buraya</a> tıklayarak indirip sisteminize kurmalısınız.</li> <li>Mesaj metninde yeni satıra geçiş yapabilmek için json'da (new line) \"\\n\" kullanımı gerekmektedir.</li> </ul>  <h3>Hata Kodları</h3> <p>SMS gönderirken ve gönderim raporu alırken size dönen status sahalarında aşağıdaki tablodaki değerler olabilir:</p> <p><strong>Mesaj Gönderirken Dönebilecek Durumlar ve Açıklamaları</strong></p> <table> <thead><tr><th>Web_Arayüzü_Durumları</th><th>API</th><th>Açıklama</th></tr></thead> <tbody> <tr><td>-</td><td>INVALID_SOURCE_ADDRESS</td><td>Başlık kabul edilmedi.</td></tr> <tr><td>-</td><td>MISSING_MESSAGE</td><td>Gönderilecek mesaj verilmemiş.</td></tr> <tr><td>-</td><td>MESSAGE_TOO_LONG</td><td>Mesaj çok uzun.</td></tr> <tr><td>-</td><td>INVALID_PERIOD</td><td>Mesajın geçerlilik süresi (validity period) geçersiz. (1dk. ile 48 saat arasında değil).</td></tr> <tr><td>-</td><td>INVALID_DELIVERY_TIME</td><td>\"send_at\" parametresi geçersiz veya geçmiş tarihe ait.</td></tr> <tr><td>-</td><td>INVALID_DATACODING</td><td>datacoding parametresi hatalı verilmiş.</td></tr> <tr><td>-</td><td>MISSING_IYS_BRAND_CODE</td><td>Ticari gönderimlerde başlığın marka kodunun tanımlanmış olması gereklidir</td></tr> <tr><td>-</td><td>AHS_AUTHORIZATION_ERROR</td><td>Yetkilendirme hatası. Lütfen İYS ile iletişime geçip Verimor'a AHS izni veriniz.</td></tr> <tr><td>-</td><td>NO_AHS_BRAND_ERROR</td><td>VKN'ye ait, İYS'de kayıtlı bir marka bulunamadı.</td></tr> <tr><td>-</td><td>COMMERCIAL_SENDING_ERROR_UNDER_150K</td><td>150 bin adedin altında ticari elektronik ileti onayı olan hesaplar için ticari gönderim 16 Temmuz 2021'de başlayacaktır. Bu tarihe kadar normal gönderimi kullanmalısınız.</td></tr> <tr><td>-</td><td>INVALID_IYS_RECIPIENT_TYPE</td><td>iys_recipient_type \"BIREYSEL\" yada \"TACIR\" olmalıdır.</td></tr> <tr><td>-</td><td>MISSING_DESTINATION_ADDRESS</td><td>Mesaj için alıcı verilmemiş.</td></tr> <tr><td>Hatalı Numara</td><td>INVALID_DESTINATION_ADDRESS</td><td>Alıcı telefon numarasının formatı geçersiz. (905121234567 gibi olmalı)</td></tr> <tr><td>-</td><td>INVALID_UTF8</td><td>Encoding UTF8 olmalıdır.</td></tr> <tr><td>-</td><td>MUKERRER_RAPORLAMA</td><td>24 Saat içerisinde aynı sms zaten atılmış.</td></tr> <tr><td>Kredi Yetersiz</td><td>INSUFFICIENT_CREDITS</td><td>Mesajı göndermek için yeterli bakiyeniz yok.</td></tr> <tr><td>Yasaklı içerik</td><td>FORBIDDEN_MESSAGE</td><td>Mesajınız yasak kelime(ler) içeriyor.</td></tr> <tr><td>-</td><td>INVALID_CONSENT_DATE</td><td>\"consent_date\" 1 Mayıs 2015 tarihinden önce olamaz.<br>\"consent_date\" ileri bir tarih olamaz.<br>\"consent_date\" 3 günden eski olamaz.<br>Kaynağı HS_2015 olan izinlerde \"consent_date\" 1 Mayıs 2015 olmalıdır.</td></tr> <tr><td>-</td><td>MISSING_CONSENT</td><td>Eksik izin durumu.</td></tr> <tr><td>-</td><td>MISSING_CONSENT_DATE</td><td>Gönderim tipi \"BIREYSEL\" olanlarda consent_date girilmelidir.</td></tr> <tr><td>-</td><td>INVALID_RECIPIENT</td><td>Geçersiz gönderim tipi.</td></tr> <tr><td>-</td><td>INVALID_JSON</td><td>Geçersiz JSON kullanımı</td></tr> <tr><td>-</td><td>MESSAGE_COUNT_LIMIT_EXCEEDED</td><td>Maksimum mesaj sayısına ulaşıldı. Bir seferde maksimum 50.000 adet mesajdan daha fazlası kabul edilmez.</td></tr> </tbody> </table> <p><strong>Mesaj Durumu Alınırken Dönebilecek Durumlar ve Açıklamaları</strong></p> <table> <thead><tr><th>Web_Arayüzü_Durumları</th><th>API_Durumları</th><th>Açıklama</th></tr></thead> <tbody> <tr><td>Gönderiliyor</td><td>SENDING</td><td>Mesaj gönderiliyor.</td></tr> <tr><td>Bekliyor</td><td>WAITING</td><td>Mesaj gönderildi. Cevap bekleniyor.</td></tr> <tr><td>İletildi</td><td>DELIVERED</td><td>Mesaj iletildi.</td></tr> <tr><td>İletildi</td><td>SENT</td><td>Mesaj iletildi. Fakat operatör gönderim raporunu desteklemediği için teyit edilemiyor. (Uluslararası bazı yönlerde oluşur.)</td></tr> <tr><td>İletilemedi</td><td>NOT_DELIVERED</td><td>Mesaj iletilemedi. (Genelde alıcı numaranın aktif olmamasından kaynaklanır.)</td></tr> <tr><td>Zaman aşımı</td><td>EXPIRED</td><td>Zaman aşımı. Mesajınız belirlediğiniz geçerlilik süresi içinde alıcısına teslim edilemedi.</td></tr> <tr><td>Hatalı Numara</td><td>INVALID_DESTINATION_ADDRESS</td><td>Alıcı telefon numarası geçersiz. (Hiçbir operatöre kayıtlı değil.)</td></tr> <tr><td>Reddedildi</td><td>REJECTED</td><td>Mesajınızın gönderimi reddedildi. (Genelde gsm operatörü tarafından içerik kontrolü sonucu oluşur.)</td></tr> <tr><td>Mükerrer Gönderim</td><td>DOUBLE_SEND_ERROR</td><td>Aynı içerik aynı gün aynı başlıkla aynı numaraya gönderilmiş. Mükerrer gönderim engellendi.</td></tr> <tr><td>Karalistede</td><td>BLACKLISTED_DESTINATION_ADDRESS</td><td>Alıcı kara listenizde.</td></tr> <tr><td>İYS izni yok</td><td>NOT_ALLOWED_BY_IYS</td><td>İYS izni yok.</td></tr> <tr><td>Tarife Bulunamadı</td><td>MISSING_TARIFF</td><td>Alıcının operatörü tarifelerimiz arasında bulunamamıştır. (Uluslararası yönlerde oluşur.)</td></tr> <tr><td>Geçersiz Şebeke</td><td>ROUTE_NOT_AVAILABLE</td><td>Hesabınız bu alıcıya mesaj gönderemez. (Uluslararası bazı yönlerde oluşur.)</td></tr> <tr><td>Geçersiz Şebeke</td><td>NETWORK_NOTCOVERED</td><td>Hesabınız bu alıcıya mesaj gönderemez. (Uluslararası bazı yönlerde oluşur.)</td></tr> <tr><td>Gönderim Hatası</td><td>SEND_ERROR</td><td>Mesajınız gönderilirken hata oluştu. (Sebebi çeşitli olabilir.)</td></tr> <tr><td>Uluslararası Gönderim Kapalı</td><td>INTERNATIONAL_DENIED</td><td>OİM'de SMS ayarlarından \"uluslararası gönderim\" ayarı kapalı olduğu için gönderilmedi.</td></tr> </tbody> </table> <p><strong>Mesaj Hata Kodları (gsm_error)</strong><br>İletilemeyen mesajlar için karşı operatörden alınan teknik hata kodları ve açıklamaları aşağıda verilmiştir.</p> <table> <thead><tr><th>Hata No</th><th>Hata Kodu</th><th>Açıklama</th></tr></thead> <tbody> <tr><td>1</td><td>EC_UNKNOWN_SUBSCRIBER</td><td>Numara karşı operatörün veritabanında bir aboneye tanımlı değil</td></tr> <tr><td>6</td><td>EC_ABSENT_SUBSCRIBER_SM</td><td>Karşı aboneden sinyal alınamadı. Abonenin telefonunun kapalı olduğu durumda veya sinyalin zayıf olduğu durumda görülür</td></tr> <tr><td>11</td><td>EC_TELESERVICE_NOT_PROVISIONED</td><td>Karşı abonenin mobil hizmeti operatörü tarafından durduruldu</td></tr> <tr><td>13</td><td>EC_CALL_BARRED</td><td>Karşı abone \"Rahatsız Etme\" (DND) hizmetini açtı, hiç mesaj almamayı tercih etti</td></tr> <tr><td>27</td><td>EC_ABSENT_SUBSCRIBER</td><td>Karşı abone çevrimiçi değil, telefon cihazı tarafından teyit edildi. Telefon kapatılınca görülür.</td></tr> <tr><td>31</td><td>EC_SUBSCRIBER_BUSY_FOR_MT_SMS</td><td>Karşı operatör fazla trafikten dolayı meşgul olduğunu bildirdi</td></tr> <tr><td>32</td><td>EC_SM_DELIVERY_FAILURE</td><td>Karşı operatör kısa mesajı abonesine iletemediğini bildirdi</td></tr> <tr><td>34</td><td>EC_SYSTEM_FAILURE</td><td>Karşı operatör sistem hatası bildirdi</td></tr> <tr><td>256</td><td>EC_SM_DF_MEMORYCAPACITYEXCEEDED</td><td>Karşı abonenin telefon cihazında mesajı kaydedecek yer kalmadı</td></tr> <tr><td>257</td><td>EC_SM_DF_EQUIPMENTPROTOCOLERROR</td><td>Karşı operatör, abonenin telefon cihazında hata olduğunu bildirdi</td></tr> <tr><td>258</td><td>EC_SM_DF_EQUIPMENTNOTSM_EQUIPPED</td><td>Karşı operatör, abonenin telefon cihazında hata olduğunu bildirdi</td></tr> <tr><td>500</td><td>EC_PROVIDER_GENERAL_ERROR</td><td>Karşı operatör genel hata bildirdi</td></tr> <tr><td>502</td><td>EC_NO_RESPONSE</td><td>Mesaj karşı operatöre iletildi fakat olumlu veya olumsuz bir iletim raporu dönmedi</td></tr> <tr><td>1030</td><td>EC_OR_POTENTIALVERSIONINCOMPATIBILITY</td><td>Karşı operatör genel hata bildirdi</td></tr> <tr><td>1155</td><td>EC_NNR_SUBSYSTEMFAILURE</td><td>Karşı operatör, sistem hatasından dolayı abonesine ulaşamadığını bildirdi</td></tr> <tr><td>1157</td><td>EC_NNR_MTPFAILURE</td><td>Karşı operatör genel hata bildirdi</td></tr> <tr><td>1281</td><td>EC_UA_USERSPECIFICREASON</td><td>Karşı operatör genel hata bildirdi</td></tr> <tr><td>1536</td><td>EC_PA_PROVIDERMALFUNCTION</td><td>Karşı operatör genel hata bildirdi</td></tr> <tr><td>2048</td><td>EC_TIME_OUT</td><td>Mesaj karşı operatöre geçerlilik süresi içinde iletilemedi</td></tr> <tr><td>2049</td><td>EC_IMSI_BLACKLISTED</td><td>Karşı abonenin SIM kartı operatörünün karalistesinde</td></tr> <tr><td>2050</td><td>EC_DEST_ADDRESS_BLACKLISTED</td><td>Numara karalistemizde olduğu için iletilemedi</td></tr> <tr><td>2051</td><td>EC_INVALIDMSCADDRESS</td><td>Mesaj metni karalistemizde olduğu için iletilemedi</td></tr> <tr><td>2053</td><td>EC_BLACKLISTED_SENDERADDRESS</td><td>Mesaj başlığının kullanımı için ek onay alınması gerekli</td></tr> <tr><td>4100</td><td>EC_MESSAGE_CANCELED</td><td>Karşı operatör mesajı abonesine geçerlilik süresi içinde iletemedi</td></tr> <tr><td>4101</td><td>EC_VALIDITYEXPIRED</td><td>Karşı operatör mesajı abonesine geçerlilik süresi içinde iletemedi</td></tr> <tr><td>4103</td><td>EC_DESTINATION_FLOODING</td><td>Karşıdaki abone çok fazla mesaj almış olduğu için yeni mesaj kabul etmiyor</td></tr> <tr><td>4104</td><td>EC_DESTINATION_TXT_FLOODING</td><td>Karşıdaki aboneye aynı mesaj çok defa gönderilmiş olduğu için yeni mesaj kabul etmiyor</td></tr> </tbody> </table>  <h3>SMS Boy Karakter Limitleri</h3> <table> <thead><tr><th></th><th>Normal (datacoding=0)</th><th>Türkçe (datacoding=1)</th><th>Unicode (datacoding=2)</th></tr></thead> <tbody> <tr><td>1 boy</td><td>0-160</td><td>0-155</td><td>0-70</td></tr> <tr><td>2 boy</td><td>161-306</td><td>156-298</td><td>71-134</td></tr> <tr><td>3 boy</td><td>307-459</td><td>299-447</td><td>135-201</td></tr> <tr><td>4 boy</td><td>460-612</td><td>448-596</td><td>202-268</td></tr> <tr><td>5 boy</td><td>613-765</td><td>597-745</td><td>269-335</td></tr> <tr><td>6 boy</td><td>766-918</td><td>746-894</td><td>336-402</td></tr> <tr><td>7 boy</td><td>919-1071</td><td>895-1043</td><td>403-469</td></tr> </tbody> </table> <p><strong>Not-1:</strong> datacoding=0 veya datacoding=1 gönderimlerde aşağıdaki karakterler 2 karakter sayılır. ^ { } \\ [ ] ~ | €<br><strong>Not-2:</strong> Sadece (Ş ş Ğ ğ ç ı İ) harfleri Türkçe olarak kabul edilir ve datacoding=1 olarak gönderilmelidir. Diğer Türkçe karakterleri (Ö ö Ü ü Ç) datacoding=0 olarak gönderebilirsiniz.</p>
 *
 * The version of the OpenAPI document: v2
 * Generated by: https://openapi-generator.tech
 * Generator version: 7.19.0
 */

/**
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

namespace BarisCemant\Verimor\Sms\Generated;

/**
 * Configuration Class Doc Comment
 * PHP version 8.1
 *
 * @category Class
 * @package  BarisCemant\Verimor\Sms\Generated
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class Configuration
{
    public const BOOLEAN_FORMAT_INT = 'int';
    public const BOOLEAN_FORMAT_STRING = 'string';

    /**
     * @var Configuration
     */
    private static $defaultConfiguration;

    /**
     * Associate array to store API key(s)
     *
     * @var string[]
     */
    protected $apiKeys = [];

    /**
     * Associate array to store API prefix (e.g. Bearer)
     *
     * @var string[]
     */
    protected $apiKeyPrefixes = [];

    /**
     * Access token for OAuth/Bearer authentication
     *
     * @var string
     */
    protected $accessToken = '';

    /**
     * Boolean format for query string
     *
     * @var string
     */
    protected $booleanFormatForQueryString = self::BOOLEAN_FORMAT_INT;

    /**
     * Username for HTTP basic authentication
     *
     * @var string
     */
    protected $username = '';

    /**
     * Password for HTTP basic authentication
     *
     * @var string
     */
    protected $password = '';

    /**
     * The host
     *
     * @var string
     */
    protected $host = 'https://sms.verimor.com.tr';

    /**
     * User agent of the HTTP request, set to "OpenAPI-Generator/{version}/PHP" by default
     *
     * @var string
     */
    protected $userAgent = 'OpenAPI-Generator/1.0.0/PHP';

    /**
     * Debug switch (default set to false)
     *
     * @var bool
     */
    protected $debug = false;

    /**
     * Debug file location (log to STDOUT by default)
     *
     * @var string
     */
    protected $debugFile = 'php://output';

    /**
     * Debug file location (log to STDOUT by default)
     *
     * @var string
     */
    protected $tempFolderPath;

    /**
     * Path to a certificate file, for mTLS
     *
     * @var string
     */
    protected $certFile;

    /**
     * Path to a key file, for mTLS
     *
     * @var string
     */
    protected $keyFile;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tempFolderPath = sys_get_temp_dir();
    }

    /**
     * Sets API key
     *
     * @param string $apiKeyIdentifier API key identifier (authentication scheme)
     * @param string $key              API key or token
     *
     * @return $this
     */
    public function setApiKey($apiKeyIdentifier, $key)
    {
        $this->apiKeys[$apiKeyIdentifier] = $key;
        return $this;
    }

    /**
     * Gets API key
     *
     * @param string $apiKeyIdentifier API key identifier (authentication scheme)
     *
     * @return null|string API key or token
     */
    public function getApiKey($apiKeyIdentifier)
    {
        return isset($this->apiKeys[$apiKeyIdentifier]) ? $this->apiKeys[$apiKeyIdentifier] : null;
    }

    /**
     * Sets the prefix for API key (e.g. Bearer)
     *
     * @param string $apiKeyIdentifier API key identifier (authentication scheme)
     * @param string $prefix           API key prefix, e.g. Bearer
     *
     * @return $this
     */
    public function setApiKeyPrefix($apiKeyIdentifier, $prefix)
    {
        $this->apiKeyPrefixes[$apiKeyIdentifier] = $prefix;
        return $this;
    }

    /**
     * Gets API key prefix
     *
     * @param string $apiKeyIdentifier API key identifier (authentication scheme)
     *
     * @return null|string
     */
    public function getApiKeyPrefix($apiKeyIdentifier)
    {
        return isset($this->apiKeyPrefixes[$apiKeyIdentifier]) ? $this->apiKeyPrefixes[$apiKeyIdentifier] : null;
    }

    /**
     * Sets the access token for OAuth
     *
     * @param string $accessToken Token for OAuth
     *
     * @return $this
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    /**
     * Gets the access token for OAuth
     *
     * @return string Access token for OAuth
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Sets boolean format for query string.
     *
     * @param string $booleanFormat Boolean format for query string
     *
     * @return $this
     */
    public function setBooleanFormatForQueryString(string $booleanFormat)
    {
        $this->booleanFormatForQueryString = $booleanFormat;

        return $this;
    }

    /**
     * Gets boolean format for query string.
     *
     * @return string Boolean format for query string
     */
    public function getBooleanFormatForQueryString(): string
    {
        return $this->booleanFormatForQueryString;
    }

    /**
     * Sets the username for HTTP basic authentication
     *
     * @param string $username Username for HTTP basic authentication
     *
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Gets the username for HTTP basic authentication
     *
     * @return string Username for HTTP basic authentication
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Sets the password for HTTP basic authentication
     *
     * @param string $password Password for HTTP basic authentication
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Gets the password for HTTP basic authentication
     *
     * @return string Password for HTTP basic authentication
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Sets the host
     *
     * @param string $host Host
     *
     * @return $this
     */
    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    /**
     * Gets the host
     *
     * @return string Host
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Sets the user agent of the api client
     *
     * @param string $userAgent the user agent of the api client
     *
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function setUserAgent($userAgent)
    {
        if (!is_string($userAgent)) {
            throw new \InvalidArgumentException('User-agent must be a string.');
        }

        $this->userAgent = $userAgent;
        return $this;
    }

    /**
     * Gets the user agent of the api client
     *
     * @return string user agent
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * Sets debug flag
     *
     * @param bool $debug Debug flag
     *
     * @return $this
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
        return $this;
    }

    /**
     * Gets the debug flag
     *
     * @return bool
     */
    public function getDebug()
    {
        return $this->debug;
    }

    /**
     * Sets the debug file
     *
     * @param string $debugFile Debug file
     *
     * @return $this
     */
    public function setDebugFile($debugFile)
    {
        $this->debugFile = $debugFile;
        return $this;
    }

    /**
     * Gets the debug file
     *
     * @return string
     */
    public function getDebugFile()
    {
        return $this->debugFile;
    }

    /**
     * Sets the temp folder path
     *
     * @param string $tempFolderPath Temp folder path
     *
     * @return $this
     */
    public function setTempFolderPath($tempFolderPath)
    {
        $this->tempFolderPath = $tempFolderPath;
        return $this;
    }

    /**
     * Gets the temp folder path
     *
     * @return string Temp folder path
     */
    public function getTempFolderPath()
    {
        return $this->tempFolderPath;
    }

    /**
     * Sets the certificate file path, for mTLS
     *
     * @return $this
     */
     public function setCertFile($certFile)
     {
        $this->certFile = $certFile;
        return $this;
     }

    /**
     * Gets the certificate file path, for mTLS
     *
     * @return string Certificate file path
     */
    public function getCertFile()
    {
        return $this->certFile;
    }

    /**
     * Sets the certificate key path, for mTLS
     *
     * @return $this
     */
     public function setKeyFile($keyFile)
     {
        $this->keyFile = $keyFile;
        return $this;
     }

    /**
     * Gets the certificate key path, for mTLS
     *
     * @return string Certificate key path
     */
    public function getKeyFile()
    {
        return $this->keyFile;
    }
    

    /**
     * Gets the default configuration instance
     *
     * @return Configuration
     */
    public static function getDefaultConfiguration()
    {
        if (self::$defaultConfiguration === null) {
            self::$defaultConfiguration = new Configuration();
        }

        return self::$defaultConfiguration;
    }

    /**
     * Sets the default configuration instance
     *
     * @param Configuration $config An instance of the Configuration Object
     *
     * @return void
     */
    public static function setDefaultConfiguration(Configuration $config)
    {
        self::$defaultConfiguration = $config;
    }

    /**
     * Gets the essential information for debugging
     *
     * @return string The report for debugging
     */
    public static function toDebugReport()
    {
        $report  = 'PHP SDK (BarisCemant\Verimor\Sms\Generated) Debug Report:' . PHP_EOL;
        $report .= '    OS: ' . php_uname() . PHP_EOL;
        $report .= '    PHP Version: ' . PHP_VERSION . PHP_EOL;
        $report .= '    The version of the OpenAPI document: v2' . PHP_EOL;
        $report .= '    Temp Folder Path: ' . self::getDefaultConfiguration()->getTempFolderPath() . PHP_EOL;

        return $report;
    }

    /**
     * Get API key (with prefix if set)
     *
     * @param  string $apiKeyIdentifier name of apikey
     *
     * @return null|string API key with the prefix
     */
    public function getApiKeyWithPrefix($apiKeyIdentifier)
    {
        $prefix = $this->getApiKeyPrefix($apiKeyIdentifier);
        $apiKey = $this->getApiKey($apiKeyIdentifier);

        if ($apiKey === null) {
            return null;
        }

        if ($prefix === null) {
            $keyWithPrefix = $apiKey;
        } else {
            $keyWithPrefix = $prefix . ' ' . $apiKey;
        }

        return $keyWithPrefix;
    }

    /**
     * Returns an array of host settings
     *
     * @return array an array of host settings
     */
    public function getHostSettings()
    {
        return [
            [
                "url" => "https://sms.verimor.com.tr",
                "description" => "No description provided",
            ]
        ];
    }

    /**
    * Returns URL based on host settings, index and variables
    *
    * @param array      $hostSettings array of host settings, generated from getHostSettings() or equivalent from the API clients
    * @param int        $hostIndex    index of the host settings
    * @param array|null $variables    hash of variable and the corresponding value (optional)
    * @return string URL based on host settings
    */
    public static function getHostString(array $hostSettings, $hostIndex, ?array $variables = null)
    {
        if (null === $variables) {
            $variables = [];
        }

        // check array index out of bound
        if ($hostIndex < 0 || $hostIndex >= count($hostSettings)) {
            throw new \InvalidArgumentException("Invalid index $hostIndex when selecting the host. Must be less than ".count($hostSettings));
        }

        $host = $hostSettings[$hostIndex];
        $url = $host["url"];

        // go through variable and assign a value
        foreach ($host["variables"] ?? [] as $name => $variable) {
            if (array_key_exists($name, $variables)) { // check to see if it's in the variables provided by the user
                if (!isset($variable['enum_values']) || in_array($variables[$name], $variable["enum_values"], true)) { // check to see if the value is in the enum
                    $url = str_replace("{".$name."}", $variables[$name], $url);
                } else {
                    throw new \InvalidArgumentException("The variable `$name` in the host URL has invalid value ".$variables[$name].". Must be ".join(',', $variable["enum_values"]).".");
                }
            } else {
                // use default value
                $url = str_replace("{".$name."}", $variable["default_value"], $url);
            }
        }

        return $url;
    }

    /**
     * Returns URL based on the index and variables
     *
     * @param int        $index     index of the host settings
     * @param array|null $variables hash of variable and the corresponding value (optional)
     * @return string URL based on host settings
     */
    public function getHostFromSettings($index, $variables = null)
    {
        return self::getHostString($this->getHostSettings(), $index, $variables);
    }
}

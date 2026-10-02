<?php
/**
 * SmsKampanyasiApi
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
 * <p>Verimor SMS API, uygulamalarınız veya sunucu taraflı yazılımlarınız üzerinden SMS gönderimi ve yönetimi yapmanızı sağlayan bir HTTP arayüzüdür. API, farklı amaçlara yönelik (toplu gönderim, raporlama, bakiye sorgulama vb.) çeşitli endpoint'ler sunar.</p>  <h3>Kimlik Doğrulama (Authentication)</h3> <p>API'ye yapılan istekler, Verimor kullanıcı adı ve API şifreniz ile doğrulanır. Kimlik doğrulama yöntemi, isteğin türüne göre değişir:</p> <ul> <li><strong>POST İstekleri (örn: /v2/send.json):</strong> <code>username</code> ve <code>password</code> bilgileri, isteğin gövdesinde (request body) JSON formatında gönderilir.</li> <li><strong>GET İstekleri (örn: /v2/report):</strong> <code>username</code> ve <code>password</code> bilgileri, isteğin URL'ine query string parametresi olarak eklenir.</li> </ul> <p>API şifrenizi Verimor Online İşlem Merkezi (OİM) üzerinden oluşturabilirsiniz.</p>  <h3>Temel Yetenekler</h3> <p>API, aşağıdaki temel işlevleri desteklemektedir:</p> <ul> <li>Tekil veya toplu SMS gönderimi</li> <li>İleri tarihli SMS gönderimlerini programlama</li> <li>Gönderilen mesajların iletim durumlarını detaylı olarak sorgulama</li> <li>Hesapta kalan SMS kredisini öğrenme</li> <li>Zamanlanmış gönderimleri iptal etme</li> <li>Onaylanmış gönderici başlıklarını (alfanümerik) listeleme</li> </ul>  <h3>Teknik Formatlar</h3> <p>API, operasyona göre farklı veri formatları kullanır. Mesaj gönderme gibi <strong>POST</strong> işlemleri <code>application/json</code> formatında veri kabul eder ve yanıt döner. Raporlama gibi <strong>GET</strong> işlemleri ise parametreleri URL üzerinden alır ve yanıtı, isteğe bağlı olarak, varsayılan olarak <strong>boşluklarla ayrılmış düz metin (plain text)</strong> veya belirtilirse <strong>JSON</strong> formatında döndürebilir.</p>  <h3>Genel Notlar</h3> <ul> <li>/v2/send ve /v2/iys_consents.json aynı hız sınırı havuzunu paylaşır: dakikada toplam 240 istek gönderebilirsiniz (burst 80). 1 isteğin büyüklüğü 10 MB geçemez. Bu limitler dahilinde, isteğin yapısına bağlı olmakla birlikte dakikada 100.000.000 mesaj gönderilebilir.</li> <li>Yoğun OTP gönderimleri için kendi tarafınızda istekleri biriktirip saniyede bir post yöntemiyle sms gönderim isteği (çok kişiye çok mesaj isteği) yapmalısınız.</li> <li>Request limitlerini aştığınızda 429 (Too Many Requests) hatası döner.</li> <li>Paket boyutu limitini aştığınızda 413 (Request Entity Too Large) hatası döner.</li> <li>/v2/status, /v2/balance, /v2/cancel, /v2/headers, /v2/blacklists, /v2/inbound_messages ve /v2/iys/campaigns endpoint'leri kendi aralarında aynı hız sınırı havuzunu paylaşır: dakikada toplam 20 istek gönderebilirsiniz (burst 10). Önerimiz Push yöntemini kullanmanızdır.</li> <li>HTTPS olarak API'mizi kullanırken SSL bağlantısı için kullandığınız kütüphane sisteminizde kök sertifikalar yüklü olmadığından sertifikamızı doğrulamayabilir. Bu sorunu çözmek için lets-encrypt-r3.crt kök sertifika dosyasını <a href=\"https://github.com/verimor/SMS-API/blob/master/lets-encrypt-r3.crt\">buraya</a> tıklayarak indirip sisteminize kurmalısınız.</li> <li>Mesaj metninde yeni satıra geçiş yapabilmek için json'da (new line) \"\\n\" kullanımı gerekmektedir.</li> </ul>  <h3>Hata Kodları</h3> <p>SMS gönderirken ve gönderim raporu alırken size dönen status sahalarında aşağıdaki tablodaki değerler olabilir:</p> <p><strong>Mesaj Gönderirken Dönebilecek Durumlar ve Açıklamaları</strong></p> <table> <thead><tr><th>Web_Arayüzü_Durumları</th><th>API</th><th>Açıklama</th></tr></thead> <tbody> <tr><td>-</td><td>INVALID_SOURCE_ADDRESS</td><td>Başlık kabul edilmedi.</td></tr> <tr><td>-</td><td>MISSING_MESSAGE</td><td>Gönderilecek mesaj verilmemiş.</td></tr> <tr><td>-</td><td>MESSAGE_TOO_LONG</td><td>Mesaj çok uzun.</td></tr> <tr><td>-</td><td>INVALID_PERIOD</td><td>Mesajın geçerlilik süresi (validity period) geçersiz. (1dk. ile 48 saat arasında değil).</td></tr> <tr><td>-</td><td>INVALID_DELIVERY_TIME</td><td>\"send_at\" parametresi geçersiz veya geçmiş tarihe ait.</td></tr> <tr><td>-</td><td>INVALID_DATACODING</td><td>datacoding parametresi hatalı verilmiş.</td></tr> <tr><td>-</td><td>MISSING_IYS_BRAND_CODE</td><td>Ticari gönderimlerde başlığın marka kodunun tanımlanmış olması gereklidir</td></tr> <tr><td>-</td><td>AHS_AUTHORIZATION_ERROR</td><td>Yetkilendirme hatası. Lütfen İYS ile iletişime geçip Verimor'a AHS izni veriniz.</td></tr> <tr><td>-</td><td>NO_AHS_BRAND_ERROR</td><td>VKN'ye ait, İYS'de kayıtlı bir marka bulunamadı.</td></tr> <tr><td>-</td><td>COMMERCIAL_SENDING_ERROR_UNDER_150K</td><td>150 bin adedin altında ticari elektronik ileti onayı olan hesaplar için ticari gönderim 16 Temmuz 2021'de başlayacaktır. Bu tarihe kadar normal gönderimi kullanmalısınız.</td></tr> <tr><td>-</td><td>INVALID_IYS_RECIPIENT_TYPE</td><td>iys_recipient_type \"BIREYSEL\" yada \"TACIR\" olmalıdır.</td></tr> <tr><td>-</td><td>MISSING_DESTINATION_ADDRESS</td><td>Mesaj için alıcı verilmemiş.</td></tr> <tr><td>Hatalı Numara</td><td>INVALID_DESTINATION_ADDRESS</td><td>Alıcı telefon numarasının formatı geçersiz. (905121234567 gibi olmalı)</td></tr> <tr><td>-</td><td>INVALID_UTF8</td><td>Encoding UTF8 olmalıdır.</td></tr> <tr><td>-</td><td>MUKERRER_RAPORLAMA</td><td>24 Saat içerisinde aynı sms zaten atılmış.</td></tr> <tr><td>Kredi Yetersiz</td><td>INSUFFICIENT_CREDITS</td><td>Mesajı göndermek için yeterli bakiyeniz yok.</td></tr> <tr><td>Yasaklı içerik</td><td>FORBIDDEN_MESSAGE</td><td>Mesajınız yasak kelime(ler) içeriyor.</td></tr> <tr><td>-</td><td>INVALID_CONSENT_DATE</td><td>\"consent_date\" 1 Mayıs 2015 tarihinden önce olamaz.<br>\"consent_date\" ileri bir tarih olamaz.<br>\"consent_date\" 3 günden eski olamaz.<br>Kaynağı HS_2015 olan izinlerde \"consent_date\" 1 Mayıs 2015 olmalıdır.</td></tr> <tr><td>-</td><td>MISSING_CONSENT</td><td>Eksik izin durumu.</td></tr> <tr><td>-</td><td>MISSING_CONSENT_DATE</td><td>Gönderim tipi \"BIREYSEL\" olanlarda consent_date girilmelidir.</td></tr> <tr><td>-</td><td>INVALID_RECIPIENT</td><td>Geçersiz gönderim tipi.</td></tr> <tr><td>-</td><td>INVALID_JSON</td><td>Geçersiz JSON kullanımı</td></tr> <tr><td>-</td><td>MESSAGE_COUNT_LIMIT_EXCEEDED</td><td>Maksimum mesaj sayısına ulaşıldı. Bir seferde maksimum 50.000 adet mesajdan daha fazlası kabul edilmez.</td></tr> <tr><td>-</td><td>MULTIPLE_DESTINATION_NOT_ALLOWED</td><td>Yalnızca /v2/otp: dest alanında birden fazla (virgülle ayrılmış) numara gönderildi. /v2/otp yalnızca tek alıcıyı destekler.</td></tr> <tr><td>-</td><td>MISSING_CODE_OR_MESSAGE</td><td>Yalnızca /v2/otp: code ve msg alanlarının ikisi de boş.</td></tr> <tr><td>-</td><td>MISSING_CODE</td><td>Yalnızca /v2/otp: msg içinde {code} yer tutucusu var ama code gönderilmemiş.</td></tr> </tbody> </table> <p><strong>Mesaj Durumu Alınırken Dönebilecek Durumlar ve Açıklamaları</strong></p> <table> <thead><tr><th>Web_Arayüzü_Durumları</th><th>API_Durumları</th><th>Açıklama</th></tr></thead> <tbody> <tr><td>Gönderiliyor</td><td>SENDING</td><td>Mesaj gönderiliyor.</td></tr> <tr><td>Bekliyor</td><td>WAITING</td><td>Mesaj gönderildi. Cevap bekleniyor.</td></tr> <tr><td>İletildi</td><td>DELIVERED</td><td>Mesaj iletildi.</td></tr> <tr><td>İletildi</td><td>SENT</td><td>Mesaj iletildi. Fakat operatör gönderim raporunu desteklemediği için teyit edilemiyor. (Uluslararası bazı yönlerde oluşur.)</td></tr> <tr><td>İletilemedi</td><td>NOT_DELIVERED</td><td>Mesaj iletilemedi. (Genelde alıcı numaranın aktif olmamasından kaynaklanır.)</td></tr> <tr><td>Zaman aşımı</td><td>EXPIRED</td><td>Zaman aşımı. Mesajınız belirlediğiniz geçerlilik süresi içinde alıcısına teslim edilemedi.</td></tr> <tr><td>Hatalı Numara</td><td>INVALID_DESTINATION_ADDRESS</td><td>Alıcı telefon numarası geçersiz. (Hiçbir operatöre kayıtlı değil.)</td></tr> <tr><td>Reddedildi</td><td>REJECTED</td><td>Mesajınızın gönderimi reddedildi. (Genelde gsm operatörü tarafından içerik kontrolü sonucu oluşur.)</td></tr> <tr><td>Mükerrer Gönderim</td><td>DOUBLE_SEND_ERROR</td><td>Aynı içerik aynı gün aynı başlıkla aynı numaraya gönderilmiş. Mükerrer gönderim engellendi.</td></tr> <tr><td>Karalistede</td><td>BLACKLISTED_DESTINATION_ADDRESS</td><td>Alıcı kara listenizde.</td></tr> <tr><td>İYS izni yok</td><td>NOT_ALLOWED_BY_IYS</td><td>İYS izni yok.</td></tr> <tr><td>Tarife Bulunamadı</td><td>MISSING_TARIFF</td><td>Alıcının operatörü tarifelerimiz arasında bulunamamıştır. (Uluslararası yönlerde oluşur.)</td></tr> <tr><td>Geçersiz Şebeke</td><td>ROUTE_NOT_AVAILABLE</td><td>Hesabınız bu alıcıya mesaj gönderemez. (Uluslararası bazı yönlerde oluşur.)</td></tr> <tr><td>Geçersiz Şebeke</td><td>NETWORK_NOTCOVERED</td><td>Hesabınız bu alıcıya mesaj gönderemez. (Uluslararası bazı yönlerde oluşur.)</td></tr> <tr><td>Gönderim Hatası</td><td>SEND_ERROR</td><td>Mesajınız gönderilirken hata oluştu. (Sebebi çeşitli olabilir.)</td></tr> <tr><td>Uluslararası Gönderim Kapalı</td><td>INTERNATIONAL_DENIED</td><td>OİM'de SMS ayarlarından \"uluslararası gönderim\" ayarı kapalı olduğu için gönderilmedi.</td></tr> </tbody> </table> <p><strong>Mesaj Hata Kodları (gsm_error)</strong><br>İletilemeyen mesajlar için karşı operatörden alınan teknik hata kodları ve açıklamaları aşağıda verilmiştir.</p> <table> <thead><tr><th>Hata No</th><th>Hata Kodu</th><th>Açıklama</th></tr></thead> <tbody> <tr><td>1</td><td>EC_UNKNOWN_SUBSCRIBER</td><td>Numara karşı operatörün veritabanında bir aboneye tanımlı değil</td></tr> <tr><td>6</td><td>EC_ABSENT_SUBSCRIBER_SM</td><td>Karşı aboneden sinyal alınamadı. Abonenin telefonunun kapalı olduğu durumda veya sinyalin zayıf olduğu durumda görülür</td></tr> <tr><td>11</td><td>EC_TELESERVICE_NOT_PROVISIONED</td><td>Karşı abonenin mobil hizmeti operatörü tarafından durduruldu</td></tr> <tr><td>13</td><td>EC_CALL_BARRED</td><td>Karşı abone \"Rahatsız Etme\" (DND) hizmetini açtı, hiç mesaj almamayı tercih etti</td></tr> <tr><td>27</td><td>EC_ABSENT_SUBSCRIBER</td><td>Karşı abone çevrimiçi değil, telefon cihazı tarafından teyit edildi. Telefon kapatılınca görülür.</td></tr> <tr><td>31</td><td>EC_SUBSCRIBER_BUSY_FOR_MT_SMS</td><td>Karşı operatör fazla trafikten dolayı meşgul olduğunu bildirdi</td></tr> <tr><td>32</td><td>EC_SM_DELIVERY_FAILURE</td><td>Karşı operatör kısa mesajı abonesine iletemediğini bildirdi</td></tr> <tr><td>34</td><td>EC_SYSTEM_FAILURE</td><td>Karşı operatör sistem hatası bildirdi</td></tr> <tr><td>256</td><td>EC_SM_DF_MEMORYCAPACITYEXCEEDED</td><td>Karşı abonenin telefon cihazında mesajı kaydedecek yer kalmadı</td></tr> <tr><td>257</td><td>EC_SM_DF_EQUIPMENTPROTOCOLERROR</td><td>Karşı operatör, abonenin telefon cihazında hata olduğunu bildirdi</td></tr> <tr><td>258</td><td>EC_SM_DF_EQUIPMENTNOTSM_EQUIPPED</td><td>Karşı operatör, abonenin telefon cihazında hata olduğunu bildirdi</td></tr> <tr><td>500</td><td>EC_PROVIDER_GENERAL_ERROR</td><td>Karşı operatör genel hata bildirdi</td></tr> <tr><td>502</td><td>EC_NO_RESPONSE</td><td>Mesaj karşı operatöre iletildi fakat olumlu veya olumsuz bir iletim raporu dönmedi</td></tr> <tr><td>1030</td><td>EC_OR_POTENTIALVERSIONINCOMPATIBILITY</td><td>Karşı operatör genel hata bildirdi</td></tr> <tr><td>1155</td><td>EC_NNR_SUBSYSTEMFAILURE</td><td>Karşı operatör, sistem hatasından dolayı abonesine ulaşamadığını bildirdi</td></tr> <tr><td>1157</td><td>EC_NNR_MTPFAILURE</td><td>Karşı operatör genel hata bildirdi</td></tr> <tr><td>1281</td><td>EC_UA_USERSPECIFICREASON</td><td>Karşı operatör genel hata bildirdi</td></tr> <tr><td>1536</td><td>EC_PA_PROVIDERMALFUNCTION</td><td>Karşı operatör genel hata bildirdi</td></tr> <tr><td>2048</td><td>EC_TIME_OUT</td><td>Mesaj karşı operatöre geçerlilik süresi içinde iletilemedi</td></tr> <tr><td>2049</td><td>EC_IMSI_BLACKLISTED</td><td>Karşı abonenin SIM kartı operatörünün karalistesinde</td></tr> <tr><td>2050</td><td>EC_DEST_ADDRESS_BLACKLISTED</td><td>Numara karalistemizde olduğu için iletilemedi</td></tr> <tr><td>2051</td><td>EC_INVALIDMSCADDRESS</td><td>Mesaj metni karalistemizde olduğu için iletilemedi</td></tr> <tr><td>2053</td><td>EC_BLACKLISTED_SENDERADDRESS</td><td>Mesaj başlığının kullanımı için ek onay alınması gerekli</td></tr> <tr><td>4100</td><td>EC_MESSAGE_CANCELED</td><td>Karşı operatör mesajı abonesine geçerlilik süresi içinde iletemedi</td></tr> <tr><td>4101</td><td>EC_VALIDITYEXPIRED</td><td>Karşı operatör mesajı abonesine geçerlilik süresi içinde iletemedi</td></tr> <tr><td>4103</td><td>EC_DESTINATION_FLOODING</td><td>Karşıdaki abone çok fazla mesaj almış olduğu için yeni mesaj kabul etmiyor</td></tr> <tr><td>4104</td><td>EC_DESTINATION_TXT_FLOODING</td><td>Karşıdaki aboneye aynı mesaj çok defa gönderilmiş olduğu için yeni mesaj kabul etmiyor</td></tr> </tbody> </table>  <h3>SMS Boy Karakter Limitleri</h3> <table> <thead><tr><th></th><th>Normal (datacoding=0)</th><th>Türkçe (datacoding=1)</th><th>Unicode (datacoding=2)</th></tr></thead> <tbody> <tr><td>1 boy</td><td>0-160</td><td>0-155</td><td>0-70</td></tr> <tr><td>2 boy</td><td>161-306</td><td>156-298</td><td>71-134</td></tr> <tr><td>3 boy</td><td>307-459</td><td>299-447</td><td>135-201</td></tr> <tr><td>4 boy</td><td>460-612</td><td>448-596</td><td>202-268</td></tr> <tr><td>5 boy</td><td>613-765</td><td>597-745</td><td>269-335</td></tr> <tr><td>6 boy</td><td>766-918</td><td>746-894</td><td>336-402</td></tr> <tr><td>7 boy</td><td>919-1071</td><td>895-1043</td><td>403-469</td></tr> </tbody> </table> <p><strong>Not-1:</strong> datacoding=0 veya datacoding=1 gönderimlerde aşağıdaki karakterler 2 karakter sayılır. ^ { } \\ [ ] ~ | €<br><strong>Not-2:</strong> Sadece (Ş ş Ğ ğ ç ı İ) harfleri Türkçe olarak kabul edilir ve datacoding=1 olarak gönderilmelidir. Diğer Türkçe karakterleri (Ö ö Ü ü Ç) datacoding=0 olarak gönderebilirsiniz.</p>
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

namespace BarisCemant\Verimor\Sms\Generated\Api;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use BarisCemant\Verimor\Sms\Generated\ApiException;
use BarisCemant\Verimor\Sms\Generated\Configuration;
use BarisCemant\Verimor\Sms\Generated\FormDataProcessor;
use BarisCemant\Verimor\Sms\Generated\HeaderSelector;
use BarisCemant\Verimor\Sms\Generated\ObjectSerializer;

/**
 * SmsKampanyasiApi Class Doc Comment
 *
 * @category Class
 * @package  BarisCemant\Verimor\Sms\Generated
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 */
class SmsKampanyasiApi
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var Configuration
     */
    protected $config;

    /**
     * @var HeaderSelector
     */
    protected $headerSelector;

    /**
     * @var int Host index
     */
    protected $hostIndex;

    /** @var string[] $contentTypes **/
    public const contentTypes = [
        'getV2Send' => [
            'application/json',
        ],
        'postV2CancelId' => [
            'application/json',
        ],
        'sendOtp' => [
            'application/json',
            'application/x-www-form-urlencoded',
        ],
        'sendSmsJson' => [
            'application/json',
        ],
    ];

    /**
     * @param ClientInterface $client
     * @param Configuration   $config
     * @param HeaderSelector  $selector
     * @param int             $hostIndex (Optional) host index to select the list of hosts if defined in the OpenAPI spec
     */
    public function __construct(
        ?ClientInterface $client = null,
        ?Configuration $config = null,
        ?HeaderSelector $selector = null,
        int $hostIndex = 0
    ) {
        $this->client = $client ?: new Client();
        $this->config = $config ?: Configuration::getDefaultConfiguration();
        $this->headerSelector = $selector ?: new HeaderSelector();
        $this->hostIndex = $hostIndex;
    }

    /**
     * Set the host index
     *
     * @param int $hostIndex Host index (required)
     */
    public function setHostIndex($hostIndex): void
    {
        $this->hostIndex = $hostIndex;
    }

    /**
     * Get the host index
     *
     * @return int Host index
     */
    public function getHostIndex()
    {
        return $this->hostIndex;
    }

    /**
     * @return Configuration
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Operation getV2Send
     *
     * SMS Gönderme (GET)
     *
     * @param  string $username API kullanıcı adı (required)
     * @param  string $password API şifresi (required)
     * @param  string $dest Mesajın gönderileceği telefon numaraları. Birden fazla numara varsa virgül ile ayrılmalıdır. Yurt dışı numaralarının başına 00 veya + eklenmelidir. Örnek: 0049xxxxxxxx veya +49xxxxxxxx (+ işareti URL&#39;lerde boşluk olarak yorumlanacağı için %2B olarak encode etmelisiniz, örn: %2B49xxxxxxxx). (zorunlu) (required)
     * @param  string $msg Gönderilecek mesaj. Türkçe harf içerebilir. Maksimum uzunluğu Türkçe harf içeriyorsa 1043, içermiyorsa 1071 karakterdir (zorunlu). Encoding her zaman UTF8 beklenir. (required)
     * @param  string|null $sourceAddr Gönderici kimliği (Başlık). Source_addr boş ise sistemde kayıtlı ilk başlığınız kullanılır. (optional)
     * @param  string|null $validFor Mesajın geçerlilik süresi. SS:DD (veya S:DD) formatında olmalı. (Varsayılan değer 24:00, Minimum değer 00:01, Maksimum değer 48:00) (optional)
     * @param  int|null $datacoding Mesaj metni için kullanılacak karakter kodlaması. 0, 1 ve 2 değerlerini alabilir. Mesajda kullanılabilecek harfleri ve mesajın boy limitlerini belirler. Boş ise mesaj metnine bakılır, Türkçe harf varsa 1, yoksa 0 kaydedilir. Mesaj boyları tablosu için dokümanın sonuna bakınız. Yurt dışına SMS gönderiminde değeri 1 olarak gönderilmemelidir.:  * &#x60;0&#x60;   * &#x60;1&#x60; (optional)
     * @param  bool|null $isCommercial Opsiyonel. true | false değeri alır. Ticari gönderimlerde true olarak belirlemelisiniz. (optional)
     * @param  string|null $iysRecipientType BIREYSEL | TACIR değeri alır. Ticari gönderimlerde mutlaka belirlemelisiniz. (optional)
     * @param  string|null $sendAt Mesajın gönderilmesini istediğiniz tarih saat. &#39;2015-02-20 16:06:00&#39; şeklinde veya ISO 8601 standardındaki formatlar kabul edilir (http://en.wikipedia.org/wiki/ISO_8601). Boş ise mesaj hemen gönderilir. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getV2Send'] to see the possible values for this operation
     *
     * @throws \BarisCemant\Verimor\Sms\Generated\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return int|string|string|string
     */
    public function getV2Send($username, $password, $dest, $msg, $sourceAddr = null, $validFor = null, $datacoding = null, $isCommercial = null, $iysRecipientType = null, $sendAt = null, string $contentType = self::contentTypes['getV2Send'][0])
    {
        list($response) = $this->getV2SendWithHttpInfo($username, $password, $dest, $msg, $sourceAddr, $validFor, $datacoding, $isCommercial, $iysRecipientType, $sendAt, $contentType);
        return $response;
    }

    /**
     * Operation getV2SendWithHttpInfo
     *
     * SMS Gönderme (GET)
     *
     * @param  string $username API kullanıcı adı (required)
     * @param  string $password API şifresi (required)
     * @param  string $dest Mesajın gönderileceği telefon numaraları. Birden fazla numara varsa virgül ile ayrılmalıdır. Yurt dışı numaralarının başına 00 veya + eklenmelidir. Örnek: 0049xxxxxxxx veya +49xxxxxxxx (+ işareti URL&#39;lerde boşluk olarak yorumlanacağı için %2B olarak encode etmelisiniz, örn: %2B49xxxxxxxx). (zorunlu) (required)
     * @param  string $msg Gönderilecek mesaj. Türkçe harf içerebilir. Maksimum uzunluğu Türkçe harf içeriyorsa 1043, içermiyorsa 1071 karakterdir (zorunlu). Encoding her zaman UTF8 beklenir. (required)
     * @param  string|null $sourceAddr Gönderici kimliği (Başlık). Source_addr boş ise sistemde kayıtlı ilk başlığınız kullanılır. (optional)
     * @param  string|null $validFor Mesajın geçerlilik süresi. SS:DD (veya S:DD) formatında olmalı. (Varsayılan değer 24:00, Minimum değer 00:01, Maksimum değer 48:00) (optional)
     * @param  int|null $datacoding Mesaj metni için kullanılacak karakter kodlaması. 0, 1 ve 2 değerlerini alabilir. Mesajda kullanılabilecek harfleri ve mesajın boy limitlerini belirler. Boş ise mesaj metnine bakılır, Türkçe harf varsa 1, yoksa 0 kaydedilir. Mesaj boyları tablosu için dokümanın sonuna bakınız. Yurt dışına SMS gönderiminde değeri 1 olarak gönderilmemelidir.:  * &#x60;0&#x60;   * &#x60;1&#x60; (optional)
     * @param  bool|null $isCommercial Opsiyonel. true | false değeri alır. Ticari gönderimlerde true olarak belirlemelisiniz. (optional)
     * @param  string|null $iysRecipientType BIREYSEL | TACIR değeri alır. Ticari gönderimlerde mutlaka belirlemelisiniz. (optional)
     * @param  string|null $sendAt Mesajın gönderilmesini istediğiniz tarih saat. &#39;2015-02-20 16:06:00&#39; şeklinde veya ISO 8601 standardındaki formatlar kabul edilir (http://en.wikipedia.org/wiki/ISO_8601). Boş ise mesaj hemen gönderilir. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getV2Send'] to see the possible values for this operation
     *
     * @throws \BarisCemant\Verimor\Sms\Generated\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return array of int|string|string|string, HTTP status code, HTTP response headers (array of strings)
     */
    public function getV2SendWithHttpInfo($username, $password, $dest, $msg, $sourceAddr = null, $validFor = null, $datacoding = null, $isCommercial = null, $iysRecipientType = null, $sendAt = null, string $contentType = self::contentTypes['getV2Send'][0])
    {
        $request = $this->getV2SendRequest($username, $password, $dest, $msg, $sourceAddr, $validFor, $datacoding, $isCommercial, $iysRecipientType, $sendAt, $contentType);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? (string) $e->getResponse()->getBody() : null
                );
            } catch (ConnectException $e) {
                throw $e;
            }

            $statusCode = $response->getStatusCode();


            switch($statusCode) {
                case 200:
                    return $this->handleResponseWithDataType(
                        'int',
                        $request,
                        $response,
                    );
                case 400:
                    return $this->handleResponseWithDataType(
                        'string',
                        $request,
                        $response,
                    );
                case 403:
                    return $this->handleResponseWithDataType(
                        'string',
                        $request,
                        $response,
                    );
                case 401:
                    return $this->handleResponseWithDataType(
                        'string',
                        $request,
                        $response,
                    );
            }

            

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        (string) $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    (string) $response->getBody()
                );
            }

            return $this->handleResponseWithDataType(
                'int',
                $request,
                $response,
            );
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        'int',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 400:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        'string',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 403:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        'string',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 401:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        'string',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
            }
        

            throw $e;
        }
    }

    /**
     * Operation getV2SendAsync
     *
     * SMS Gönderme (GET)
     *
     * @param  string $username API kullanıcı adı (required)
     * @param  string $password API şifresi (required)
     * @param  string $dest Mesajın gönderileceği telefon numaraları. Birden fazla numara varsa virgül ile ayrılmalıdır. Yurt dışı numaralarının başına 00 veya + eklenmelidir. Örnek: 0049xxxxxxxx veya +49xxxxxxxx (+ işareti URL&#39;lerde boşluk olarak yorumlanacağı için %2B olarak encode etmelisiniz, örn: %2B49xxxxxxxx). (zorunlu) (required)
     * @param  string $msg Gönderilecek mesaj. Türkçe harf içerebilir. Maksimum uzunluğu Türkçe harf içeriyorsa 1043, içermiyorsa 1071 karakterdir (zorunlu). Encoding her zaman UTF8 beklenir. (required)
     * @param  string|null $sourceAddr Gönderici kimliği (Başlık). Source_addr boş ise sistemde kayıtlı ilk başlığınız kullanılır. (optional)
     * @param  string|null $validFor Mesajın geçerlilik süresi. SS:DD (veya S:DD) formatında olmalı. (Varsayılan değer 24:00, Minimum değer 00:01, Maksimum değer 48:00) (optional)
     * @param  int|null $datacoding Mesaj metni için kullanılacak karakter kodlaması. 0, 1 ve 2 değerlerini alabilir. Mesajda kullanılabilecek harfleri ve mesajın boy limitlerini belirler. Boş ise mesaj metnine bakılır, Türkçe harf varsa 1, yoksa 0 kaydedilir. Mesaj boyları tablosu için dokümanın sonuna bakınız. Yurt dışına SMS gönderiminde değeri 1 olarak gönderilmemelidir.:  * &#x60;0&#x60;   * &#x60;1&#x60; (optional)
     * @param  bool|null $isCommercial Opsiyonel. true | false değeri alır. Ticari gönderimlerde true olarak belirlemelisiniz. (optional)
     * @param  string|null $iysRecipientType BIREYSEL | TACIR değeri alır. Ticari gönderimlerde mutlaka belirlemelisiniz. (optional)
     * @param  string|null $sendAt Mesajın gönderilmesini istediğiniz tarih saat. &#39;2015-02-20 16:06:00&#39; şeklinde veya ISO 8601 standardındaki formatlar kabul edilir (http://en.wikipedia.org/wiki/ISO_8601). Boş ise mesaj hemen gönderilir. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getV2Send'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function getV2SendAsync($username, $password, $dest, $msg, $sourceAddr = null, $validFor = null, $datacoding = null, $isCommercial = null, $iysRecipientType = null, $sendAt = null, string $contentType = self::contentTypes['getV2Send'][0])
    {
        return $this->getV2SendAsyncWithHttpInfo($username, $password, $dest, $msg, $sourceAddr, $validFor, $datacoding, $isCommercial, $iysRecipientType, $sendAt, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation getV2SendAsyncWithHttpInfo
     *
     * SMS Gönderme (GET)
     *
     * @param  string $username API kullanıcı adı (required)
     * @param  string $password API şifresi (required)
     * @param  string $dest Mesajın gönderileceği telefon numaraları. Birden fazla numara varsa virgül ile ayrılmalıdır. Yurt dışı numaralarının başına 00 veya + eklenmelidir. Örnek: 0049xxxxxxxx veya +49xxxxxxxx (+ işareti URL&#39;lerde boşluk olarak yorumlanacağı için %2B olarak encode etmelisiniz, örn: %2B49xxxxxxxx). (zorunlu) (required)
     * @param  string $msg Gönderilecek mesaj. Türkçe harf içerebilir. Maksimum uzunluğu Türkçe harf içeriyorsa 1043, içermiyorsa 1071 karakterdir (zorunlu). Encoding her zaman UTF8 beklenir. (required)
     * @param  string|null $sourceAddr Gönderici kimliği (Başlık). Source_addr boş ise sistemde kayıtlı ilk başlığınız kullanılır. (optional)
     * @param  string|null $validFor Mesajın geçerlilik süresi. SS:DD (veya S:DD) formatında olmalı. (Varsayılan değer 24:00, Minimum değer 00:01, Maksimum değer 48:00) (optional)
     * @param  int|null $datacoding Mesaj metni için kullanılacak karakter kodlaması. 0, 1 ve 2 değerlerini alabilir. Mesajda kullanılabilecek harfleri ve mesajın boy limitlerini belirler. Boş ise mesaj metnine bakılır, Türkçe harf varsa 1, yoksa 0 kaydedilir. Mesaj boyları tablosu için dokümanın sonuna bakınız. Yurt dışına SMS gönderiminde değeri 1 olarak gönderilmemelidir.:  * &#x60;0&#x60;   * &#x60;1&#x60; (optional)
     * @param  bool|null $isCommercial Opsiyonel. true | false değeri alır. Ticari gönderimlerde true olarak belirlemelisiniz. (optional)
     * @param  string|null $iysRecipientType BIREYSEL | TACIR değeri alır. Ticari gönderimlerde mutlaka belirlemelisiniz. (optional)
     * @param  string|null $sendAt Mesajın gönderilmesini istediğiniz tarih saat. &#39;2015-02-20 16:06:00&#39; şeklinde veya ISO 8601 standardındaki formatlar kabul edilir (http://en.wikipedia.org/wiki/ISO_8601). Boş ise mesaj hemen gönderilir. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getV2Send'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function getV2SendAsyncWithHttpInfo($username, $password, $dest, $msg, $sourceAddr = null, $validFor = null, $datacoding = null, $isCommercial = null, $iysRecipientType = null, $sendAt = null, string $contentType = self::contentTypes['getV2Send'][0])
    {
        $returnType = 'int';
        $request = $this->getV2SendRequest($username, $password, $dest, $msg, $sourceAddr, $validFor, $datacoding, $isCommercial, $iysRecipientType, $sendAt, $contentType);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    if ($returnType === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        (string) $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'getV2Send'
     *
     * @param  string $username API kullanıcı adı (required)
     * @param  string $password API şifresi (required)
     * @param  string $dest Mesajın gönderileceği telefon numaraları. Birden fazla numara varsa virgül ile ayrılmalıdır. Yurt dışı numaralarının başına 00 veya + eklenmelidir. Örnek: 0049xxxxxxxx veya +49xxxxxxxx (+ işareti URL&#39;lerde boşluk olarak yorumlanacağı için %2B olarak encode etmelisiniz, örn: %2B49xxxxxxxx). (zorunlu) (required)
     * @param  string $msg Gönderilecek mesaj. Türkçe harf içerebilir. Maksimum uzunluğu Türkçe harf içeriyorsa 1043, içermiyorsa 1071 karakterdir (zorunlu). Encoding her zaman UTF8 beklenir. (required)
     * @param  string|null $sourceAddr Gönderici kimliği (Başlık). Source_addr boş ise sistemde kayıtlı ilk başlığınız kullanılır. (optional)
     * @param  string|null $validFor Mesajın geçerlilik süresi. SS:DD (veya S:DD) formatında olmalı. (Varsayılan değer 24:00, Minimum değer 00:01, Maksimum değer 48:00) (optional)
     * @param  int|null $datacoding Mesaj metni için kullanılacak karakter kodlaması. 0, 1 ve 2 değerlerini alabilir. Mesajda kullanılabilecek harfleri ve mesajın boy limitlerini belirler. Boş ise mesaj metnine bakılır, Türkçe harf varsa 1, yoksa 0 kaydedilir. Mesaj boyları tablosu için dokümanın sonuna bakınız. Yurt dışına SMS gönderiminde değeri 1 olarak gönderilmemelidir.:  * &#x60;0&#x60;   * &#x60;1&#x60; (optional)
     * @param  bool|null $isCommercial Opsiyonel. true | false değeri alır. Ticari gönderimlerde true olarak belirlemelisiniz. (optional)
     * @param  string|null $iysRecipientType BIREYSEL | TACIR değeri alır. Ticari gönderimlerde mutlaka belirlemelisiniz. (optional)
     * @param  string|null $sendAt Mesajın gönderilmesini istediğiniz tarih saat. &#39;2015-02-20 16:06:00&#39; şeklinde veya ISO 8601 standardındaki formatlar kabul edilir (http://en.wikipedia.org/wiki/ISO_8601). Boş ise mesaj hemen gönderilir. (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['getV2Send'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function getV2SendRequest($username, $password, $dest, $msg, $sourceAddr = null, $validFor = null, $datacoding = null, $isCommercial = null, $iysRecipientType = null, $sendAt = null, string $contentType = self::contentTypes['getV2Send'][0])
    {

        // verify the required parameter 'username' is set
        if ($username === null || (is_array($username) && count($username) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $username when calling getV2Send'
            );
        }

        // verify the required parameter 'password' is set
        if ($password === null || (is_array($password) && count($password) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $password when calling getV2Send'
            );
        }

        // verify the required parameter 'dest' is set
        if ($dest === null || (is_array($dest) && count($dest) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $dest when calling getV2Send'
            );
        }

        // verify the required parameter 'msg' is set
        if ($msg === null || (is_array($msg) && count($msg) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $msg when calling getV2Send'
            );
        }








        $resourcePath = '/v2/send';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;

        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $username,
            'username', // param base name
            'string', // openApiType
            'form', // style
            true, // explode
            true // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $password,
            'password', // param base name
            'string', // openApiType
            'form', // style
            true, // explode
            true // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $dest,
            'dest', // param base name
            'string', // openApiType
            'form', // style
            true, // explode
            true // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $msg,
            'msg', // param base name
            'string', // openApiType
            'form', // style
            true, // explode
            true // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $sourceAddr,
            'source_addr', // param base name
            'string', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $validFor,
            'valid_for', // param base name
            'string', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $datacoding,
            'datacoding', // param base name
            'integer', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $isCommercial,
            'is_commercial', // param base name
            'boolean', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $iysRecipientType,
            'iys_recipient_type', // param base name
            'string', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);
        // query params
        $queryParams = array_merge($queryParams, ObjectSerializer::toQueryValue(
            $sendAt,
            'send_at', // param base name
            'string', // openApiType
            'form', // style
            true, // explode
            false // required
        ) ?? []);




        $headers = $this->headerSelector->selectHeaders(
            ['text/plain', ],
            $contentType,
            $multipart
        );

        // for model (json/xml)
        if (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $formParamValueItems = is_array($formParamValue) ? $formParamValue : [$formParamValue];
                    foreach ($formParamValueItems as $formParamValueItem) {
                        $multipartContents[] = [
                            'name' => $formParamName,
                            'contents' => $formParamValueItem
                        ];
                    }
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the form parameters
                $httpBody = \GuzzleHttp\Utils::jsonEncode($formParams);
            } else {
                // for HTTP post (form)
                $httpBody = ObjectSerializer::buildQuery($formParams);
            }
        }


        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $operationHost = $this->config->getHost();
        $query = ObjectSerializer::buildQuery($queryParams);
        return new Request(
            'GET',
            $operationHost . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Operation postV2CancelId
     *
     * Gönderim İptali
     *
     * @param  string $id Kampanya ID&#39;si (required)
     * @param  \BarisCemant\Verimor\Sms\Generated\Model\PostV2CancelIdRequest|null $postV2CancelIdRequest postV2CancelIdRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['postV2CancelId'] to see the possible values for this operation
     *
     * @throws \BarisCemant\Verimor\Sms\Generated\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return string
     */
    public function postV2CancelId($id, $postV2CancelIdRequest = null, string $contentType = self::contentTypes['postV2CancelId'][0])
    {
        list($response) = $this->postV2CancelIdWithHttpInfo($id, $postV2CancelIdRequest, $contentType);
        return $response;
    }

    /**
     * Operation postV2CancelIdWithHttpInfo
     *
     * Gönderim İptali
     *
     * @param  string $id Kampanya ID&#39;si (required)
     * @param  \BarisCemant\Verimor\Sms\Generated\Model\PostV2CancelIdRequest|null $postV2CancelIdRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['postV2CancelId'] to see the possible values for this operation
     *
     * @throws \BarisCemant\Verimor\Sms\Generated\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return array of string, HTTP status code, HTTP response headers (array of strings)
     */
    public function postV2CancelIdWithHttpInfo($id, $postV2CancelIdRequest = null, string $contentType = self::contentTypes['postV2CancelId'][0])
    {
        $request = $this->postV2CancelIdRequest($id, $postV2CancelIdRequest, $contentType);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? (string) $e->getResponse()->getBody() : null
                );
            } catch (ConnectException $e) {
                throw $e;
            }

            $statusCode = $response->getStatusCode();


            switch($statusCode) {
                case 200:
                    return $this->handleResponseWithDataType(
                        'string',
                        $request,
                        $response,
                    );
            }

            

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        (string) $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    (string) $response->getBody()
                );
            }

            return $this->handleResponseWithDataType(
                'string',
                $request,
                $response,
            );
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        'string',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
            }
        

            throw $e;
        }
    }

    /**
     * Operation postV2CancelIdAsync
     *
     * Gönderim İptali
     *
     * @param  string $id Kampanya ID&#39;si (required)
     * @param  \BarisCemant\Verimor\Sms\Generated\Model\PostV2CancelIdRequest|null $postV2CancelIdRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['postV2CancelId'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function postV2CancelIdAsync($id, $postV2CancelIdRequest = null, string $contentType = self::contentTypes['postV2CancelId'][0])
    {
        return $this->postV2CancelIdAsyncWithHttpInfo($id, $postV2CancelIdRequest, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation postV2CancelIdAsyncWithHttpInfo
     *
     * Gönderim İptali
     *
     * @param  string $id Kampanya ID&#39;si (required)
     * @param  \BarisCemant\Verimor\Sms\Generated\Model\PostV2CancelIdRequest|null $postV2CancelIdRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['postV2CancelId'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function postV2CancelIdAsyncWithHttpInfo($id, $postV2CancelIdRequest = null, string $contentType = self::contentTypes['postV2CancelId'][0])
    {
        $returnType = 'string';
        $request = $this->postV2CancelIdRequest($id, $postV2CancelIdRequest, $contentType);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    if ($returnType === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        (string) $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'postV2CancelId'
     *
     * @param  string $id Kampanya ID&#39;si (required)
     * @param  \BarisCemant\Verimor\Sms\Generated\Model\PostV2CancelIdRequest|null $postV2CancelIdRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['postV2CancelId'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function postV2CancelIdRequest($id, $postV2CancelIdRequest = null, string $contentType = self::contentTypes['postV2CancelId'][0])
    {

        // verify the required parameter 'id' is set
        if ($id === null || (is_array($id) && count($id) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $id when calling postV2CancelId'
            );
        }



        $resourcePath = '/v2/cancel/{id}';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;



        // path params
        if ($id !== null) {
            $resourcePath = str_replace(
                '{' . 'id' . '}',
                ObjectSerializer::toPathValue($id),
                $resourcePath
            );
        }


        $headers = $this->headerSelector->selectHeaders(
            ['text/plain', ],
            $contentType,
            $multipart
        );

        // for model (json/xml)
        if (isset($postV2CancelIdRequest)) {
            if (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the body
                $httpBody = \GuzzleHttp\Utils::jsonEncode(ObjectSerializer::sanitizeForSerialization($postV2CancelIdRequest));
            } else {
                $httpBody = $postV2CancelIdRequest;
            }
        } elseif (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $formParamValueItems = is_array($formParamValue) ? $formParamValue : [$formParamValue];
                    foreach ($formParamValueItems as $formParamValueItem) {
                        $multipartContents[] = [
                            'name' => $formParamName,
                            'contents' => $formParamValueItem
                        ];
                    }
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the form parameters
                $httpBody = \GuzzleHttp\Utils::jsonEncode($formParams);
            } else {
                // for HTTP post (form)
                $httpBody = ObjectSerializer::buildQuery($formParams);
            }
        }


        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $operationHost = $this->config->getHost();
        $query = ObjectSerializer::buildQuery($queryParams);
        return new Request(
            'POST',
            $operationHost . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Operation sendOtp
     *
     * OTP Gönderme
     *
     * @param  \BarisCemant\Verimor\Sms\Generated\Model\OtpRequest|null $otpRequest otpRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['sendOtp'] to see the possible values for this operation
     *
     * @throws \BarisCemant\Verimor\Sms\Generated\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return string|string|string|string
     */
    public function sendOtp($otpRequest = null, string $contentType = self::contentTypes['sendOtp'][0])
    {
        list($response) = $this->sendOtpWithHttpInfo($otpRequest, $contentType);
        return $response;
    }

    /**
     * Operation sendOtpWithHttpInfo
     *
     * OTP Gönderme
     *
     * @param  \BarisCemant\Verimor\Sms\Generated\Model\OtpRequest|null $otpRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['sendOtp'] to see the possible values for this operation
     *
     * @throws \BarisCemant\Verimor\Sms\Generated\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return array of string|string|string|string, HTTP status code, HTTP response headers (array of strings)
     */
    public function sendOtpWithHttpInfo($otpRequest = null, string $contentType = self::contentTypes['sendOtp'][0])
    {
        $request = $this->sendOtpRequest($otpRequest, $contentType);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? (string) $e->getResponse()->getBody() : null
                );
            } catch (ConnectException $e) {
                throw $e;
            }

            $statusCode = $response->getStatusCode();


            switch($statusCode) {
                case 200:
                    return $this->handleResponseWithDataType(
                        'string',
                        $request,
                        $response,
                    );
                case 400:
                    return $this->handleResponseWithDataType(
                        'string',
                        $request,
                        $response,
                    );
                case 401:
                    return $this->handleResponseWithDataType(
                        'string',
                        $request,
                        $response,
                    );
                case 403:
                    return $this->handleResponseWithDataType(
                        'string',
                        $request,
                        $response,
                    );
            }

            

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        (string) $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    (string) $response->getBody()
                );
            }

            return $this->handleResponseWithDataType(
                'string',
                $request,
                $response,
            );
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        'string',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 400:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        'string',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 401:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        'string',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 403:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        'string',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
            }
        

            throw $e;
        }
    }

    /**
     * Operation sendOtpAsync
     *
     * OTP Gönderme
     *
     * @param  \BarisCemant\Verimor\Sms\Generated\Model\OtpRequest|null $otpRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['sendOtp'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function sendOtpAsync($otpRequest = null, string $contentType = self::contentTypes['sendOtp'][0])
    {
        return $this->sendOtpAsyncWithHttpInfo($otpRequest, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation sendOtpAsyncWithHttpInfo
     *
     * OTP Gönderme
     *
     * @param  \BarisCemant\Verimor\Sms\Generated\Model\OtpRequest|null $otpRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['sendOtp'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function sendOtpAsyncWithHttpInfo($otpRequest = null, string $contentType = self::contentTypes['sendOtp'][0])
    {
        $returnType = 'string';
        $request = $this->sendOtpRequest($otpRequest, $contentType);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    if ($returnType === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        (string) $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'sendOtp'
     *
     * @param  \BarisCemant\Verimor\Sms\Generated\Model\OtpRequest|null $otpRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['sendOtp'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function sendOtpRequest($otpRequest = null, string $contentType = self::contentTypes['sendOtp'][0])
    {



        $resourcePath = '/v2/otp';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;





        $headers = $this->headerSelector->selectHeaders(
            ['text/plain', ],
            $contentType,
            $multipart
        );

        // for model (json/xml)
        if (isset($otpRequest)) {
            if (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the body
                $httpBody = \GuzzleHttp\Utils::jsonEncode(ObjectSerializer::sanitizeForSerialization($otpRequest));
            } else {
                $httpBody = $otpRequest;
            }
        } elseif (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $formParamValueItems = is_array($formParamValue) ? $formParamValue : [$formParamValue];
                    foreach ($formParamValueItems as $formParamValueItem) {
                        $multipartContents[] = [
                            'name' => $formParamName,
                            'contents' => $formParamValueItem
                        ];
                    }
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the form parameters
                $httpBody = \GuzzleHttp\Utils::jsonEncode($formParams);
            } else {
                // for HTTP post (form)
                $httpBody = ObjectSerializer::buildQuery($formParams);
            }
        }


        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $operationHost = $this->config->getHost();
        $query = ObjectSerializer::buildQuery($queryParams);
        return new Request(
            'POST',
            $operationHost . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Operation sendSmsJson
     *
     * SMS Gönderme (JSON)
     *
     * @param  \BarisCemant\Verimor\Sms\Generated\Model\SendSmsJsonRequest|null $sendSmsJsonRequest sendSmsJsonRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['sendSmsJson'] to see the possible values for this operation
     *
     * @throws \BarisCemant\Verimor\Sms\Generated\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return string|string|string|string
     */
    public function sendSmsJson($sendSmsJsonRequest = null, string $contentType = self::contentTypes['sendSmsJson'][0])
    {
        list($response) = $this->sendSmsJsonWithHttpInfo($sendSmsJsonRequest, $contentType);
        return $response;
    }

    /**
     * Operation sendSmsJsonWithHttpInfo
     *
     * SMS Gönderme (JSON)
     *
     * @param  \BarisCemant\Verimor\Sms\Generated\Model\SendSmsJsonRequest|null $sendSmsJsonRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['sendSmsJson'] to see the possible values for this operation
     *
     * @throws \BarisCemant\Verimor\Sms\Generated\ApiException on non-2xx response or if the response body is not in the expected format
     * @throws \InvalidArgumentException
     * @return array of string|string|string|string, HTTP status code, HTTP response headers (array of strings)
     */
    public function sendSmsJsonWithHttpInfo($sendSmsJsonRequest = null, string $contentType = self::contentTypes['sendSmsJson'][0])
    {
        $request = $this->sendSmsJsonRequest($sendSmsJsonRequest, $contentType);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    (int) $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? (string) $e->getResponse()->getBody() : null
                );
            } catch (ConnectException $e) {
                throw $e;
            }

            $statusCode = $response->getStatusCode();


            switch($statusCode) {
                case 200:
                    return $this->handleResponseWithDataType(
                        'string',
                        $request,
                        $response,
                    );
                case 400:
                    return $this->handleResponseWithDataType(
                        'string',
                        $request,
                        $response,
                    );
                case 401:
                    return $this->handleResponseWithDataType(
                        'string',
                        $request,
                        $response,
                    );
                case 403:
                    return $this->handleResponseWithDataType(
                        'string',
                        $request,
                        $response,
                    );
            }

            

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        (string) $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    (string) $response->getBody()
                );
            }

            return $this->handleResponseWithDataType(
                'string',
                $request,
                $response,
            );
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        'string',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 400:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        'string',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 401:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        'string',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
                case 403:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        'string',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    throw $e;
            }
        

            throw $e;
        }
    }

    /**
     * Operation sendSmsJsonAsync
     *
     * SMS Gönderme (JSON)
     *
     * @param  \BarisCemant\Verimor\Sms\Generated\Model\SendSmsJsonRequest|null $sendSmsJsonRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['sendSmsJson'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function sendSmsJsonAsync($sendSmsJsonRequest = null, string $contentType = self::contentTypes['sendSmsJson'][0])
    {
        return $this->sendSmsJsonAsyncWithHttpInfo($sendSmsJsonRequest, $contentType)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation sendSmsJsonAsyncWithHttpInfo
     *
     * SMS Gönderme (JSON)
     *
     * @param  \BarisCemant\Verimor\Sms\Generated\Model\SendSmsJsonRequest|null $sendSmsJsonRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['sendSmsJson'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function sendSmsJsonAsyncWithHttpInfo($sendSmsJsonRequest = null, string $contentType = self::contentTypes['sendSmsJson'][0])
    {
        $returnType = 'string';
        $request = $this->sendSmsJsonRequest($sendSmsJsonRequest, $contentType);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    if ($returnType === '\SplFileObject') {
                        $content = $response->getBody(); //stream goes to serializer
                    } else {
                        $content = (string) $response->getBody();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        (string) $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'sendSmsJson'
     *
     * @param  \BarisCemant\Verimor\Sms\Generated\Model\SendSmsJsonRequest|null $sendSmsJsonRequest (optional)
     * @param  string $contentType The value for the Content-Type header. Check self::contentTypes['sendSmsJson'] to see the possible values for this operation
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    public function sendSmsJsonRequest($sendSmsJsonRequest = null, string $contentType = self::contentTypes['sendSmsJson'][0])
    {



        $resourcePath = '/v2/send.json';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;





        $headers = $this->headerSelector->selectHeaders(
            ['text/plain', ],
            $contentType,
            $multipart
        );

        // for model (json/xml)
        if (isset($sendSmsJsonRequest)) {
            if (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the body
                $httpBody = \GuzzleHttp\Utils::jsonEncode(ObjectSerializer::sanitizeForSerialization($sendSmsJsonRequest));
            } else {
                $httpBody = $sendSmsJsonRequest;
            }
        } elseif (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $formParamValueItems = is_array($formParamValue) ? $formParamValue : [$formParamValue];
                    foreach ($formParamValueItems as $formParamValueItem) {
                        $multipartContents[] = [
                            'name' => $formParamName,
                            'contents' => $formParamValueItem
                        ];
                    }
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif (stripos($headers['Content-Type'], 'application/json') !== false) {
                # if Content-Type contains "application/json", json_encode the form parameters
                $httpBody = \GuzzleHttp\Utils::jsonEncode($formParams);
            } else {
                // for HTTP post (form)
                $httpBody = ObjectSerializer::buildQuery($formParams);
            }
        }


        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $operationHost = $this->config->getHost();
        $query = ObjectSerializer::buildQuery($queryParams);
        return new Request(
            'POST',
            $operationHost . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Create http client option
     *
     * @throws \RuntimeException on file opening failure
     * @return array of http client options
     */
    protected function createHttpClientOption()
    {
        $options = [];
        if ($this->config->getDebug()) {
            $options[RequestOptions::DEBUG] = fopen($this->config->getDebugFile(), 'a');
            if (!$options[RequestOptions::DEBUG]) {
                throw new \RuntimeException('Failed to open the debug file: ' . $this->config->getDebugFile());
            }
        }

        if ($this->config->getCertFile()) {
            $options[RequestOptions::CERT] = $this->config->getCertFile();
        }

        if ($this->config->getKeyFile()) {
            $options[RequestOptions::SSL_KEY] = $this->config->getKeyFile();
        }

        return $options;
    }

    private function handleResponseWithDataType(
        string $dataType,
        RequestInterface $request,
        ResponseInterface $response
    ): array {
        if ($dataType === '\SplFileObject') {
            $content = $response->getBody(); //stream goes to serializer
        } else {
            $content = (string) $response->getBody();
            if ($dataType !== 'string') {
                try {
                    $content = json_decode($content, false, 512, JSON_THROW_ON_ERROR);
                } catch (\JsonException $exception) {
                    throw new ApiException(
                        sprintf(
                            'Error JSON decoding server response (%s)',
                            $request->getUri()
                        ),
                        $response->getStatusCode(),
                        $response->getHeaders(),
                        $content
                    );
                }
            }
        }

        return [
            ObjectSerializer::deserialize($content, $dataType, []),
            $response->getStatusCode(),
            $response->getHeaders()
        ];
    }

    private function responseWithinRangeCode(
        string $rangeCode,
        int $statusCode
    ): bool {
        $left = (int) ($rangeCode[0].'00');
        $right = (int) ($rangeCode[0].'99');

        return $statusCode >= $left && $statusCode <= $right;
    }
}

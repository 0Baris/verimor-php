<?php
/**
 * PostV2IysConsentsJsonRequest
 *
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

namespace BarisCemant\Verimor\Sms\Generated\Model;

use \ArrayAccess;
use \BarisCemant\Verimor\Sms\Generated\ObjectSerializer;

/**
 * PostV2IysConsentsJsonRequest Class Doc Comment
 *
 * @category Class
 * @package  BarisCemant\Verimor\Sms\Generated
 * @author   OpenAPI Generator team
 * @link     https://openapi-generator.tech
 * @implements \ArrayAccess<string, mixed>
 */
class PostV2IysConsentsJsonRequest implements ModelInterface, ArrayAccess, \JsonSerializable
{
    public const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $openAPIModelName = 'post_v2_iys_consents_json_request';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $openAPITypes = [
        'username' => 'string',
        'password' => 'string',
        'sourceAddr' => 'string',
        'consents' => '\BarisCemant\Verimor\Sms\Generated\Model\PostV2IysConsentsJsonRequestConsentsInner[]'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      * @phpstan-var array<string, string|null>
      * @psalm-var array<string, string|null>
      */
    protected static $openAPIFormats = [
        'username' => null,
        'password' => null,
        'sourceAddr' => null,
        'consents' => null
    ];

    /**
      * Array of nullable properties. Used for (de)serialization
      *
      * @var boolean[]
      */
    protected static array $openAPINullables = [
        'username' => false,
        'password' => false,
        'sourceAddr' => false,
        'consents' => false
    ];

    /**
      * If a nullable field gets set to null, insert it here
      *
      * @var boolean[]
      */
    protected array $openAPINullablesSetToNull = [];

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function openAPITypes()
    {
        return self::$openAPITypes;
    }

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function openAPIFormats()
    {
        return self::$openAPIFormats;
    }

    /**
     * Array of nullable properties
     *
     * @return array
     */
    protected static function openAPINullables(): array
    {
        return self::$openAPINullables;
    }

    /**
     * Array of nullable field names deliberately set to null
     *
     * @return boolean[]
     */
    private function getOpenAPINullablesSetToNull(): array
    {
        return $this->openAPINullablesSetToNull;
    }

    /**
     * Setter - Array of nullable field names deliberately set to null
     *
     * @param boolean[] $openAPINullablesSetToNull
     */
    private function setOpenAPINullablesSetToNull(array $openAPINullablesSetToNull): void
    {
        $this->openAPINullablesSetToNull = $openAPINullablesSetToNull;
    }

    /**
     * Checks if a property is nullable
     *
     * @param string $property
     * @return bool
     */
    public static function isNullable(string $property): bool
    {
        return self::openAPINullables()[$property] ?? false;
    }

    /**
     * Checks if a nullable property is set to null.
     *
     * @param string $property
     * @return bool
     */
    public function isNullableSetToNull(string $property): bool
    {
        return in_array($property, $this->getOpenAPINullablesSetToNull(), true);
    }

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'username' => 'username',
        'password' => 'password',
        'sourceAddr' => 'source_addr',
        'consents' => 'consents'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'username' => 'setUsername',
        'password' => 'setPassword',
        'sourceAddr' => 'setSourceAddr',
        'consents' => 'setConsents'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'username' => 'getUsername',
        'password' => 'getPassword',
        'sourceAddr' => 'getSourceAddr',
        'consents' => 'getConsents'
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @return array
     */
    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @return array
     */
    public static function setters()
    {
        return self::$setters;
    }

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @return array
     */
    public static function getters()
    {
        return self::$getters;
    }

    /**
     * The original name of the model.
     *
     * @return string
     */
    public function getModelName()
    {
        return self::$openAPIModelName;
    }


    /**
     * Associative array for storing property values
     *
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor
     *
     * @param mixed[]|null $data Associated array of property values
     *                      initializing the model
     */
    public function __construct(?array $data = null)
    {
        $this->setIfExists('username', $data ?? [], null);
        $this->setIfExists('password', $data ?? [], null);
        $this->setIfExists('sourceAddr', $data ?? [], null);
        $this->setIfExists('consents', $data ?? [], null);
    }

    /**
    * Sets $this->container[$variableName] to the given data or to the given default Value; if $variableName
    * is nullable and its value is set to null in the $fields array, then mark it as "set to null" in the
    * $this->openAPINullablesSetToNull array
    *
    * @param string $variableName
    * @param array  $fields
    * @param mixed  $defaultValue
    */
    private function setIfExists(string $variableName, array $fields, $defaultValue): void
    {
        if (self::isNullable($variableName) && array_key_exists($variableName, $fields) && is_null($fields[$variableName])) {
            $this->openAPINullablesSetToNull[] = $variableName;
        }

        $this->container[$variableName] = $fields[$variableName] ?? $defaultValue;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        if ($this->container['username'] === null) {
            $invalidProperties[] = "'username' can't be null";
        }
        if ($this->container['password'] === null) {
            $invalidProperties[] = "'password' can't be null";
        }
        if ($this->container['sourceAddr'] === null) {
            $invalidProperties[] = "'sourceAddr' can't be null";
        }
        if ($this->container['consents'] === null) {
            $invalidProperties[] = "'consents' can't be null";
        }
        return $invalidProperties;
    }

    /**
     * Validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {
        return count($this->listInvalidProperties()) === 0;
    }


    /**
     * Gets username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->container['username'];
    }

    /**
     * Sets username
     *
     * @param string $username Kullanıcı Adı
     *
     * @return self
     */
    public function setUsername($username)
    {
        if (is_null($username)) {
            throw new \InvalidArgumentException('non-nullable username cannot be null');
        }
        $this->container['username'] = $username;

        return $this;
    }

    /**
     * Gets password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->container['password'];
    }

    /**
     * Sets password
     *
     * @param string $password Şifre
     *
     * @return self
     */
    public function setPassword($password)
    {
        if (is_null($password)) {
            throw new \InvalidArgumentException('non-nullable password cannot be null');
        }
        $this->container['password'] = $password;

        return $this;
    }

    /**
     * Gets sourceAddr
     *
     * @return string
     */
    public function getSourceAddr()
    {
        return $this->container['sourceAddr'];
    }

    /**
     * Sets sourceAddr
     *
     * @param string $sourceAddr Başlık
     *
     * @return self
     */
    public function setSourceAddr($sourceAddr)
    {
        if (is_null($sourceAddr)) {
            throw new \InvalidArgumentException('non-nullable sourceAddr cannot be null');
        }
        $this->container['sourceAddr'] = $sourceAddr;

        return $this;
    }

    /**
     * Gets consents
     *
     * @return \BarisCemant\Verimor\Sms\Generated\Model\PostV2IysConsentsJsonRequestConsentsInner[]
     */
    public function getConsents()
    {
        return $this->container['consents'];
    }

    /**
     * Sets consents
     *
     * @param \BarisCemant\Verimor\Sms\Generated\Model\PostV2IysConsentsJsonRequestConsentsInner[] $consents consents
     *
     * @return self
     */
    public function setConsents($consents)
    {
        if (is_null($consents)) {
            throw new \InvalidArgumentException('non-nullable consents cannot be null');
        }
        $this->container['consents'] = $consents;

        return $this;
    }
    /**
     * Returns true if offset exists. False otherwise.
     *
     * @param integer|string $offset Offset
     *
     * @return boolean
     */
    public function offsetExists($offset): bool
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     *
     * @param integer|string $offset Offset
     *
     * @return mixed|null
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->container[$offset] ?? null;
    }

    /**
     * Sets value based on offset.
     *
     * @param int|null $offset Offset
     * @param mixed    $value  Value to be set
     *
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     *
     * @param integer|string $offset Offset
     *
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->container[$offset]);
    }

    /**
     * Serializes the object to a value that can be serialized natively by json_encode().
     * @link https://www.php.net/manual/en/jsonserializable.jsonserialize.php
     *
     * @return mixed Returns data which can be serialized by json_encode(), which is a value
     * of any type other than a resource.
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
       return ObjectSerializer::sanitizeForSerialization($this);
    }

    /**
     * Gets the string presentation of the object
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode(
            ObjectSerializer::sanitizeForSerialization($this),
            JSON_PRETTY_PRINT
        );
    }

    /**
     * Gets a header-safe presentation of the object
     *
     * @return string
     */
    public function toHeaderValue()
    {
        return json_encode(ObjectSerializer::sanitizeForSerialization($this));
    }
}



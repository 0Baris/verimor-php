# Verimor PHP SDK — reference for AI assistants

Unofficial PHP (7.4+) client for Verimor SMS, Switch and WhatsApp.

```bash
composer require bariscemant/verimor
```

## Setting the server

```php
use BarisCemant\Verimor\Sms\Config\SmsConfig;
use BarisCemant\Verimor\Sms\SmsClient;

$sms = new SmsClient(new SmsConfig($username, $password, 'VERIMOR', 'https://sms.example.test'));
```

`SwitchConfig($apiKey, $baseUrl)` and `WhatsAppConfig($apiKey, $baseUrl)` work the same way;
leave the base URL out to use Verimor's server.

## Calling operations

Each product client groups operations into services: `$client->{service}()->{operation}(Request::fromArray([...]))`.
Request classes live in `BarisCemant\Verimor\{Sms|SwitchApi|WhatsApp}\Dto` and reject unknown keys.

## Errors

A non-2xx answer throws `VerimorApiException` with the status code and body;
`UnexpectedResponseException` means a 2xx body did not match the documented shape.

## Products and authentication

| Product | Credentials | Default server | Environment variables used by the examples |
| --- | --- | --- | --- |
| SMS | username + password, optional default sender (`source_addr`) | `https://sms.verimor.com.tr` | `VERIMOR_SMS_USERNAME`, `VERIMOR_SMS_PASSWORD`, `VERIMOR_SMS_SENDER` |
| Switch | API key (`key`) | `https://api.bulutsantralim.com` | `VERIMOR_SWITCH_API_KEY` |
| WhatsApp | API key (`x-api-key` header) | `https://wapi.verimor.com.tr` | `VERIMOR_WHATSAPP_API_KEY` |

Every client talks to Verimor's server by default. Pass a different base URL to use a proxy,
a test server or a mock; every example reads it from `VERIMOR_BASE_URL`. The client adds the
credentials to each request itself (query, body or header, as the operation requires), so
request values never carry them. Keep credentials on the server side.

The SMS default sender is sent as `source_addr` wherever an operation accepts one and the
call does not set it.

## Running an example

Every operation has a runnable example. Set the environment variables above and run the
file; set `VERIMOR_BASE_URL` to point it at your own server. The repository's
`scripts/run_examples.py` runs all of them against a local recording server, which never
contacts Verimor.

## Operations

Each operation: HTTP method and path, the call, and the runnable example file. Values are samples from the API documentation.

### SMS

#### addBlacklistEntry — `POST /v2/blacklists`

Kara Liste Ekleme

PHP — [`examples/operations/sms/addBlacklistEntry.php`](examples/operations/sms/addBlacklistEntry.php)

```php
$client->blacklist()->addBlacklistEntry(AddBlacklistEntryRequest::fromArray([
    'phones' => '905001112233',
]))
```


#### balance — `GET /v2/balance`

Bakiye Sorgulama

PHP — [`examples/operations/sms/balance.php`](examples/operations/sms/balance.php)

```php
$client->balances()->balance()
```


#### cancel — `POST /v2/cancel/{id}`

Gönderim İptali

PHP — [`examples/operations/sms/cancel.php`](examples/operations/sms/cancel.php)

```php
$client->campaigns()->cancel(CancelRequest::fromArray([
    'id' => '123',
]))
```


#### deleteBlacklistEntry — `DELETE /v2/blacklists/{id}`

Kara Listeden Silme

PHP — [`examples/operations/sms/deleteBlacklistEntry.php`](examples/operations/sms/deleteBlacklistEntry.php)

```php
$client->blacklist()->deleteBlacklistEntry(DeleteBlacklistEntryRequest::fromArray([
    'id' => '123',
]))
```


#### listBlacklistEntries — `GET /v2/blacklists`

Kara Liste Görüntüleme

PHP — [`examples/operations/sms/listBlacklistEntries.php`](examples/operations/sms/listBlacklistEntries.php)

```php
$client->blacklist()->listBlacklistEntries(ListBlacklistEntriesRequest::fromArray([]))
```


#### listInboundMessages — `GET /v2/inbound_messages`

Gelen SMS Sorgulama

PHP — [`examples/operations/sms/listInboundMessages.php`](examples/operations/sms/listInboundMessages.php)

```php
$client->reports()->listInboundMessages(ListInboundMessagesRequest::fromArray([]))
```


#### listIysCampaignConsents — `GET /v2/iys/campaigns/{id}/consents`

İYS İzinleri Sorgulama

PHP — [`examples/operations/sms/listIysCampaignConsents.php`](examples/operations/sms/listIysCampaignConsents.php)

```php
$client->iys()->listIysCampaignConsents(ListIysCampaignConsentsRequest::fromArray([
    'id' => 1,
]))
```


#### listIysCampaigns — `GET /v2/iys/campaigns`

İYS Kampanyaları Listeleme

PHP — [`examples/operations/sms/listIysCampaigns.php`](examples/operations/sms/listIysCampaigns.php)

```php
$client->iys()->listIysCampaigns(ListIysCampaignsRequest::fromArray([]))
```


#### listSenderIds — `GET /v2/headers`

Başlık Yönetimi

PHP — [`examples/operations/sms/listSenderIds.php`](examples/operations/sms/listSenderIds.php)

```php
$client->senderIds()->listSenderIds()
```


#### send — `POST /v2/send.json`

SMS Gönderme (JSON)

PHP — [`examples/operations/sms/send.php`](examples/operations/sms/send.php)

```php
$client->campaigns()->send(SendRequest::fromArray([
    'messages' => [
        [
            'dest' => '905111111111,905111111112',
            'msg' => 'Deneme Mesaj',
        ],
    ],
]))
```


#### sendLegacy — `GET /v2/send`

SMS Gönderme (GET)

PHP — [`examples/operations/sms/sendLegacy.php`](examples/operations/sms/sendLegacy.php)

```php
$client->campaigns()->sendLegacy(SendLegacyRequest::fromArray([
    'dest' => '905001112233',
    'msg' => 'Merhaba',
]))
```


#### sendOtp — `POST /v2/otp`

OTP Gönderme

PHP — [`examples/operations/sms/sendOtp.php`](examples/operations/sms/sendOtp.php)

```php
$client->campaigns()->sendOtp(SendOtpRequest::fromArray([
    'dest' => '905001234567',
    'code' => '482931',
]))
```


#### status — `GET /v2/status`

Rapor Sorgulama (API ID)

PHP — [`examples/operations/sms/status.php`](examples/operations/sms/status.php)

```php
$client->reports()->status(StatusRequest::fromArray([]))
```


#### submitIysConsents — `POST /v2/iys_consents.json`

İzin Yönetimi

PHP — [`examples/operations/sms/submitIysConsents.php`](examples/operations/sms/submitIysConsents.php)

```php
$client->iys()->submitIysConsents(SubmitIysConsentsRequest::fromArray([
    'consents' => [
        [
            'type' => 'MESAJ',
            'source' => 'HS_WEB',
            'status' => 'ONAY',
            'recipient_type' => 'BIREYSEL',
            'consent_date' => '2022-04-14 13:30:30',
            'recipient' => '905001112233',
        ],
    ],
]))
```


### Switch

#### answer — `POST /answer`

Çağrıyı Cevaplama (POST)

PHP — [`examples/operations/switch/answer.php`](examples/operations/switch/answer.php)

```php
$client->calls()->answer(AnswerRequest::fromArray([
    'id' => '736eaf7e-4cc4-44ab-8dbe-16b18e9618b1',
]))
```


#### answerLegacy — `GET /answer/{id}`

Çağrıyı Cevaplama (GET)

PHP — [`examples/operations/switch/answerLegacy.php`](examples/operations/switch/answerLegacy.php)

```php
$client->calls()->answerLegacy(AnswerLegacyRequest::fromArray([
    'id' => '736eaf7e-4cc4-44ab-8dbe-16b18e9618b1',
]))
```


#### bridge — `GET /bridge`

Çağrı Bağlama

PHP — [`examples/operations/switch/bridge.php`](examples/operations/switch/bridge.php)

```php
$client->calls()->bridge(BridgeRequest::fromArray([
    'source' => '905111111111',
    'destination' => '905111111112',
]))
```


#### createAnnouncement — `POST /announcements`

Yeni Ses Dosyası Yükleme

PHP — [`examples/operations/switch/createAnnouncement.php`](examples/operations/switch/createAnnouncement.php)

```php
$client->announcements()->createAnnouncement(CreateAnnouncementRequest::fromArray([
    'name' => 'dosya adı',
    'sounddata' => 'base64',
]))
```


#### createBlockedNumber — `POST /blocked_numbers`

Kara Listeye Ekleme

PHP — [`examples/operations/switch/createBlockedNumber.php`](examples/operations/switch/createBlockedNumber.php)

```php
$client->blacklist()->createBlockedNumber(CreateBlockedNumberRequest::fromArray([
    'number' => '05111111111',
]))
```


#### createContact — `POST /contacts`

Kişi Ekleme

PHP — [`examples/operations/switch/createContact.php`](examples/operations/switch/createContact.php)

```php
$client->contacts()->createContact(CreateContactRequest::fromArray([
    'name' => 'Verimor',
    'surname' => 'Telekomünikasyon',
    'phone' => '05111111111',
]))
```


#### createContactGroup — `POST /contact_groups`

Grup Oluşturma

PHP — [`examples/operations/switch/createContactGroup.php`](examples/operations/switch/createContactGroup.php)

```php
$client->contacts()->createContactGroup(CreateContactGroupRequest::fromArray([
    'name' => 'Müşteriler',
]))
```


#### createFaxDocumentUrl — `POST /fax_document_url`

Faks Belgesi URL'si İsteme

PHP — [`examples/operations/switch/createFaxDocumentUrl.php`](examples/operations/switch/createFaxDocumentUrl.php)

```php
$client->fax()->createFaxDocumentUrl(CreateFaxDocumentUrlRequest::fromArray([
    'callUuid' => 'e28e5d48-05d8-11e8-663a-fde60c59425c',
]))
```


#### createFaxOrder — `POST /fax_orders`

Faks Gönderimi

PHP — [`examples/operations/switch/createFaxOrder.php`](examples/operations/switch/createFaxOrder.php)

```php
$client->fax()->createFaxOrder(CreateFaxOrderRequest::fromArray([
    'remoteStationId' => '901234567891',
    'filedata' => 'JVBERi0xLjQK',
]))
```


#### createIvrCampaign — `POST /ivr_campaigns.json`

Otomatik Arama Kampanyası Oluşturma

PHP — [`examples/operations/switch/createIvrCampaign.php`](examples/operations/switch/createIvrCampaign.php)

```php
$client->ivrCampaigns()->createIvrCampaign(CreateIvrCampaignRequest::fromArray([
    'callType' => 'ivr',
    'name' => 'Memnuniyet anketi',
    'phoneList' => [
        [
            'phone' => '05111111111',
            'phrase' => '#429 12/05/2017 #430 102.45 #431',
            'lang' => 'tr-TR',
        ],
        [
            'phone' => '05111111112',
            'phrase' => '#429 12/05/2017 #430 65.12 #431',
            'lang' => 'tr-TR',
        ],
    ],
]))
```


#### createRecordingUrl — `POST /recording_url`

Ses Kaydı için Geçici URL Oluşturma

PHP — [`examples/operations/switch/createRecordingUrl.php`](examples/operations/switch/createRecordingUrl.php)

```php
$client->records()->createRecordingUrl(CreateRecordingUrlRequest::fromArray([
    'callUuid' => '3f2504e0-4f89-41d3-9a0c-0305e82c3301',
]))
```


#### createVoicemailRecordingUrl — `POST /voicemail_recording_url`

Telesekreter Ses Kaydı için Geçici URL Oluşturma

PHP — [`examples/operations/switch/createVoicemailRecordingUrl.php`](examples/operations/switch/createVoicemailRecordingUrl.php)

```php
$client->records()->createVoicemailRecordingUrl(CreateVoicemailRecordingUrlRequest::fromArray([
    'uuid' => '12345678-1234-5678-4321-123456789012',
]))
```


#### createWebphoneToken — `POST /webphone_tokens`

Dahili için Token Alma (IFrame ile kullanmak için)

PHP — [`examples/operations/switch/createWebphoneToken.php`](examples/operations/switch/createWebphoneToken.php)

```php
$client->users()->createWebphoneToken(CreateWebphoneTokenRequest::fromArray([
    'extension' => '1001',
]))
```


#### deleteAnnouncement — `DELETE /announcements/{id}`

Ses Dosyası Silme

PHP — [`examples/operations/switch/deleteAnnouncement.php`](examples/operations/switch/deleteAnnouncement.php)

```php
$client->announcements()->deleteAnnouncement(DeleteAnnouncementRequest::fromArray([
    'id' => '123',
]))
```


#### deleteBlockedNumber — `DELETE /blocked_numbers/delete`

Kara Listeden Silme

PHP — [`examples/operations/switch/deleteBlockedNumber.php`](examples/operations/switch/deleteBlockedNumber.php)

```php
$client->blacklist()->deleteBlockedNumber(DeleteBlockedNumberRequest::fromArray([
    'number' => '05111111111',
]))
```


#### deleteContact — `DELETE /contacts/{id}`

Kişi Silme

PHP — [`examples/operations/switch/deleteContact.php`](examples/operations/switch/deleteContact.php)

```php
$client->contacts()->deleteContact(DeleteContactRequest::fromArray([
    'id' => 1,
]))
```


#### deleteContactGroup — `DELETE /contact_groups/{id}`

Grup Silme

PHP — [`examples/operations/switch/deleteContactGroup.php`](examples/operations/switch/deleteContactGroup.php)

```php
$client->contacts()->deleteContactGroup(DeleteContactGroupRequest::fromArray([
    'id' => 1,
]))
```


#### deleteIvrCampaign — `DELETE /ivr_campaigns/{id}.json`

Otomatik Arama Kampanyasını Silme

PHP — [`examples/operations/switch/deleteIvrCampaign.php`](examples/operations/switch/deleteIvrCampaign.php)

```php
$client->ivrCampaigns()->deleteIvrCampaign(DeleteIvrCampaignRequest::fromArray([
    'id' => '123',
]))
```


#### downloadFaxDocument — `GET /fax_document/{id}`

Faks Belgesi İndirme/Görüntüleme

PHP — [`examples/operations/switch/downloadFaxDocument.php`](examples/operations/switch/downloadFaxDocument.php)

```php
$client->fax()->downloadFaxDocument(DownloadFaxDocumentRequest::fromArray([
    'id' => '123',
]))
```


#### getCdr — `GET /cdrs/{id}`

Belirli Bir Çağrının Detaylı CDR Kaydı

PHP — [`examples/operations/switch/getCdr.php`](examples/operations/switch/getCdr.php)

```php
$client->records()->getCdr(GetCdrRequest::fromArray([
    'id' => 'call-uuid-12345-67890',
]))
```


#### getCrmIntegrations — `GET /crm_integrations`

CRM Entegrasyon Ayarlarını Getirme

PHP — [`examples/operations/switch/getCrmIntegrations.php`](examples/operations/switch/getCrmIntegrations.php)

```php
$client->crm()->getCrmIntegrations()
```


#### getExtension — `GET /extensions/{id}`

Dahili Detayı

PHP — [`examples/operations/switch/getExtension.php`](examples/operations/switch/getExtension.php)

```php
$client->users()->getExtension(GetExtensionRequest::fromArray([
    'id' => '1001',
]))
```


#### getWebhookPayloadExamples — `GET /webhook-payload-examples`

CRM Webhook Payload Örnekleri

PHP — [`examples/operations/switch/getWebhookPayloadExamples.php`](examples/operations/switch/getWebhookPayloadExamples.php)

```php
$client->crm()->getWebhookPayloadExamples()
```


#### hangup — `GET /hangup/{id}`

Çağrıyı Sonlandırma

PHP — [`examples/operations/switch/hangup.php`](examples/operations/switch/hangup.php)

```php
$client->calls()->hangup(HangupRequest::fromArray([
    'id' => 'f3797dfc-a818-11e7-bf70-cb295b6663ce',
]))
```


#### listAgentStatuses — `GET /agent_statuses`

MT Durumlarını ve Üyeliklerini Listeleme

PHP — [`examples/operations/switch/listAgentStatuses.php`](examples/operations/switch/listAgentStatuses.php)

```php
$client->users()->listAgentStatuses(ListAgentStatusesRequest::fromArray([]))
```


#### listAnnouncements — `GET /announcements`

Ses Dosyaları Listesine Erişim

PHP — [`examples/operations/switch/listAnnouncements.php`](examples/operations/switch/listAnnouncements.php)

```php
$client->announcements()->listAnnouncements()
```


#### listBlockedNumbers — `GET /blocked_numbers`

Kara Listeye Erişim

PHP — [`examples/operations/switch/listBlockedNumbers.php`](examples/operations/switch/listBlockedNumbers.php)

```php
$client->blacklist()->listBlockedNumbers(ListBlockedNumbersRequest::fromArray([]))
```


#### listCallerIds — `GET /caller_ids`

Dış Numaralar Listesine Erişim

PHP — [`examples/operations/switch/listCallerIds.php`](examples/operations/switch/listCallerIds.php)

```php
$client->callerIds()->listCallerIds()
```


#### listCdrs — `GET /cdrs`

Çağrı Detay Kayıtları (CDR) Listesi

PHP — [`examples/operations/switch/listCdrs.php`](examples/operations/switch/listCdrs.php)

```php
$client->records()->listCdrs(ListCdrsRequest::fromArray([]))
```


#### listContactGroups — `GET /contact_groups`

Grup Listesine Erişim

PHP — [`examples/operations/switch/listContactGroups.php`](examples/operations/switch/listContactGroups.php)

```php
$client->contacts()->listContactGroups()
```


#### listContacts — `GET /contacts`

Kişiler Listesine Erişim

PHP — [`examples/operations/switch/listContacts.php`](examples/operations/switch/listContacts.php)

```php
$client->contacts()->listContacts(ListContactsRequest::fromArray([]))
```


#### listExtensions — `GET /extensions`

Dahili Listesi

PHP — [`examples/operations/switch/listExtensions.php`](examples/operations/switch/listExtensions.php)

```php
$client->users()->listExtensions()
```


#### listFaxOrders — `GET /fax_orders`

Tamamlanmamış Faks Gönderimlerinin Listesi

PHP — [`examples/operations/switch/listFaxOrders.php`](examples/operations/switch/listFaxOrders.php)

```php
$client->fax()->listFaxOrders(ListFaxOrdersRequest::fromArray([]))
```


#### listFaxRecords — `GET /fdrs`

Faks Listesine Erişim

PHP — [`examples/operations/switch/listFaxRecords.php`](examples/operations/switch/listFaxRecords.php)

```php
$client->fax()->listFaxRecords(ListFaxRecordsRequest::fromArray([]))
```


#### listQueuePendingCalls — `GET /queues/pending`

Kuyrukta Bekleyenler Listesine Erişim

PHP — [`examples/operations/switch/listQueuePendingCalls.php`](examples/operations/switch/listQueuePendingCalls.php)

```php
$client->queues()->listQueuePendingCalls()
```


#### listQueueUsers — `GET /queue/user_list`

Kuyruktaki Dahili Listesine Erişim

PHP — [`examples/operations/switch/listQueueUsers.php`](examples/operations/switch/listQueueUsers.php)

```php
$client->queues()->listQueueUsers(ListQueueUsersRequest::fromArray([
    'queueNumber' => '200',
]))
```


#### listQueues — `GET /queues`

Kuyruklar Listesine Erişim

PHP — [`examples/operations/switch/listQueues.php`](examples/operations/switch/listQueues.php)

```php
$client->queues()->listQueues()
```


#### listUserStatuses — `GET /user_statuses`

Dahili Durumlarını Listeleme

PHP — [`examples/operations/switch/listUserStatuses.php`](examples/operations/switch/listUserStatuses.php)

```php
$client->users()->listUserStatuses(ListUserStatusesRequest::fromArray([]))
```


#### listVoicemailMessages — `GET /voicemail_messages`

Telesekreter Arama Kayıtlarına Erişim

PHP — [`examples/operations/switch/listVoicemailMessages.php`](examples/operations/switch/listVoicemailMessages.php)

```php
$client->records()->listVoicemailMessages(ListVoicemailMessagesRequest::fromArray([]))
```


#### manageQueueUsers — `GET /queue/manage_users`

Kuyruğa Dahili Ekleme, Çıkarma veya Yer Değiştirme

PHP — [`examples/operations/switch/manageQueueUsers.php`](examples/operations/switch/manageQueueUsers.php)

```php
$client->queues()->manageQueueUsers(ManageQueueUsersRequest::fromArray([
    'queueNumber' => '200',
    'userList' => '1000,1001,1002',
]))
```


#### originate — `POST /originate`

Çağrı Başlatma (POST)

PHP — [`examples/operations/switch/originate.php`](examples/operations/switch/originate.php)

```php
$client->calls()->originate(OriginateRequest::fromArray([
    'extension' => '1001',
    'destination' => '908505320000',
]))
```


#### originateLegacy — `GET /originate`

Çağrı Başlatma (GET)

PHP — [`examples/operations/switch/originateLegacy.php`](examples/operations/switch/originateLegacy.php)

```php
$client->calls()->originateLegacy(OriginateLegacyRequest::fromArray([
    'extension' => '1001',
    'destination' => '908505320000',
]))
```


#### setCallMute — `GET /mute/{id}`

Çağrıyı Sessize Alma / Sesli Yapma

PHP — [`examples/operations/switch/setCallMute.php`](examples/operations/switch/setCallMute.php)

```php
$client->calls()->setCallMute(SetCallMuteRequest::fromArray([
    'id' => 'f3797dfc-a818-11e7-bf70-cb295b6663ce',
    'state' => 'on',
]))
```


#### setDnd — `GET /dnd/{id}`

Dahili için Rahatsız Etme (DND) Modunu Ayarlama

PHP — [`examples/operations/switch/setDnd.php`](examples/operations/switch/setDnd.php)

```php
$client->users()->setDnd(SetDndRequest::fromArray([
    'id' => '1001',
    'state' => 'on',
]))
```


#### transfer — `POST /transfer`

Çağrıyı Aktarma (POST)

PHP — [`examples/operations/switch/transfer.php`](examples/operations/switch/transfer.php)

```php
$client->calls()->transfer(TransferRequest::fromArray([
    'id' => 'f3797dfc-a818-11e7-bf70-cb295b6663ce',
    'userNumber' => '1000',
]))
```


#### transferLegacy — `GET /transfer/{id}`

Çağrıyı Aktarma (GET)

PHP — [`examples/operations/switch/transferLegacy.php`](examples/operations/switch/transferLegacy.php)

```php
$client->calls()->transferLegacy(TransferLegacyRequest::fromArray([
    'id' => 'f3797dfc-a818-11e7-bf70-cb295b6663ce',
    'userNumber' => '1000',
]))
```


#### updateAnnouncement — `PATCH /announcements/{id}`

Ses Dosyası Güncelleme

PHP — [`examples/operations/switch/updateAnnouncement.php`](examples/operations/switch/updateAnnouncement.php)

```php
$client->announcements()->updateAnnouncement(UpdateAnnouncementRequest::fromArray([
    'id' => '123',
]))
```


#### updateContact — `PATCH /contacts/{id}`

Kişi Güncelleme

PHP — [`examples/operations/switch/updateContact.php`](examples/operations/switch/updateContact.php)

```php
$client->contacts()->updateContact(UpdateContactRequest::fromArray([
    'id' => 1,
]))
```


#### updateContactGroup — `PATCH /contact_groups/{id}`

Grup Güncelleme

PHP — [`examples/operations/switch/updateContactGroup.php`](examples/operations/switch/updateContactGroup.php)

```php
$client->contacts()->updateContactGroup(UpdateContactGroupRequest::fromArray([
    'id' => 1,
    'name' => 'Arkadaşlarım',
]))
```


#### updateCrmIntegrations — `POST /crm_integrations`

CRM Entegrasyon Ayarlarını Güncelleme

PHP — [`examples/operations/switch/updateCrmIntegrations.php`](examples/operations/switch/updateCrmIntegrations.php)

```php
$client->crm()->updateCrmIntegrations(UpdateCrmIntegrationsRequest::fromArray([]))
```


#### updateIvrCampaign — `PATCH /ivr_campaigns/{id}.json`

Otomatik Arama Kampanyasını Başlatma/Durdurma

PHP — [`examples/operations/switch/updateIvrCampaign.php`](examples/operations/switch/updateIvrCampaign.php)

```php
$client->ivrCampaigns()->updateIvrCampaign(UpdateIvrCampaignRequest::fromArray([
    'id' => '123',
    'status' => 'on',
]))
```


#### updateOutboundCallerId — `GET /update_outbound_caller_id`

Dahilinin Dış Numarasını (Arayan No) Değiştirme

PHP — [`examples/operations/switch/updateOutboundCallerId.php`](examples/operations/switch/updateOutboundCallerId.php)

```php
$client->callerIds()->updateOutboundCallerId(UpdateOutboundCallerIdRequest::fromArray([
    'extension' => '1000',
    'callerId' => '90850532xxxx',
]))
```


### WhatsApp

#### getMessage — `GET /v1/messages/{message_ref}`

Mesaj Kaydını Sorgula

PHP — [`examples/operations/whatsapp/getMessage.php`](examples/operations/whatsapp/getMessage.php)

```php
$client->messages()->getMessage(GetMessageRequest::fromArray([
    'messageRef' => '3f2504e0-4f89-41d3-9a0c-0305e82c3301',
]))
```


#### health — `GET /health`

Health check

PHP — [`examples/operations/whatsapp/health.php`](examples/operations/whatsapp/health.php)

```php
$client->health()->health()
```


#### listMessages — `GET /v1/messages`

Mesajları Listele / Ara

PHP — [`examples/operations/whatsapp/listMessages.php`](examples/operations/whatsapp/listMessages.php)

```php
$client->messages()->listMessages(ListMessagesRequest::fromArray([]))
```


#### sendBulk — `POST /v1/messages/bulk`

Toplu Şablon Mesajı Gönder

PHP — [`examples/operations/whatsapp/sendBulk.php`](examples/operations/whatsapp/sendBulk.php)

```php
$client->messages()->sendBulk(SendBulkRequest::fromArray([
    'templateName' => 'odeme_hatirlatici',
    'recipients' => [
        [
            'to' => '905001112233',
        ],
    ],
]))
```


#### sendOtp — `POST /v1/messages/otp`

OTP / Kimlik Doğrulama Mesajı Gönder

PHP — [`examples/operations/whatsapp/sendOtp.php`](examples/operations/whatsapp/sendOtp.php)

```php
$client->messages()->sendOtp(SendOtpRequest::fromArray([
    'to' => '905001112233',
    'templateName' => 'siparis_onay',
]))
```


#### sendUtility — `POST /v1/messages/utility`

Utility / İşlemsel Mesaj Gönder

PHP — [`examples/operations/whatsapp/sendUtility.php`](examples/operations/whatsapp/sendUtility.php)

```php
$client->messages()->sendUtility(SendUtilityRequest::fromArray([
    'to' => '905001112233',
    'templateName' => 'siparis_onay',
]))
```

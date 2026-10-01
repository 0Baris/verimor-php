<?php

declare(strict_types=1);

namespace BarisCemant\Verimor\SwitchApi;

use BarisCemant\Verimor\Http\ClientFactory;
use BarisCemant\Verimor\Http\StatusAwareClient;
use BarisCemant\Verimor\SwitchApi\Config\SwitchConfig;
use BarisCemant\Verimor\SwitchApi\Dto\OriginateRequest;
use BarisCemant\Verimor\SwitchApi\Generated\Configuration;
use BarisCemant\Verimor\SwitchApi\Service\AnnouncementsService;
use BarisCemant\Verimor\SwitchApi\Service\BlacklistService;
use BarisCemant\Verimor\SwitchApi\Service\CallerIdsService;
use BarisCemant\Verimor\SwitchApi\Service\CallsService;
use BarisCemant\Verimor\SwitchApi\Service\ContactsService;
use BarisCemant\Verimor\SwitchApi\Service\CrmService;
use BarisCemant\Verimor\SwitchApi\Service\FaxService;
use BarisCemant\Verimor\SwitchApi\Service\IvrCampaignsService;
use BarisCemant\Verimor\SwitchApi\Service\QueuesService;
use BarisCemant\Verimor\SwitchApi\Service\RecordsService;
use BarisCemant\Verimor\SwitchApi\Service\UsersService;
use GuzzleHttp\ClientInterface;

final class SwitchClient
{
    /** @var RawApis */ private $raw;
    /** @var AnnouncementsService */ private $announcements;
    /** @var BlacklistService */ private $blacklist;
    /** @var CallerIdsService */ private $callerIds;
    /** @var CallsService */ private $calls;
    /** @var ContactsService */ private $contacts;
    /** @var CrmService */ private $crm;
    /** @var FaxService */ private $fax;
    /** @var IvrCampaignsService */ private $ivrCampaigns;
    /** @var QueuesService */ private $queues;
    /** @var RecordsService */ private $records;
    /** @var UsersService */ private $users;

    public function __construct(SwitchConfig $config, ?ClientInterface $httpClient = null)
    {
        $httpClient = new StatusAwareClient($httpClient ?? ClientFactory::create($config));
        $generated = (new Configuration())->setHost($config->baseUrl())->setApiKey('key', $config->apiKey());
        $this->raw = new RawApis($httpClient, $generated);
        $credentials = ['key' => $config->apiKey()];
        $this->announcements = new AnnouncementsService($this->raw->anonsYonetimi(), $credentials);
        $this->blacklist = new BlacklistService($this->raw->karaListeYonetimi(), $credentials);
        $this->callerIds = new CallerIdsService($this->raw->disNumaralarYonetimi(), $credentials);
        $this->calls = new CallsService($this->raw->cagriYonetimi(), $credentials);
        $this->contacts = new ContactsService($this->raw->rehberYonetimi(), $credentials);
        $this->crm = new CrmService($this->raw->crmEntegrasyonu(), $credentials);
        $this->fax = new FaxService($this->raw->faksYonetimi(), $credentials);
        $this->ivrCampaigns = new IvrCampaignsService($this->raw->otomatikAramaKampanyalari(), $credentials);
        $this->queues = new QueuesService($this->raw->kuyrukYonetimi(), $credentials);
        $this->records = new RecordsService($this->raw->raporlamaVeKayitlar(), $credentials);
        $this->users = new UsersService($this->raw->kullaniciYonetimi(), $credentials);
    }

    public function announcements(): AnnouncementsService
    {
        return $this->announcements;
    }

    public function blacklist(): BlacklistService
    {
        return $this->blacklist;
    }

    public function callerIds(): CallerIdsService
    {
        return $this->callerIds;
    }

    public function calls(): CallsService
    {
        return $this->calls;
    }

    public function contacts(): ContactsService
    {
        return $this->contacts;
    }

    public function crm(): CrmService
    {
        return $this->crm;
    }

    public function fax(): FaxService
    {
        return $this->fax;
    }

    public function ivrCampaigns(): IvrCampaignsService
    {
        return $this->ivrCampaigns;
    }

    public function queues(): QueuesService
    {
        return $this->queues;
    }

    public function records(): RecordsService
    {
        return $this->records;
    }

    public function users(): UsersService
    {
        return $this->users;
    }

    public function raw(): RawApis
    {
        return $this->raw;
    }

    /** @return string */
    public function originate(OriginateRequest $request)
    {
        return $this->calls->originate($request);
    }
}

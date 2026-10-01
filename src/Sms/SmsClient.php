<?php

declare(strict_types=1);

namespace BarisCemant\Verimor\Sms;

use BarisCemant\Verimor\Http\ClientFactory;
use BarisCemant\Verimor\Http\StatusAwareClient;
use BarisCemant\Verimor\Sms\Config\SmsConfig;
use BarisCemant\Verimor\Sms\Dto\SendRequest;
use BarisCemant\Verimor\Sms\Dto\StatusRequest;
use BarisCemant\Verimor\Sms\Generated\Configuration;
use BarisCemant\Verimor\Sms\Service\BalancesService;
use BarisCemant\Verimor\Sms\Service\BlacklistService;
use BarisCemant\Verimor\Sms\Service\CampaignsService;
use BarisCemant\Verimor\Sms\Service\IysService;
use BarisCemant\Verimor\Sms\Service\ReportsService;
use BarisCemant\Verimor\Sms\Service\SenderIdsService;
use GuzzleHttp\ClientInterface;
use InvalidArgumentException;

final class SmsClient
{
    /** @var RawApis */
    private $raw;
    /** @var BalancesService */
    private $balances;
    /** @var BlacklistService */
    private $blacklist;
    /** @var CampaignsService */
    private $campaigns;
    /** @var IysService */
    private $iys;
    /** @var ReportsService */
    private $reports;
    /** @var SenderIdsService */
    private $senderIds;

    public function __construct(SmsConfig $config, ?ClientInterface $httpClient = null)
    {
        $httpClient = new StatusAwareClient($httpClient ?? ClientFactory::create($config));
        $generated = (new Configuration())->setHost($config->baseUrl());
        $this->raw = new RawApis($httpClient, $generated);
        $credentials = ['username' => $config->username(), 'password' => $config->password()];
        $defaults = $config->sourceAddr() === null ? [] : ['source_addr' => $config->sourceAddr()];
        $this->balances = new BalancesService($this->raw->bakiyeSorgulamalari(), $credentials, $defaults);
        $this->blacklist = new BlacklistService($this->raw->karaListe(), $credentials, $defaults);
        $this->campaigns = new CampaignsService($this->raw->smsKampanyasi(), $credentials, $defaults);
        $this->iys = new IysService($this->raw->iysHizmetleri(), $credentials, $defaults);
        $this->reports = new ReportsService($this->raw->raporlar(), $credentials, $defaults);
        $this->senderIds = new SenderIdsService($this->raw->basliklar(), $credentials, $defaults);
    }

    public function balances(): BalancesService
    {
        return $this->balances;
    }

    public function blacklist(): BlacklistService
    {
        return $this->blacklist;
    }

    public function campaigns(): CampaignsService
    {
        return $this->campaigns;
    }

    public function iys(): IysService
    {
        return $this->iys;
    }

    public function reports(): ReportsService
    {
        return $this->reports;
    }

    public function senderIds(): SenderIdsService
    {
        return $this->senderIds;
    }

    public function raw(): RawApis
    {
        return $this->raw;
    }

    /**
     * @param array<string, mixed> $payload
     * @return string
     */
    public function send(array $payload)
    {
        if (array_key_exists('source_addr', $payload)) {
            if (array_key_exists('sourceAddr', $payload)) {
                throw new InvalidArgumentException('Use only one of source_addr or sourceAddr');
            }
            $payload['sourceAddr'] = $payload['source_addr'];
            unset($payload['source_addr']);
        }
        return $this->sendRequest(SendRequest::fromArray($payload));
    }

    /** @return string */
    public function sendRequest(SendRequest $request)
    {
        return $this->campaigns->send($request);
    }

    /** @return void */
    public function balance()
    {
        $this->balances->balance();
    }

    /** @return \BarisCemant\Verimor\Sms\Generated\Model\GetSmsStatus200ResponseInner[]|string */
    public function statusById(int $id)
    {
        return $this->reports->status(new StatusRequest(null, null, null, $id));
    }

    /** @return \BarisCemant\Verimor\Sms\Generated\Model\GetSmsStatus200ResponseInner[]|string */
    public function statusByCustomId(string $customId)
    {
        return $this->reports->status(new StatusRequest($customId));
    }
}

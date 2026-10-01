<?php

declare(strict_types=1);

namespace BarisCemant\Verimor\WhatsApp;

use BarisCemant\Verimor\Http\ClientFactory;
use BarisCemant\Verimor\Http\StatusAwareClient;
use BarisCemant\Verimor\Exception\UnexpectedResponseException;
use BarisCemant\Verimor\Exception\VerimorApiException;
use BarisCemant\Verimor\WhatsApp\Config\WhatsAppConfig;
use BarisCemant\Verimor\WhatsApp\Dto\SendOtpRequest;
use BarisCemant\Verimor\WhatsApp\Dto\SendUtilityRequest;
use BarisCemant\Verimor\WhatsApp\Generated\Configuration;
use BarisCemant\Verimor\WhatsApp\Generated\Model\HTTPValidationError;
use BarisCemant\Verimor\WhatsApp\Generated\Model\MessageResponse;
use BarisCemant\Verimor\WhatsApp\Service\HealthService;
use BarisCemant\Verimor\WhatsApp\Service\MessagesService;
use GuzzleHttp\ClientInterface;

final class WhatsAppClient
{
    /** @var RawApis */ private $raw;
    /** @var HealthService */ private $health;
    /** @var MessagesService */ private $messages;

    public function __construct(WhatsAppConfig $config, ?ClientInterface $httpClient = null)
    {
        $httpClient = new StatusAwareClient($httpClient ?? ClientFactory::create($config));
        $generated = (new Configuration())->setHost($config->baseUrl());
        $this->raw = new RawApis($httpClient, $generated);
        $credentials = ['x-api-key' => $config->apiKey()];
        $this->health = new HealthService($this->raw->health(), $credentials);
        $this->messages = new MessagesService($this->raw->messages(), $credentials);
    }

    public function health(): HealthService
    {
        return $this->health;
    }

    public function messages(): MessagesService
    {
        return $this->messages;
    }

    public function raw(): RawApis
    {
        return $this->raw;
    }

    /** @return MessageResponse */
    public function sendOtp(SendOtpRequest $request)
    {
        try {
            return $this->validateMessageResponse(
                $this->messages->sendOtp($request),
                'send_otp_v1_messages_otp_post'
            );
        } catch (VerimorApiException $error) {
            if ($error->statusCode() >= 200 && $error->statusCode() < 300) {
                throw new UnexpectedResponseException(
                    'whatsapp',
                    'send_otp_v1_messages_otp_post',
                    'malformed JSON body'
                );
            }
            throw $error;
        }
    }

    /** @return MessageResponse */
    public function sendUtility(SendUtilityRequest $request)
    {
        try {
            return $this->validateMessageResponse(
                $this->messages->sendUtility($request),
                'send_utility_v1_messages_utility_post'
            );
        } catch (VerimorApiException $error) {
            if ($error->statusCode() >= 200 && $error->statusCode() < 300) {
                throw new UnexpectedResponseException(
                    'whatsapp',
                    'send_utility_v1_messages_utility_post',
                    'malformed JSON body'
                );
            }
            throw $error;
        }
    }

    /** @param MessageResponse|HTTPValidationError $response */
    private function validateMessageResponse($response, string $operationId): MessageResponse
    {
        if (
            !$response instanceof MessageResponse
            || !$response->valid()
            || $response->getId() === ''
            || $response->getStatus() === ''
        ) {
            throw new UnexpectedResponseException(
                'whatsapp',
                $operationId,
                'required id or status is missing'
            );
        }
        return $response;
    }
}

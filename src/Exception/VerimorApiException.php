<?php

declare(strict_types=1);

namespace BarisCemant\Verimor\Exception;

use RuntimeException;

final class VerimorApiException extends RuntimeException
{
    /** @var string */
    private $product;
    /** @var int */
    private $statusCode;
    /** @var array<mixed>|string|null */
    private $responseBody;
    /** @var string */
    private $operationId;

    /** @param array<mixed>|string|null $body */
    public function __construct(string $product, int $statusCode, $body, string $operationId)
    {
        parent::__construct(sprintf(
            'Verimor %s operation %s failed with HTTP %d',
            $product,
            $operationId,
            $statusCode
        ), $statusCode);
        $this->product = $product;
        $this->statusCode = $statusCode;
        $this->responseBody = $body;
        $this->operationId = $operationId;
    }

    public function product(): string
    {
        return $this->product;
    }

    public function statusCode(): int
    {
        return $this->statusCode;
    }

    /** @return array<mixed>|string|null */
    public function body()
    {
        return $this->responseBody;
    }

    public function operationId(): string
    {
        return $this->operationId;
    }
}

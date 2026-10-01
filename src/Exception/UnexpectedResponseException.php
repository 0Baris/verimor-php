<?php

declare(strict_types=1);

namespace BarisCemant\Verimor\Exception;

use RuntimeException;

final class UnexpectedResponseException extends RuntimeException
{
    /** @var string */
    private $product;
    /** @var string */
    private $operationId;

    public function __construct(string $product, string $operationId, string $reason)
    {
        parent::__construct(sprintf(
            'Verimor %s operation %s returned an unexpected response: %s',
            $product,
            $operationId,
            $reason
        ));
        $this->product = $product;
        $this->operationId = $operationId;
    }

    public function product(): string
    {
        return $this->product;
    }

    public function operationId(): string
    {
        return $this->operationId;
    }
}

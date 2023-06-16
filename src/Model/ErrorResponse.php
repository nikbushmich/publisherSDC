<?php

declare(strict_types=1);

namespace App\Model;

use OpenApi\Attributes as OA;

class ErrorResponse
{
    public function __construct(private readonly string $message, private mixed $details = null)
    {
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    #[OA\Property(type: 'object')]
    public function getDetails(): mixed
    {
        return $this->details;
    }
}

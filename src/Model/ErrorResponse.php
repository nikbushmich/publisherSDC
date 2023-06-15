<?php

declare(strict_types=1);

namespace App\Model;

class ErrorResponse
{
    public function __construct(private readonly string $message, private mixed $details = null)
    {
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getDetails(): mixed
    {
        return $this->details;
    }
}

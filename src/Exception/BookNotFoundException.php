<?php

declare(strict_types=1);

namespace App\Exception;

class BookNotFoundException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('book not found');
    }
}

<?php

declare(strict_types=1);

namespace App\Exception;

class BookCategoryNotFoundException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('book category not found');
    }
}

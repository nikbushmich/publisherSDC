<?php

namespace App\Service;

use App\Model\BookListResponse;

interface BookServiceInterface
{
    public function getBooksByCategory(int $categoryId): BookListResponse;
}

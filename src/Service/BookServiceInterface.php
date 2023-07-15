<?php

namespace App\Service;

use App\Model\BookDetails;
use App\Model\BookListResponse;

interface BookServiceInterface
{
    public function getBooksByCategory(int $categoryId): BookListResponse;
    public function getBookById($id): BookDetails;
}

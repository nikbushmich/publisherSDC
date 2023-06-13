<?php

namespace App\Service;

use App\Model\BookCategoryListResponse;

interface BookCategoryServiceInterface
{
    public function getCategories(): BookCategoryListResponse;
}

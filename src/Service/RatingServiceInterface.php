<?php

namespace App\Service;

interface RatingServiceInterface
{
    public function calcReviewRatingForBook(int $id, int $total): float;
}

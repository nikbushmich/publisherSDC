<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\ReviewRepository;

class RatingService implements RatingServiceInterface
{
    public function __construct(private readonly ReviewRepository $reviewRepository)
    {
    }

    public function calcReviewRatingForBook(int $id, int $total): float
    {
        return $total > 0 ? $this->reviewRepository->getBookTotalRatingSum($id) / $total : 0;
    }
}

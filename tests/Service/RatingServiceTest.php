<?php

namespace App\Tests\Service;

use App\Repository\ReviewRepository;
use App\Service\RatingService;
use App\Tests\AbstractTestCase;

class RatingServiceTest extends AbstractTestCase
{
    private ReviewRepository $reviewRepositoryvie;

    protected function setUp(): void
    {
        parent::setUp();

        $this->reviewRepositoryvie = $this->createMock(ReviewRepository::class);
    }

    public function provider(): array
    {
        return [
            [25, 20, 1.25],
            [0, 5, 0],
        ];
    }

    /**
     * @dataProvider provider
     */
    public function testCalcReviewRatingForBook(int $repositoryRatingSum, int $total, float $expectedRating): void
    {
        $this->reviewRepositoryvie->expects($this->once())
            ->method('getBookTotalRatingSum')
            ->with(1)
            ->willReturn($repositoryRatingSum);

        $this->assertEquals(
            $expectedRating,
            (new RatingService($this->reviewRepositoryvie))->calcReviewRatingForBook(1, $total)
        );
    }

    public function testCalcReviewRatingForBookZeroTotal(): void
    {
        $this->reviewRepositoryvie->expects($this->never())->method('getBookTotalRatingSum');

        $this->assertEquals(
            0,
            (new RatingService($this->reviewRepositoryvie))->calcReviewRatingForBook(1, 0)
        );
    }
}

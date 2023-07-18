<?php

namespace App\Tests\Service;

use App\Entity\Review;
use App\Model\Review as ReviewMadel;
use App\Model\ReviewPage;
use App\Repository\ReviewRepository;
use App\Service\RatingService;
use App\Service\ReviewService;
use App\Tests\AbstractTestCase;

class ReviewServiceTest extends AbstractTestCase
{
    private ReviewRepository $reviewRepository;
    private RatingService $ratingService;
    private const BOOK_ID = 1;
    private const RER_PAGE = 5;

    protected function setUp(): void
    {
        parent::setUp();

        $this->reviewRepository = $this->createMock(ReviewRepository::class);
        $this->ratingService = $this->createMock(RatingService::class);
    }

    public function dataProvider(): array
    {
        return [
            [0, 0],
            [-1, 0],
            [-15, 0],
        ];
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetReviewPageByBookIdInvalidPage(int $page, int $offset): void
    {
        $this->ratingService->expects($this->once())
            ->method('calcReviewRatingForBook')
            ->with(self::BOOK_ID, 0)
            ->willReturn(0.0);

        $this->reviewRepository->expects($this->once())
            ->method('getPageByBookId')
            ->with(self::BOOK_ID, $offset, self::RER_PAGE)
            ->willReturn(new \ArrayIterator());

        $service = new ReviewService($this->reviewRepository, $this->ratingService);
        $expected = (new ReviewPage())
            ->setTotal(0)
            ->setRating(0)
            ->setPage($page)
            ->setPages(0)
            ->setPerPage(self::RER_PAGE)
            ->setItems([]);

        $this->assertEquals($expected, $service->getReviewPageByBookId(self::BOOK_ID, $page));
    }

    public function testGetReviewPageByBookId(): void
    {
        $this->ratingService->expects($this->once())
            ->method('calcReviewRatingForBook')
            ->with(self::BOOK_ID, 1)
            ->willReturn(7.0);

        $entity = (new Review())
            ->setAuthor('test author')
            ->setContent('test content')
            ->setCreatedAt(new \DateTimeImmutable('2023-06-06'))
            ->setRating(7);

        $this->setEntityId($entity, 1);

        $this->reviewRepository->expects($this->once())
            ->method('getPageByBookId')
            ->with(self::BOOK_ID, 0, self::RER_PAGE)
            ->willReturn(new \ArrayIterator([$entity]));

        $service = new ReviewService($this->reviewRepository, $this->ratingService);
        $expected = (new ReviewPage())
            ->setTotal(1)
            ->setRating(7)
            ->setPage(1)
            ->setPages(1)
            ->setPerPage(self::RER_PAGE)
            ->setItems([
                (new ReviewMadel())->setId(1)->setRating(7)->setCreatedAt(1686009600)
            ->setContent('test content')->setAuthor('test author'),
            ]);

        $this->assertEquals($expected, $service->getReviewPageByBookId(self::BOOK_ID, 1));
    }
}

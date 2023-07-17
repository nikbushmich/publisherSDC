<?php

namespace App\Tests\Service;

use App\Entity\Book;
use App\Exception\BookCategoryNotFoundException;
use App\Model\BookListItem;
use App\Model\BookListResponse;
use App\Repository\BookCategoryRepository;
use App\Repository\BookRepository;
use App\Repository\ReviewRepository;
use App\Service\BookService;
use App\Service\BookServiceInterface;
use App\Service\RatingServiceInterface;
use App\Tests\AbstractTestCase;
use Doctrine\Common\Collections\ArrayCollection;

class BookServiceTest extends AbstractTestCase
{
    private ReviewRepository $reviewRepository;
    private BookRepository $bookRepository;
    private BookCategoryRepository $bookCategoryRepository;
    private RatingServiceInterface $ratingService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->reviewRepository = $this->createMock(ReviewRepository::class);
        $this->bookRepository = $this->createMock(BookRepository::class);
        $this->bookCategoryRepository = $this->createMock(BookCategoryRepository::class);
        $this->ratingService = $this->createMock(RatingServiceInterface::class);
    }

    public function testGetBooksByCategoryNotFound()
    {
        $this->bookCategoryRepository->expects($this->once())
            ->method('existsById')
            ->with(130)
            ->willReturn(false);

        $this->expectException(BookCategoryNotFoundException::class);

        $this->createBookService()->getBooksByCategory(130);
    }

    public function testGetBooksByCategory()
    {
        $this->bookRepository->expects($this->once())
            ->method('findBooksByCategoryId')
            ->with(130)
            ->willReturn([$this->createBookEntity()]);

        $this->bookCategoryRepository->expects($this->once())
            ->method('existsById')
            ->with(130)
            ->willReturn(true);

        $expected = new BookListResponse([$this->createBookItemModel()]);

        $this->assertEquals($expected, $this->createBookService()->getBooksByCategory(130));
    }

    private function createBookService(): BookServiceInterface
    {
        return new BookService(
            $this->bookRepository,
            $this->bookCategoryRepository,
            $this->reviewRepository,
            $this->ratingService,
        );
    }

    private function createBookEntity(): Book
    {
        $book = (new Book())
            ->setTitle('test title')
            ->setSlug('test_slug')
            ->setMeap(false)
            ->setIsbn('123321')
            ->setDescription('test description')
            ->setAuthors(['test author'])
            ->setImage('http://localhost/testimage.png')
            ->setCategories(new ArrayCollection())
            ->setPublicationDate(new \DateTimeImmutable('2023-06-05'));

        $this->setEntityId($book, 123);

        return $book;
    }

    private function createBookItemModel(): BookListItem
    {
        return (new BookListItem())
            ->setId(123)
            ->setTitle('test title')
            ->setSlug('test_slug')
            ->setMeap(false)
            ->setAuthors(['test author'])
            ->setImage('http://localhost/testimage.png')
            ->setPublicationDate(1685923200);
    }
}

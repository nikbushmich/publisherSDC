<?php

namespace App\Tests\Service;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Exception\BookCategoryNotFoundException;
use App\Model\BookListItem;
use App\Model\BookListResponse;
use App\Repository\BookCategoryRepository;
use App\Repository\BookRepository;
use App\Repository\ReviewRepository;
use App\Service\BookService;
use App\Tests\AbstractTestCase;
use Doctrine\Common\Collections\ArrayCollection;

class BookServiceTest extends AbstractTestCase
{
    public function testGetBooksByCategoryNotFound()
    {
        $reviewRepository = $this->createMock(ReviewRepository::class);
        $bookRepository = $this->createMock(BookRepository::class);
        $bookCategoryRepository = $this->createMock(BookCategoryRepository::class);
        $bookCategoryRepository->expects($this->once())
            ->method('existsById')
            ->with(130)
            ->willReturn(false);

        $this->expectException(BookCategoryNotFoundException::class);

        (new BookService($bookRepository, $bookCategoryRepository, $reviewRepository))->getBooksByCategory(130);
    }

    public function testGetBooksByCategory()
    {
        $reviewRepository = $this->createMock(ReviewRepository::class);
        $bookRepository = $this->createMock(BookRepository::class);
        $bookRepository->expects($this->once())
            ->method('findBooksByCategoryId')
            ->with(130)
            ->willReturn([$this->createBookEntity()]);

        $bookCategoryRepository = $this->createMock(BookCategoryRepository::class);
        $bookCategoryRepository->expects($this->once())
            ->method('existsById')
            ->with(130)
            ->willReturn(true);

        $service = new BookService($bookRepository, $bookCategoryRepository, $reviewRepository);
        $expected = new BookListResponse([$this->createBookItemModel()]);

        $this->assertEquals($expected, $service->getBooksByCategory(130));
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

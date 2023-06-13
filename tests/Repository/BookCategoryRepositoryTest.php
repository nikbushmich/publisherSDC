<?php

namespace App\Tests\Repository;

use App\Entity\BookCategory;
use App\Repository\BookCategoryRepository;
use App\Tests\AbstractRepositoryTest;

class BookCategoryRepositoryTest extends AbstractRepositoryTest
{
    private BookCategoryRepository $bookCategoryRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bookCategoryRepository = $this->getRepositoryForEntity(BookCategory::class);
    }

    public function testFindAllSortedByTitle()
    {
        $category1 = (new BookCategory())->setTitle('category1')->setSlug('category1');
        $category2 = (new BookCategory())->setTitle('category2')->setSlug('category2');
        $category3 = (new BookCategory())->setTitle('category3')->setSlug('category3');

        foreach ([$category1, $category2, $category3] as $category) {
            $this->em->persist($category);
        }

        $this->em->flush();

        $titles = array_map(
            fn (BookCategory $bookCategory) => $bookCategory->getTitle(),
            $this->bookCategoryRepository->findAllSortedByTitle(),
        );

        $this->assertEquals(['category1', 'category2', 'category3'], $titles);
    }
}

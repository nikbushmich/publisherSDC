<?php

namespace App\Tests\Service;

use App\Entity\BookCategory;
use App\Model\BookCategory as BookCategoryModel;
use App\Model\BookCategoryListResponse;
use App\Repository\BookCategoryRepository;
use App\Service\BookCategoryService;
use App\Tests\AbstractTestCase;

class BookCategoryServiceTest extends AbstractTestCase
{
    public function testGetCategories(): void
    {
        $category = (new BookCategory())->setTitle('unit_test')->setSlug('slug_test');
        $this->setEntityId($category, 9);

        $repository = $this->createMock(BookCategoryRepository::class);
        $repository->expects($this->once())
            ->method('findAllSortedByTitle')
            ->willReturn([$category]);

        $service = new BookCategoryService($repository);
        $expected = new BookCategoryListResponse([new BookCategoryModel(9, 'unit_test', 'slug_test')]);

        $this->assertEquals($expected, $service->getCategories());
    }
}

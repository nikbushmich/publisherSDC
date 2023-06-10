<?php

namespace App\Tests\Controller;

use App\Controller\BookCategoryController;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookCategoryControllerTest extends WebTestCase
{
    public function testCategories(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/v1/book/categories');
        $responseContent = $client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertJsonStringEqualsJsonFile(
//            '{"items":[{"id":1,"title":"title 1","slug":"slug-1"},{"id":2,"title":"title 2","slug":"slug-2"}]}',
            __DIR__ . '/responses/BookCategoryControllerTest_testCategories.json',
            $responseContent
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Entity\BookFormat;
use App\Entity\BookToBookFormat;
use App\Tests\AbstractControllerTest;
use Doctrine\Common\Collections\ArrayCollection;

class BookControllerTest extends AbstractControllerTest
{
    public function testBooksByCategory(): void
    {
        $categoryId = $this->createCategory();

        $this->client->request('GET', '/api/v1/category/'.$categoryId.'/books');
        $responseContent = json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertResponseIsSuccessful();
        self::assertJsonDocumentMatchesSchema($responseContent, [
            'type' => 'object',
            'required' => ['items'],
            'properties' => [
                'items' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'required' => ['id', 'title', 'slug', 'image', 'authors', 'meap', 'publicationDate'],
                        'properties' => [
                            'title' => ['type' => 'string'],
                            'slug' => ['type' => 'string'],
                            'id' => ['type' => 'integer'],
                            'publicationDate' => ['type' => 'integer'],
                            'image' => ['type' => 'string'],
                            'meap' => ['type' => 'boolean'],
                            'authors' => [
                                'type' => 'array',
                                'items' => ['type' => 'string'],
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function testBookById(): void
    {
        $bookId = $this->createBook();

        $this->client->request('GET', '/api/v1/book/'.$bookId);
        $responseContent = json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertResponseIsSuccessful();
        self::assertJsonDocumentMatchesSchema($responseContent, [
            'type' => 'object',
            'required' => [
                'id', 'title', 'slug', 'image', 'authors', 'meap', 'publicationDate', 'rating', 'reviews',
                'categories', 'formats',
            ],
            'properties' => [
                'title' => ['type' => 'string'],
                'slug' => ['type' => 'string'],
                'id' => ['type' => 'integer'],
                'publicationDate' => ['type' => 'integer'],
                'image' => ['type' => 'string'],
                'meap' => ['type' => 'boolean'],
                'authors' => [
                    'type' => 'array',
                    'items' => ['type' => 'string'],
                ],
                'rating' => ['type' => 'number'],
                'reviews' => ['type' => 'integer'],
                'categories' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'required' => ['id', 'title', 'slug'],
                        'properties' => [
                            'title' => ['type' => 'string'],
                            'slug' => ['type' => 'string'],
                            'id' => ['type' => 'integer'],
                        ],
                    ],
                ],
            ],
        ]);
    }

    private function createCategory(): int
    {
        $bookCategory = (new BookCategory())->setSlug('category1')->setTitle('category1');
        $this->em->persist($bookCategory);

        $this->em->persist((new Book())
            ->setTitle('Name book test1')
            ->setPublicationDate(new \DateTimeImmutable())
            ->setMeap(false)
            ->setIsbn('123321')
            ->setDescription('test description')
            ->setAuthors(['Name Author test1'])
            ->setSlug('test1_slug')
            ->setCategories(new ArrayCollection([$bookCategory]))
            ->setImage('https://localhost.jpg'));

        $this->em->flush();

        return $bookCategory->getId();
    }

    private function createBook(): int
    {
        $bookCategory = (new BookCategory())->setSlug('category1')->setTitle('category1');
        $this->em->persist($bookCategory);

        $format = (new BookFormat())
            ->setTitle('test format title')
            ->setDescription('test format description')
            ->setComment('test format comment');
        $this->em->persist($format);

        $book = (new Book())
            ->setTitle('Name book test1')
            ->setPublicationDate(new \DateTimeImmutable())
            ->setMeap(false)
            ->setIsbn('123321')
            ->setDescription('test description')
            ->setAuthors(['Name Author test1'])
            ->setSlug('test1_slug')
            ->setCategories(new ArrayCollection([]))
            ->setImage('https://localhost.jpg');

        $this->em->persist($book);

        $join = (new BookToBookFormat())
            ->setPrice(123.123)
            ->setBook($book)
            ->setDiscountPercent(10)
            ->setFormat($format);

        $this->em->persist($join);

        $this->em->flush();

        return $book->getId();
    }
}

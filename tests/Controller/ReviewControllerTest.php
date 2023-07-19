<?php

namespace App\Tests\Controller;

use App\Entity\Book;
use App\Entity\Review;
use App\Tests\AbstractControllerTest;
use Doctrine\Common\Collections\ArrayCollection;

class ReviewControllerTest extends AbstractControllerTest
{
    public function testReviews(): void
    {
        $book = $this->createBook();
        $this->createReview($book);

        $this->em->flush();

        $this->client->request('GET', '/api/v1/book/'.$book->getId().'/reviews');
        $responseContent = json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertResponseIsSuccessful();
        self::assertJsonDocumentMatchesSchema($responseContent, [
            'type' => 'object',
            'required' => ['items', 'rating', 'page', 'pages', 'perPage', 'total'],
            'properties' => [
                'rating' => ['type' => 'number'],
                'page' => ['type' => 'integer'],
                'pages' => ['type' => 'integer'],
                'perPage' => ['type' => 'integer'],
                'total' => ['type' => 'integer'],
                'items' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'required' => ['id', 'content', 'author', 'rating', 'createdAt'],
                        'properties' => [
                            'id' => ['type' => 'integer'],
                            'rating' => ['type' => 'integer'],
                            'createdAt' => ['type' => 'integer'],
                            'content' => ['type' => 'string'],
                            'author' => ['type' => 'string'],
                        ],
                    ],
                ],
            ],
        ]);
    }

    private function createBook(): Book
    {
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

        return $book;
    }

    private function createReview(Book $book)
    {
        $this->em->persist((new Review())
            ->setAuthor('test author')
            ->setCreatedAt(new \DateTimeImmutable())
            ->setContent('test content')
            ->setRating(7)
            ->setBook($book)
        );
    }
}

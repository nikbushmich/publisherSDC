<?php

namespace App\Tests\Mapper;

use App\Entity\Book;
use App\Mapper\BookMapper;
use App\Model\BookDetails;
use App\Tests\AbstractTestCase;
use PHPUnit\Framework\TestCase;

class BookMapperTest extends AbstractTestCase
{

    public function testMap(): void
    {
        $book = (new Book())
            ->setTitle('test title')
            ->setSlug('test slug')
            ->setImage('test 123')
            ->setAuthors(['test author'])
            ->setMeap(true)
            ->setPublicationDate(new \DateTimeImmutable('2023-06-06'));

        $this->setEntityId($book, 1);

        $expected = (new BookDetails())
            ->setId(1)
            ->setSlug('test slug')
            ->setTitle('test title')
            ->setImage('test 123')
            ->setAuthors(['test author'])
            ->setMeap(true)
            ->setPublicationDate(1686009600);

        $this->assertEquals($expected, BookMapper::map($book, new BookDetails()));
    }
}

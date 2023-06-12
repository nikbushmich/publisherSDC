<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $test1Category = $this->getReference(BookCategoryFixtures::TEST_1_CATEGORY);
        $test2Category = $this->getReference(BookCategoryFixtures::TEST_2_CATEGORY);

        $book = (new Book())
            ->setTitle('Name book test1')
            ->setPublicationDate(new \DateTime('2023-06-02'))
            ->setMeap(false)
            ->setAuthors(['Name Author test1'])
            ->setSlug('test1_slug')
            ->setCategories(new ArrayCollection([$test1Category, $test2Category]))
            ->setImage('https://img3.labirint.ru/rc/f74823751ffa35c22952ced51890d70c/363x561q80/books84/832441/cover.jpg?1636986384');

        $manager->persist($book);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            BookCategoryFixtures::class,
        ];
    }
}

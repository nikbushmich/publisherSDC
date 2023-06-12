<?php

namespace App\DataFixtures;

use App\Entity\BookCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookCategoryFixtures extends Fixture
{
    public const TEST_1_CATEGORY = 'test_1';
    public const TEST_2_CATEGORY = 'test_2';

    public function load(ObjectManager $manager): void
    {
        $categories = [
          self::TEST_1_CATEGORY => (new BookCategory())->setTitle('title_1')->setSlug('slug_1'),
          self::TEST_2_CATEGORY => (new BookCategory())->setTitle('title_2')->setSlug('slug_2'),
        ];

        foreach ($categories as $category) {
            $manager->persist($category);
        }

        $manager->persist((new BookCategory())->setTitle('title_3')->setSlug('slug_3'));

        $manager->flush();

        foreach ($categories as $code => $category) {
            $this->addReference($code, $category);
        }
    }
}

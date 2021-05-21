<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    static public $names = [
        "Les grabs",
        "Les rotations",
        "Les flips",
        "Les rotations désaxées",
        "Les slides",
        "Les one foot tricks"
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::$names as $index => $name) {
            $category = (new Category())->setName($name);
            $manager->persist($category);

            $this->addReference("category_" . $index, $category);
        }
        $manager->flush();
    }
}

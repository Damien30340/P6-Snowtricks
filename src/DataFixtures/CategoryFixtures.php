<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    /**
     * @throws \Exception
     */
    public function removeDirectory($directory){
        if(!is_dir($directory)){
            throw new \Exception($directory.' is not directory '.__LINE__.', file '.__FILE__);
        }
        $iterator = new \DirectoryIterator($directory);
        foreach ($iterator as $fileinfo) {

            if (!$fileinfo->isDot()) {
                if($fileinfo->isFile()) {
                    unlink($directory.$fileinfo->getFileName());
                }
            }
        }
    }

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
        $this->removeDirectory('public/uploads/img/avatar/');
        $this->removeDirectory('public/uploads/img/tricks/');
        foreach (self::$names as $index => $name) {
            $category = (new Category())->setName($name);
            $manager->persist($category);

            $this->addReference("category_" . $index, $category);
        }
        $manager->flush();
    }
}

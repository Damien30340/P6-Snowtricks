<?php

namespace App\DataFixtures;

use App\Entity\Picture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PictureFixtures extends Fixture implements DependentFixtureInterface
{
    private static $img = [
        "/img/tricks/snowboard0.jpeg",
        "/img/tricks/snowboard1.jpeg",
        "/img/tricks/snowboard2.jpeg",
        "/img/tricks/snowboard3.jpeg",
        "/img/tricks/snowboard4.jpeg",
        "/img/tricks/snowboard5.jpeg",
        "/img/tricks/snowboard6.jpeg",
        "/img/tricks/snowboard7.jpeg",
        "/img/tricks/snowboard9.jpeg",
        "/img/tricks/snowboard10.jpeg",
        "/img/tricks/snowboard11.jpeg",
        "/img/tricks/snowboard10.jpeg",
        "/img/tricks/snowboard9.jpeg",
        "/img/tricks/snowboard8.jpeg",
        "/img/tricks/snowboard7.jpeg",
        "/img/tricks/snowboard6.jpeg",
        "/img/tricks/snowboard5.jpeg",
        "/img/tricks/snowboard4.jpeg"
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::$img as $index => $data) {
            $Picture = (new Picture())
                ->setFileName($data, Picture::FIXTURES_DIR)
                ->setTrick($this->getReference('trick_' . $index));
            $manager->persist($Picture);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            TrickFixtures::class,
        ];
    }
}

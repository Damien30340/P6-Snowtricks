<?php

namespace App\DataFixtures;

use App\Entity\TrickPicture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TrickPictureFixtures extends Fixture implements DependentFixtureInterface
{
    private static $img = [
        "/assets/img/tricks/snowboard0.jpeg",
        "/assets/img/tricks/snowboard1.jpeg",
        "/assets/img/tricks/snowboard2.jpeg",
        "/assets/img/tricks/snowboard3.jpeg",
        "/assets/img/tricks/snowboard4.jpeg",
        "/assets/img/tricks/snowboard5.jpeg",
        "/assets/img/tricks/snowboard6.jpeg",
        "/assets/img/tricks/snowboard7.jpeg",
        "/assets/img/tricks/snowboard9.jpeg",
        "/assets/img/tricks/snowboard10.jpeg",
        "/assets/img/tricks/snowboard11.jpeg",
        "/assets/img/tricks/snowboard10.jpeg",
        "/assets/img/tricks/snowboard9.jpeg",
        "/assets/img/tricks/snowboard8.jpeg",
        "/assets/img/tricks/snowboard7.jpeg",
        "/assets/img/tricks/snowboard6.jpeg"
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::$img as $index => $data) {
            $trickPicture = (new TrickPicture())
                ->setFileName($data)
                ->setTrick($this->getReference('trick_' . $index));
            $manager->persist($trickPicture);
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

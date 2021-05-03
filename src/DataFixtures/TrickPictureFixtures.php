<?php

namespace App\DataFixtures;

use App\Entity\TrickPicture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TrickPictureFixtures extends Fixture implements DependentFixtureInterface
{
    private static $img = [
        "asset('assets/img/tricks/snowboard0.jpg')",
        "asset('assets/img/tricks/snowboard1.jpg')",
        "asset('assets/img/tricks/snowboard2.jpg')",
        "asset('assets/img/tricks/snowboard3.jpg')",
        "asset('assets/img/tricks/snowboard4.jpg')",
        "asset('assets/img/tricks/snowboard5.jpg')",
        "asset('assets/img/tricks/snowboard6.jpg')",
        "asset('assets/img/tricks/snowboard7.jpg')",
        "asset('assets/img/tricks/snowboard9.jpg')",
        "asset('assets/img/tricks/snowboard10.jpg')",
        "asset('assets/img/tricks/snowboard11.jpg')"
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

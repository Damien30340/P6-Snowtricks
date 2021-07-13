<?php

namespace App\DataFixtures;

use App\Entity\Picture;
use App\Service\Uploader;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureFixtures extends Fixture implements DependentFixtureInterface
{

    private Uploader $uploader;
    private Filesystem $filesystem;

    public function __construct(Uploader $uploader, Filesystem $filesystem)
    {
        $this->uploader = $uploader;
        $this->filesystem = $filesystem;
    }

    private static $img = [
        "/tricks/snowboard1.jpeg",
        "/tricks/snowboard2.jpeg",
        "/tricks/snowboard3.jpeg",
        "/tricks/snowboard5.jpeg",
        "/tricks/snowboard4.jpeg",
        "/tricks/snowboard5.jpeg",
        "/tricks/snowboard6.jpeg",
        "/tricks/snowboard7.jpeg",
        "/tricks/snowboard8.jpeg",
        "/tricks/snowboard9.jpeg",
        "/tricks/snowboard10.jpeg",
        "/tricks/snowboard11.jpeg"
    ];

    public function load(ObjectManager $manager)
    {
        $nbTricks = count(TrickFixtures::$datas);


        for ($i = 0; $i < $nbTricks; $i++) {
            $this->filesystem->copy(__DIR__.self::$img[rand(0, count(self::$img)-1)], __DIR__."/tricks/tmp.jpeg");


            $uplodedFile = new UploadedFile(__DIR__."/tricks/tmp.jpeg",
                uniqid("img_"),
                null,
                null,
                true);

            $fileName = $this->uploader->upload($uplodedFile, Uploader::PICTURE_TRICK_DIR);


            $picture = (new Picture())
                ->setFileName($fileName, Picture::UPLOADS_DIR)
                ->setTrick($this->getReference('trick_' . $i));
            $manager->persist($picture);
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

<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Service\Uploader;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Filesystem\Filesystem;

class UserFixtures extends Fixture
{

    private static $img = "/avatar/Avatar_UPLOAD.png";

    static private $names = [
        'Jean-Paulochon',
        'Ycare Amel',
        'Théo Jasmin',
        'Rayne des neiges',
        'Franck Ofone',
        'Ève Aine-Mensiel',
        'Otto Psie',
        'Jean-Paul Ochon',
        'Emmy Grassion',
        'Tara Chide',
        'Username'
    ];
    private Uploader $uploader;
    private UserPasswordEncoderInterface $encoder;
    private Filesystem $filesystem;

    public function __construct(UserPasswordEncoderInterface $encoder, Uploader $uploader, Filesystem $filesystem)
    {
        $this->uploader = $uploader;
        $this->encoder = $encoder;
        $this->filesystem = $filesystem;
    }

    public function load(ObjectManager $manager)
    {
        foreach (self::$names as $index => $name) {
            $this->filesystem->copy(__DIR__.self::$img, __DIR__."/avatar/tmp.jpeg");

            $uploadedFile = new UploadedFile(__DIR__."/avatar/tmp.jpeg",
                uniqid("img_"),
                null,
                null,
                true);

            $index += 1;
            $user = (new User())
                ->setUsername($name)
                ->setEmail('test' . $index . '@hotmail.fr')
                ->setRoles(['ROLE_USER'])
                ->setAvatar($this->uploader->upload($uploadedFile, Uploader::AVATAR_DIR));
            $user->setPassword($this->encoder->encodePassword($user, '15071990aA@'));
            $manager->persist($user);

            $this->addReference("user_" . $index, $user);
        }
        $manager->flush();
    }
}

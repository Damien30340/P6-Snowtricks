<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Token;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

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

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        foreach (self::$names as $index => $name) {
            $index += 1;
            $user = (new User())
                ->setUsername($name)
                ->setEmail('test' . $index . '@hotmail.fr')
                ->setRoles(['ROLE_USER'])
                ->setAvatar('img/profil/avatar' . $index);
            $user->setPassword($this->encoder->encodePassword($user, '15071990aA@'));
            $manager->persist($user);
        }
        $manager->flush();
    }
}

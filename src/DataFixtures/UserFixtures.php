<?php

namespace App\DataFixtures;

use App\Entity\Token;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i <= 10; $i++) {
            $user = (new User())
                ->setUsername('test n°' . $i . '')
                ->setEmail('nozalstorn' . $i . '@hotmail.fr')
                ->setPassword('12345aB6987@')
                ->setRoles(['ROLE_USER'])
                ->setAvatar('IMG_AVATAR')
                ->setToken(new Token());

            $manager->persist($user);
        }
        $manager->flush();
    }
}

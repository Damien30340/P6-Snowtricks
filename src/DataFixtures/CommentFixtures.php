<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
       for($i=1; $i <= 11; $i++){

           $rand1 = random_int(1, 11);
           $rand2 = random_int(1, 14);

           $comment = (New Comment())
                ->setAuthor($this->getReference('user_'. $rand1))
                ->setContent('Lremmmme, comment' .$i)
                ->setTrick($this->getReference('trick_'. $rand2));
           $manager->persist($comment);
       }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            TrickFixtures::class
        ];
    }
}

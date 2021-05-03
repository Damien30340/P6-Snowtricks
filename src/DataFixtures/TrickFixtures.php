<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TrickFixtures extends Fixture implements DependentFixtureInterface
{

    private static $datas = [
        ["Le Mute", "Saisie de la carre frontside de la planche entre les deux pieds avec la main avant.", 'category_1'],
        ["Le Indy", "Saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière.", 'category_1'],
        ["Le Nose Grap", "Saisie de la partie avant de la planche, avec la main avant.", 'category_1'],
        ["Le 180", "Un 180 désigne un demi-tour, soit 180 degrés d'angle.", 'category_2'],
        ["Le 360",  "Un 360 désigne un tour complet, soit 360 degrés d'angle.", 'category_2'],
        ["Le Front Flip", "Le front flip est une rotation en avant.", 'category_3'],
        ["Le Back Flip", "Le back flip est une rotation en arrière.", 'category_3'],
        ["Le Cork", "Le diminutif de corkscrew qui signifie littéralement tire-bouchon et désignait les premières simples rotations têtes en bas en frontside. Désormais, on utilise le mot cork à toute les sauces pour qualifier les figures où le rider passe la tête en bas, peu importe le sens de rotation. Et dorénavant en compétition, on parle souvent de double cork, triple cork et certains riders vont jusqu'au quadruple cork !", 'category_4'],
        ["Le Backside air", "Le grab star du snowboard qui peut être fait d'autant de façon différentes qu'il y a de styles de riders. Il consiste à attraper la carre arrière entre les pieds, ou légèrement devant, et à pousser avec sa jambe arrière pour ramener la planche devant. C'est une figure phare en pipe ou sur un hip en backside. C'est généralement avec ce trick que les riders vont le plus haut.", 'category_4'],
        ["Le Mc Twist", "Un grand classique des rotations tête en bas qui se fait en backside, sur un mur backside de pipe. Le Mc Twist est généralement fait en japan, un grab très tweaké (action d'accentuer un grab en se contorsionnant).", 'category_5'],
        ["Le 810", "Un 810 désigne deux tours un quart, elle demande une grande amplitude", 'category_2']
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::$datas as $index => $data) {
            $trick = (new Trick())
                ->setName($data[0])
                ->setContent($data[1])
                ->setCreatedAt(new \Datetime())
                ->setCategory($this->getReference($data[2]));
            $manager->persist($trick);

            $this->addReference("trick_" . $index, $trick);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
        ];
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TrickFixtures extends Fixture implements DependentFixtureInterface
{

    public static $datas = [
        ["Le Mute", "Saisie de la carre frontside de la planche entre les deux pieds avec la main avant.", 'category_1'],
        ["Le Indy", "Saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière.", 'category_1'],
        ["Le Nose Grap", "Saisie de la partie avant de la planche, avec la main avant.", 'category_1'],
        ["Le 180", "Un 180 désigne un demi-tour, soit 180 degrés d'angle.", 'category_2'],
        ["Le 360",  "Un 360 désigne un tour complet, soit 360 degrés d'angle.", 'category_2'],
        ["Le Front Flip", "Le front flip est une rotation en avant.", 'category_3'],
        ["Le Front Flip", "Le front flip est une rotation en avant.", 'category_3'],
        ["Le Back Flip", "Le back flip est une rotation en arrière.", 'category_3'],
        ["Le Cork", "Le diminutif de corkscrew qui signifie littéralement tire-bouchon et désignait les premières simples rotations têtes en bas en frontside. Désormais, on utilise le mot cork à toute les sauces pour qualifier les figures où le rider passe la tête en bas, peu importe le sens de rotation. Et dorénavant en compétition, on parle souvent de double cork, triple cork et certains riders vont jusqu'au quadruple cork !", 'category_4'],
        ["Le Backside air", "Le grab star du snowboard qui peut être fait d'autant de façon différentes qu'il y a de styles de riders. Il consiste à attraper la carre arrière entre les pieds, ou légèrement devant, et à pousser avec sa jambe arrière pour ramener la planche devant. C'est une figure phare en pipe ou sur un hip en backside. C'est généralement avec ce trick que les riders vont le plus haut.", 'category_4'],
        ["Le Mc Twist", "Un grand classique des rotations tête en bas qui se fait en backside, sur un mur backside de pipe. Le Mc Twist est généralement fait en japan, un grab très tweaké (action d'accentuer un grab en se contorsionnant).", 'category_5'],
        ["Le 810", "Un 810 désigne deux tours un quart, elle demande une grande amplitude", 'category_2'],
        ["Le Test", "Amongst the hundreds of thousands of symbols which are in the unicode text specifications are certain characters which resemble, or are variations of the alphabet and other keyword symbols. For example, if we can take the phrase thug life and convert its characters into the fancy letters 𝖙𝖍𝖚𝖌 𝖑𝖎𝖋𝖊 which are a set of unicode symbols. These different sets of fancy text letters are scattered all throughout the unicode specification, and so to create a fancy text translator, it's just a matter of finding these sets of letters and symbols, and linking them to their normal alphabetical equivalents.Unicode has a huge number of symbols, and so we're able to create other things like a wingdings translator too. Also if you're looking for messy text, or glitchy text, visit this creepy zalgo text generator (another translator on LingoJam).", 'category_5'],
        ["Le Flush", "Or are variations of the alphabet and other keyword symbols. For example, if we can take the phrase thug life and convert its characters into the fancy letters 𝖙𝖍𝖚𝖌 𝖑𝖎𝖋𝖊 which are a set of unicode symbols. These different sets of fancy text letters are scattered all throughout the unicode specification, and so to create a fancy text translator, it's just a matter of finding these sets of letters and symbols, and linking them to their normal alphabetical equivalents.Unicode has a huge number of symbols, and so we're able to create other things like a wingdings transl creepy zalgo text generator (another translator on LingoJam).", 'category_5'],
        ["Le BougBast", "Alphabet and other keyword symbols. For example, if we can take the phrase thug life and convert its characters into the fancy letters 𝖙𝖍𝖚𝖌 𝖑𝖎𝖋𝖊 which are a set of unicode symbols.", 'category_4'],
        ["Le Pirasso", "Amongst the hundreds of thousands of symbols which are in the unicode text specifications are certain characters which resemble, or are variations of the alphabet and other keyword symbols. For example", 'category_3'],
        ["Le Persist", "it's just a matter of finding these sets of letters and symbols, and linking them to their normal alphabetical equivalents.Unicode has a huge number of symbols, and so we're able to create other things like a wingdings translator too.", 'category_1'],
        ["Le BigFast", "it's just a matter of finding these sets of letters and symbols, and linking them to their normal alphabetical equivalents.Unicode has a huge number of symbols, and so we're able to create other things like a wingdings translator too.", 'category_5']
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

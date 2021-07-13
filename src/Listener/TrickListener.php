<?php


namespace App\Listener;

use App\Entity\Trick;
use Symfony\Component\String\Slugger\SluggerInterface;


class TrickListener
{

    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function postLoad(Trick $trick): void
    {
        $trick->setSlug($this->slugger->slug($trick->getName()));
    }

    public function preFlush(Trick $trick): void
    {
        $trick->setSlug($this->slugger->slug($trick->getName()));
    }
}
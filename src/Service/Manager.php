<?php


namespace App\Service;


use Doctrine\ORM\EntityManagerInterface;

class Manager
{
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function update($obj){
        $this->manager->persist($obj);
        $this->manager->flush();
    }

    public function delete($obj){
        $this->manager->remove($obj);
        $this->manager->flush();
    }
}
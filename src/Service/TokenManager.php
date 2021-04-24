<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\TokenRepository;
use Doctrine\ORM\EntityManagerInterface;

class TokenManager
{
    private TokenRepository $tokenRepository;
    private EntityManagerInterface $manager;

    public function __construct(TokenRepository $tokenRepository, EntityManagerInterface $manager)
    {
        $this->tokenRepository = $tokenRepository;
        $this->manager = $manager;
    }

    public function generateTokenResetPass(User $user)
    {
        $tokenRepo = $this->tokenRepository->findOneBy(["id" => $user->getToken()->getId()]);
        $tokenRepo
            ->setCreatedAt(new \DateTime())
            ->setContent($tokenRepo->generateToken());
        $this->manager->persist($tokenRepo);
        $this->manager->flush();

        return $this;
    }

    public function nullableToken(User $user)
    {
        $tokenRepo = $this->tokenRepository->findOneBy(['id' => $user->getToken()]);
        $tokenRepo
            ->setContent(null)
            ->setCreatedAt(null);

        return $this;
    }
}

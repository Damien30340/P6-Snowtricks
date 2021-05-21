<?php

namespace App\Entity;

use App\Repository\TokenRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;

/**
 * @ORM\Entity(repositoryClass=TokenRepository::class)
 */
class Token
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /** 
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="token")
     */
    private User $user;

    public function getUser()
    {
        return $this->user;
    }


    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->content = $this->generateToken();
    }

    public function generateToken()
    {
        return sha1(random_bytes(10));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}

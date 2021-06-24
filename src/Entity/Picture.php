<?php

namespace App\Entity;

use App\Repository\PictureRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PictureRepository::class)
 */
class Picture
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $fileName;

    /**
     * @var File|null
     * @Assert\Image(
     *     maxSize = "4096k",
     *     mimeTypes = {"image/jpeg", "image/jpg", "image/png"}
     * )
     */
    private ?File $file = null;

    /**
     * @ORM\ManyToOne(targetEntity=Trick::class, inversedBy="pictures")
     */
    private ?Trick $trick;

    const UPLOADS_DIR = '/uploads';
    const FIXTURES_DIR = '/assets';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileName(): ?string
    {
        return (!empty($this->fileName)?$this->fileName : null);
    }

    public function setFileName(string $fileName, $dest = null): self
    {
        $dir = ($dest === self::FIXTURES_DIR) ? $dest : self::UPLOADS_DIR;
        $this->fileName = $dir . $fileName;

        return $this;
    }

    public function getTrick(): ?Trick
    {
        return $this->trick;
    }

    public function setTrick(?Trick $trick): self
    {
        $this->trick = $trick;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getFile(): ?File
    {
        return $this->file;
    }

    /**
     * @param File|null $file
     */
    public function setFile(?File $file): void
    {
        $this->file = $file;
    }
}

<?php

namespace App\Entity;

class FileUploader
{

    private $uploadDir;

    private $file;

    private $fileName;

    public function getUploadDir(): ?string
    {
        return $this->uploadDir;
    }

    public function setUploadDir(string $uploadDir): self
    {
        $this->uploadDir = $uploadDir;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }
}

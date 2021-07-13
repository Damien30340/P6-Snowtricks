<?php

namespace App\Service;

use App\Entity\Picture;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class Uploader
{

    const AVATAR_DIR = "/img/avatar/";
    const PICTURE_TRICK_DIR = "/img/tricks/";

    private SluggerInterface $slugger;
    private string $baseUploadDir;

    public function __construct(SluggerInterface $slugger, string $baseUploadDir)
    {
        $this->slugger = $slugger;
        $this->baseUploadDir = $baseUploadDir;
    }

    /**
     * @param UploadedFile $file
     * @param string $dest
     * @param string|null $filename
     * @return string
     * @throws FileException
     */
    public function upload(UploadedFile $file, string $dest, ?string $filename = null): string
    {

        $safeFileName = $filename ?? $this->slugger->slug($file->getClientOriginalName(), PATHINFO_FILENAME);
        $newFileName = $dest . $safeFileName . '_' . uniqid() . '.' . $file->guessExtension();

        $file->move(
            $this->baseUploadDir . $dest,
            $newFileName
        );
        return $newFileName;
    }

    public function deleteFile(Picture $picture){
        try {
            unlink($this->baseUploadDir . $picture->getFileName());
        } catch (\Exception $e){
            return;
        }

    }


}

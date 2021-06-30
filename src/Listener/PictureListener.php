<?php


namespace App\Listener;

use App\Entity\Picture;
use App\Service\Uploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class PictureListener
{

    private Uploader $uploader;

    public function __construct(Uploader $uploader)
    {
        $this->uploader = $uploader;
    }

    public function prePersist(Picture $picture): void
    {
        try {
            if ($picture->getFile() instanceof UploadedFile) {
                $picture->setFileName($this->uploader->upload($picture->getFile(), Uploader::PICTURE_TRICK_DIR));
            }
        } catch (\Exception $e) {
            return;
        }
    }

    public function preRemove(Picture $picture): void
    {
        $this->uploader->deleteFile($picture);
    }
}
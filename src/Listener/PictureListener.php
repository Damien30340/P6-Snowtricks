<?php


namespace App\Listener;

use App\Entity\Picture;
use App\Service\Uploader;


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
            foreach ($picture->getTrick()->getPictures() as $pic) {
                $pic->setFileName($this->uploader->upload($pic->getFile(), Uploader::PICTURE_TRICK_DIR));
            }
        } catch (\Exception $e) {
            echo 'Impossible d\'uploader le fichier : ' . $e;
        }
    }

    public function preRemove(Picture $picture): void
    {
        $this->uploader->deleteFile($picture);
    }
}
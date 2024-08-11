<?php

namespace App\Service;

use Vich\UploaderBundle\Event\Event;

class CompressImageService
{
    public function compress(Event $event, int $quality = 40): void
    {
        try {
            $object = $event->getObject();
            $mapping = $event->getMapping();

            $destination = $mapping->getUploadDestination();
            $nameFile = $object->getImageName();
            $imageSize = getimagesize($destination.'/'.$nameFile);

            $imageWidth = $imageSize['0'];
            $imageHeight = $imageSize['1'];
            $extension = $imageSize['mime'] ?? $object->getImageFile()->getExtension();

            switch ($extension) {
                case 'image/png':
                    $image = imagecreatefrompng($destination.'/'.$nameFile);
                    $newNameFile = str_replace('.png', '.webp', $nameFile);
                    break;

                case 'image/jpeg':
                    $image = imagecreatefromjpeg($destination.'/'.$nameFile);
                    $newNameFile = str_replace(['.jpeg', '.jpg'], '.webp', $nameFile);
                    break;

                default:
                    $image = null;
                    $newNameFile = $nameFile;
                    break;
            }

            if ('image/webp' == $extension) {
                return;
            }

            if ($image) {
                $object->setImageName($newNameFile);

                $resize = $this->resizeImage($quality, $image, $imageWidth, $imageHeight);

                // format image to webp
                imagewebp($resize, $destination.'/'.$newNameFile, $quality);

                // remove image when formatted
                unlink($destination.'/'.$nameFile);

                // delete image to cache
                imagedestroy($image);
                imagedestroy($resize);
            } else {
                throw new \Exception("Can't format image");
            }
        } catch (\Throwable $th) {
            throw new \Exception('Error formatting image '.$th->getMessage());
        }
    }

    private function resizeImage(int $quality, \GdImage $image, int $imageWidth, int $imageHeight): \GdImage
    {
        if ($imageHeight > 1080) {
            return imagescale($image, $imageWidth * ($quality / 100), $imageHeight * ($quality / 100));
        } else {
            return $image;
        }
    }
}

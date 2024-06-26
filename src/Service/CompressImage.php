<?php

namespace App\Service;

class CompressImage
{

  public static function compress($event, $quality = 70)
  {

    try {

      $object = $event->getObject();
      $mapping = $event->getMapping();


      $destination = $mapping->getUploadDestination();
      $nameFile = $object->getImageName();
      $imageSize = getimagesize($destination  . '/' . $nameFile);

      $imageWidth = $imageSize['0'];
      $imageHeight = $imageSize['1'];
      $extension  = $imageSize['mime'] ?? $object->getImageFile()->getExtension();

      switch ($extension) {
        case 'image/png':
          $image = imagecreatefrompng($destination . '/' . $nameFile);
          $newNameFile = str_replace('.png', '.webp', $nameFile);
          break;

        case 'image/jpeg':

          $image = imagecreatefromjpeg($destination . '/' . $nameFile);
          $newNameFile = str_replace(['.jpeg', '.jpg'], '.webp', $nameFile);
          break;




        default:
          $image = null;
          break;
      }

      if ($extension == 'image/webp') {
        return;
      }


      if ($image) {
        $object->setImageName($newNameFile);

        if ($imageHeight > 1080) {
          $resize = imagescale($image,  $imageWidth * ($quality / 100), $imageHeight * ($quality / 100));
        } else {
          $resize = $image;
        }

        // format image to webp
        imagewebp($resize, $destination  . '/' . $newNameFile, $quality);

        // remove image when formatted
        unlink($destination . '/' . $nameFile);

        // delete image to cache
        imagedestroy($image);
      } else {
        throw new \Exception("Can't format image");
      }
    } catch (\Throwable $th) {
      throw new \Exception("Error formatting image " . $th->getMessage());
    }
  }
}

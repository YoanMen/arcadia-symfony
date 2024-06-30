<?php

namespace App\EventListener;

use App\Service\CompressImage;
use Vich\UploaderBundle\Event\Event;
use Vich\UploaderBundle\Event\Events;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

class UploadFileListener
{

  #[AsEventListener(event: Events::POST_UPLOAD)]
  public function onVichUploaderPreUpload(Event $event)
  {
    $compressImage = new CompressImage();
    $compressImage->compress($event);
  }
}

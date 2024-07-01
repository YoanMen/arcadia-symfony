<?php

namespace App\EventListener;

use App\Service\CompressImage;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Vich\UploaderBundle\Event\Event;
use Vich\UploaderBundle\Event\Events;

class UploadFileListener
{
    #[AsEventListener(event: Events::POST_UPLOAD)]
    public function onVichUploaderPreUpload(Event $event): void
    {
        $compressImage = new CompressImage();
        $compressImage->compress($event);
    }
}

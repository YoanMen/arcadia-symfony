<?php

namespace App\EventListener;

use App\Service\CompressImageService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Vich\UploaderBundle\Event\Event;
use Vich\UploaderBundle\Event\Events;

class UploadFileListener
{
    #[AsEventListener(event: Events::POST_UPLOAD)]
    public function onVichUploaderPreUpload(Event $event): void
    {
        $compressImage = new CompressImageService();
        $compressImage->compress($event);
    }
}

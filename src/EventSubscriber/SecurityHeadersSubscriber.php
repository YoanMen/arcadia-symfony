<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class SecurityHeadersSubscriber implements EventSubscriberInterface
{
  public function onKernelResponse(ResponseEvent $event)
  {
    $response = $event->getResponse();


    if ($response->getStatusCode() === Response::HTTP_OK) {
      $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
    }
  }

  public static function getSubscribedEvents()
  {
    return [
      KernelEvents::RESPONSE => 'onKernelResponse',
    ];
  }
}

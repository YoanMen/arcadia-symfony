<?php

namespace App\Controller\API;

use App\Repository\ServiceRepository;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ServiceController  extends AbstractController
{
  #[Route('api/service', name: 'api_getService', methods: ['GET'])]
  public function getAdviceCount(Request $request, ServiceRepository $serviceRepository)
  {
    try {
      $page = $request->get('page', '1');

      $data = $serviceRepository->findServicesByPage($page);


      return $this->json(['success' => true, 'data' => $data]);
    } catch (\Throwable $th) {

      return $this->json(['success' => false, 'error' => $th->getMessage()]);
    }
  }
}

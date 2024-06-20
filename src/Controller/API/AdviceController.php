<?php

namespace App\Controller\API;

use App\Repository\AdviceRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdviceController  extends AbstractController
{
  #[Route('api/advice/count', name: 'api_getAdviceCount', methods: ['GET'])]
  public function getAdviceCount(AdviceRepository $adviceRepository)
  {
    try {

      $adviceCount = $adviceRepository->count(['approved' => true]);

      return $this->json(['success' => true, 'count' => $adviceCount]);
    } catch (\Throwable $th) {

      return $this->json(['success' => false, 'error' => $th->getMessage()]);
    }
  }


  #[Route('api/advice/{id}', name: 'api_getAdvice', methods: ['GET'])]
  public function getAdvice(int $id, AdviceRepository $adviceRepository)
  {
    try {

      $advice = $adviceRepository->paginateApprovedAdvice($id);

      return $this->json(['success' => true, 'data' => $advice], 200, [], [
        'groups' => ['advice.approved']
      ]);
    } catch (\Throwable $th) {

      return $this->json(['success' => false, 'error' => $th->getMessage()], 500);
    }
  }
}

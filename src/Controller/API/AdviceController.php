<?php

namespace App\Controller\API;

use App\Entity\Advice;
use App\Repository\AdviceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;


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


  #[Route('api/advice/{id}', requirements: ['id' => '\d+'], name: 'api_getAdvice', methods: ['GET'])]
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

  #[Route('api/advice', name: 'api_sendAdvice', methods: ['POST'])]
  public function sendAdvice(Request $request, #[MapRequestPayload(serializationContext: ['groups' => ['advice.create']], acceptFormat: 'json')] Advice $advice, EntityManagerInterface $entityManagerInterface)
  {
    try {

      $csrf = json_decode($request->getContent(), true)['csrf'];


      if ($this->isCsrfTokenValid('send_advice', $csrf)) {

        $entityManagerInterface->persist($advice);
        $entityManagerInterface->flush();

        return $this->json(['success' => true], 200);
      } else {
        return $this->json(['success' => false, 'error' => 'clé CSRF non valide'], 401);
      }
    } catch (\Throwable $th) {


      return $this->json(['success' => false, 'error' => $th->getMessage()], 500);
    }
  }
}

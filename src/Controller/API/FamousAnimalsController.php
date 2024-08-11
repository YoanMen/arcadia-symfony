<?php

namespace App\Controller\API;

use App\Service\FamousAnimalService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FamousAnimalsController extends AbstractController
{
    #[Route('api/clickToAnimal/{id}', name: 'api_incrementClickAnimal', methods: ['GET'])]
    public function index(
        Request $request,
        FamousAnimalService $famousAnimalService
    ): JsonResponse {
        $id = $request->get('id');

        try {
            $famousAnimalService->incrementAnimalClick($id);

            return $this->json(['message' => 'ok'], 200);
        } catch (\Exception $e) {
            return $this->json(['message' => $e->getMessage()], 500);
        }
    }
}

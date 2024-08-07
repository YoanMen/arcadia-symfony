<?php

namespace App\Controller\API;

use App\Repository\AnimalRepository;
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
        AnimalRepository $animalRepository,
        FamousAnimalService $famousAnimalService
    ): JsonResponse {
        $id = $request->get('id');
        $animal = $animalRepository->findOneBy(['id' => $id]);

        try {
            if ($animal) {
                $famousAnimalService->incrementAnimalClick($animal->getId());

                return $this->json(['message' => 'ok'], 200);
            }

            return $this->json(['message' => 'no animal with this id'], 404);
        } catch (\Exception $e) {
            return $this->json(['message' => 'error'], 500);
        }
    }
}

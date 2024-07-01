<?php

namespace App\Controller\API;

use App\Repository\AnimalRepository;
use App\Service\CouchDBManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FamousAnimalsController extends AbstractController
{
    #[Route('api/clickToAnimal/{name}', name: 'api_incrementClickAnimal', methods: ['GET'])]
    public function index(Request $request, AnimalRepository $animalRepository, CouchDBManager $couchDBManager): JsonResponse
    {
        $name = $request->get('name');
        $animal = $animalRepository->findOneBy(['name' => $name]);

        try {
            if ($animal) {
                $couchDBManager->addClickToAnimalDocument($animal->getId());

                return $this->json(['message' => 'ok'], 200);
            }

            return $this->json(['message' => 'no animal with this name'], 404);
        } catch (\Exception $e) {
            return $this->json(['message' => 'error'], 500);
        }
    }
}

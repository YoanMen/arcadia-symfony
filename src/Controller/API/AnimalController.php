<?php

namespace App\Controller\API;

use App\Repository\AnimalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AnimalController extends AbstractController
{
    #[Route('api/animal/search', name: 'api_getAnimalResult', methods: ['GET'])]
    public function getSearchResult(Request $request, AnimalRepository $animalRepository): JsonResponse
    {
        try {
            $search = $request->get('search', '');
            $page = $request->get('page', '1');

            $data = $animalRepository->findAnimalBySearch($search, $page);

            return $this->json(['success' => true, 'data' => $data]);
        } catch (\Throwable $th) {
            return $this->json(['success' => false, 'error' => $th->getMessage()]);
        }
    }

    #[Route('api/animal/predictive', name: 'api_getAnimalPredictive', methods: ['POST'])]
    public function setPredictive(Request $request, AnimalRepository $animalRepository): JsonResponse
    {
        try {
            $json = file_get_contents('php://input');
            $data = json_decode($json);

            $search = $data->search;

            $data = $animalRepository->getPredictive($search);

            return $this->json(['success' => true, 'data' => $data]);
        } catch (\Throwable $th) {
            return $this->json(['success' => false, 'error' => $th->getMessage()]);
        }
    }
}

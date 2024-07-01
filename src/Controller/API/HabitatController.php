<?php

namespace App\Controller\API;

use App\Repository\HabitatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HabitatController extends AbstractController
{
    #[Route('api/habitat', name: 'api_getHabitat', methods: ['GET'])]
    public function getHabitats(Request $request, HabitatRepository $habitatRepository): JsonResponse
    {
        try {
            $page = $request->get('page', '1');

            $data = $habitatRepository->findHabitatsByPage($page);

            return $this->json(['success' => true, 'data' => $data]);
        } catch (\Throwable $th) {
            return $this->json(['success' => false, 'error' => $th->getMessage()]);
        }
    }

    #[Route('api/habitat/{slug}', methods: ['GET'], name: 'api_habitat.allAnimal')]
    public function getHabitatsAnimals(Request $request, HabitatRepository $habitatRepository, string $slug): JsonResponse
    {
        try {
            $page = $request->get('page', '1');

            $data = $habitatRepository->findAnimalsByHabitatAndByPage($page, $slug);

            return $this->json(['success' => true, 'data' => $data]);
        } catch (\Throwable $th) {
            return $this->json(['success' => false, 'error' => $th->getMessage()]);
        }
    }
}

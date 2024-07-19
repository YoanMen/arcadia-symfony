<?php

namespace App\Controller;

use App\Repository\AnimalRepository;
use App\Repository\HabitatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HabitatController extends AbstractController
{
    #[Route('/habitats', name: 'app_habitat')]
    public function index(AnimalRepository $animalRepository): Response
    {
        return $this->render('habitat/index.html.twig', [
            'controller_name' => 'HabitatController',
        ]);
    }

    #[Route('/habitats/{slug}', name: 'app_habitat.show')]
    public function show(HabitatRepository $habitatRepository, string $slug): Response
    {
        $habitat = $habitatRepository->findOneBy(['slug' => $slug]);

        if ($habitat) {
            return $this->render('habitat/show.html.twig', [
                'habitat' => $habitat,
            ]);
        }

        return $this->render(
            'error.html.twig',
            [
                'app_url' => 'app_habitat'
            ]
        );
    }

    #[Route('/habitats/{slug}/{slugAnimal}', name: 'app_habitat.animalShow')]
    public function showAnimal(HabitatRepository $habitatRepository, AnimalRepository $animalRepository, string $slug, string $slugAnimal): Response
    {
        $habitat = $habitatRepository->findOneBy(['slug' => $slug]);
        $animal = $animalRepository->findOneBy(['slug' => $slugAnimal]);
        if ($animal && $habitat) {
            return $this->render('animal/show.html.twig', [
                'animal' => $animal,
            ]);
        }

        return $this->render(
            'error.html.twig',
            [
                'app_url' => 'app_habitat.show',
                'slug' => $habitat->getSlug(),
            ]
        );
    }
}

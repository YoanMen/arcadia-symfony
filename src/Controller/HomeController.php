<?php

namespace App\Controller;

use App\Repository\AnimalRepository;
use App\Repository\HabitatRepository;
use App\Repository\SchedulesRepository;
use App\Service\CouchDBManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{



    #[Route('/', name: 'app_home')]
    public function index(SchedulesRepository $schedulesRepository, HabitatRepository $habitatRepository): Response
    {
        $schedules = $schedulesRepository->findAll();
        $habitats = $habitatRepository->findTwoHabitatForHomePageCards();
        return $this->render('home/index.html.twig', [
            'schedules' => $schedules,
            'habitats' => $habitats
        ]);
    }
}

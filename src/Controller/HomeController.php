<?php

namespace App\Controller;

use App\Repository\HabitatRepository;
use App\Repository\SchedulesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{

    public function __construct(private SchedulesRepository $schedulesRepository)
    {
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {

        $schedules = $this->schedulesRepository->findAll();


        return $this->render('home/index.html.twig', [
            'schedules' => $schedules,
        ]);
    }
}

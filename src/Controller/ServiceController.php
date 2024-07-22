<?php

namespace App\Controller;

use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ServiceController extends AbstractController
{
    #[Route('/services', name: 'app_service')]
    public function index(): Response
    {
        return $this->render('service/index.html.twig', [
            'controller_name' => 'ServiceController',
        ]);
    }

    #[Route('/services/{slug}', name: 'app_service.show')]
    public function show(string $slug, ServiceRepository $serviceRepository): Response
    {
        $service = $serviceRepository->findOneBy(['slug' => $slug]);

        if ($service) {
            return $this->render('service/show.html.twig', [
                'service' => $service,
            ]);
        }

        return $this->render('error.html.twig', [
            'app_url' => 'app_service',
        ]);
    }
}

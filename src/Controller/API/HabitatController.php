<?php

namespace App\Controller\API;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HabitatController  extends AbstractController
{
  #[Route('api/habitat', name: 'api_getHabitat', methods: ['GET'])]
  public function index()
  {
  }
}

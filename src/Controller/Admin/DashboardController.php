<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Advice;
use App\Entity\Animal;
use App\Entity\Habitat;
use App\Entity\Service;
use App\Entity\Schedules;
use App\Entity\AnimalFood;
use App\Entity\AnimalReport;
use App\Entity\HabitatComment;
use App\Repository\AnimalReportRepository;
use App\Repository\HabitatCommentRepository;
use App\Service\CouchDBManager;
use Symfony\UX\Chartjs\Model\Chart;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{


    public function __construct(
        private CouchDBManager $couchDBManager,
        private ChartBuilderInterface $chartBuilder,
        private HabitatCommentRepository $habitatCommentRepository,
        private AnimalReportRepository $animalReportRepository
    ) {
    }

    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {

        if ($this->isGranted('ROLE_ADMIN')) {

            $reports = $this->animalReportRepository
                ->createQueryBuilder('a')
                ->orderBy('a.id', 'DESC') // order by id in descending order (newest first)
                ->setMaxResults(10) // limit to the last 10 reports
                ->getQuery()
                ->getResult();

            $comments = $this->habitatCommentRepository
                ->createQueryBuilder('h')
                ->orderBy('h.id', 'DESC')
                ->setMaxResults(5)
                ->getQuery()
                ->execute();

            // dd($reports[0]->getVeterinary()->getUsername());

            return $this->render(
                'admin/dashboard.html.twig',
                [
                    'chart' => $this->setChart(),
                    'reports' => $reports,
                    'comments' => $comments
                ]
            );
        }

        if ($this->isGranted('ROLE_EMPLOYEE')) {
            $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
            return $this->redirect($adminUrlGenerator->setController(AnimalFoodCrudController::class)->generateUrl());
        }
        if ($this->isGranted('ROLE_VETERINARY')) {
            $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
            return $this->redirect($adminUrlGenerator->setController(AnimalReportCrudController::class)->generateUrl());
        }


        return $this->redirect('/');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setFaviconPath("/images/logo/arcadia_favicon.svg")
            ->disableDarkMode()
            ->setTitle('<img src="/images/logo/arcadia_green.svg" class="img-fluid d-block mx-auto" style="max-width:64px; width:100%;">');
    }

    public function configureMenuItems(): iterable
    {

        yield MenuItem::linkToUrl('Retour au site', 'fa fa-caret-left', '/');
        yield MenuItem::section("");
        if ($this->isGranted('ROLE_ADMIN')) {
            yield MenuItem::linkToDashboard('Dashboard', 'fa fa-chart-line');
            yield MenuItem::linkToCrud('Rapport sur les animaux', 'fas fa-clipboard', AnimalReport::class);
            yield MenuItem::linkToCrud('Commentaire habitats', 'fas fa-comment', HabitatComment::class);
            yield MenuItem::section("Gestion");
            yield MenuItem::linkToCrud('Habitats', 'fas fa-house', Habitat::class);
            yield MenuItem::linkToCrud('Animaux', 'fas fa-hippo', Animal::class);
            yield MenuItem::linkToCrud('Services', 'fas fa-list', Service::class);
            yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class);
            yield MenuItem::linkToCrud('Horaires', 'fas fa-clock', Schedules::class);
        }

        if ($this->isGranted('ROLE_VETERINARY')) {
            yield MenuItem::linkToCrud('Rapport sur les animaux', 'fas fa-clipboard', AnimalReport::class);
            yield MenuItem::linkToCrud('Commentaire habitats', 'fas fa-comment', HabitatComment::class);
            yield MenuItem::linkToCrud('Nourriture des animaux', 'fas fa-utensils', AnimalFood::class);
        }

        if ($this->isGranted('ROLE_EMPLOYEE')) {
            yield MenuItem::linkToCrud('Nourriture des animaux', 'fas fa-utensils', AnimalFood::class);
            yield MenuItem::section("Gestion");
            yield MenuItem::linkToCrud('Avis', 'fas fa-message', Advice::class);
            yield MenuItem::linkToCrud('Services', 'fas fa-list', Service::class);
        }
    }

    public function configureAssets(): Assets
    {
        return parent::configureAssets()
            ->addAssetMapperEntry('admin/app' );
    }


    public function setChart()
    {

        $famousAnimal = $this->couchDBManager->getFamousAnimals();


        $chart = $this->chartBuilder->createChart(Chart::TYPE_LINE);

        $name = array_map(function ($animal) {
            return $animal->getName();
        }, $famousAnimal);

        $clicks = array_map(function ($animal) {
            return $animal->getClicks();
        }, $famousAnimal);


        $chart->setData([
            'labels' => $name,
            'datasets' => [
                [
                    'label' => 'nombre de cliques',
                    'backgroundColor' => '#db4c40',
                    'borderColor' => '#dbe3df',
                    'data' => $clicks,
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => (!empty($clicks)) ?  max($clicks) + 10 : 50,
                ],
            ],
        ]);

        return $chart;
    }
}

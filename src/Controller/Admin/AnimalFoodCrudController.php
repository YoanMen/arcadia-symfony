<?php

namespace App\Controller\Admin;

use App\Entity\AnimalFood;
use Doctrine\ORM\EntityManagerInterface;
use App\Controller\Admin\Filter\EmployeeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use App\Controller\Admin\Filter\HabitatCommentFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use App\Controller\Admin\Filter\HabitatAnimalFoodFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AnimalFoodCrudController extends AbstractCrudController
{

    public function configureActions(Actions $actions): Actions
    {

        if ($this->isGranted('ROLE_EMPLOYEE')) {
            return $actions->disable(Action::DELETE, Action::EDIT)
                ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                    return $action->setIcon('fa fa-plus')->setLabel('Ajouter un nourrissage');
                });
        }



        return $actions->disable(Action::NEW, Action::EDIT, Action::DELETE);
    }



    public static function getEntityFqcn(): string
    {
        return AnimalFood::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters->add('date')
            ->add('animal')
            ->add(HabitatAnimalFoodFilter::new('habitat'));
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setPageTitle("index", "Les nourrissage des animaux")
            ->setDefaultSort(["date" => 'DESC'])
            ->setPageTitle('new', 'Création d\'un rapport de nourriture')
            ->showEntityActionsInlined(true);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            DateTimeField::new('date')->setFormat("d/M/Y - H:m")
                ->setHelp('Date du nourrissage')
                ->setFormTypeOption('data', new \DateTimeImmutable('now'))
                ->setColumns(12),
            AssociationField::new("animal", "Animal")
                ->setColumns(2)
                ->setSortable(true)
                ->setFormTypeOption('choice_label', function ($animal) {
                    return $animal->getName() . ' (' . $animal->getHabitat()->getName() . ')';
                }),
            TextField::new('food', 'Nourriture')
                ->setSortable(false)
                ->setColumns(3)
                ->setHelp('viande, foin, fruits etc ...'),
            TextField::new('quantity', 'Quantité')
                ->formatValue(function ($value) {
                    return $value . ' Kg';
                })
                ->onlyOnIndex(),
            NumberField::new('quantity', 'Quantité')
                ->setStoredAsString()->onlyOnForms()
                ->setHelp('en kilogrammes')
                ->setColumns(3),

        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // add veterinary for comment
        if ($entityInstance instanceof AnimalFood) {

            $user = $this->getUser();
            if ($user) {
                $entityInstance->setEmployee($user);
            }
        }

        parent::updateEntity($entityManager, $entityInstance);
    }
}

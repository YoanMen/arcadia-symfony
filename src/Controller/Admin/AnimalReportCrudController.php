<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Filter\AnimalRapportHabitatFilter;
use App\Controller\Admin\Filter\VeterinaryFilter;
use App\Entity\AnimalReport;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AnimalReportCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AnimalReport::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $filters->add(VeterinaryFilter::new('veterinary'));
        }

        return $filters->add('animal')
            ->add('date')
            ->add(AnimalRapportHabitatFilter::new('habitat'));
    }

    public function configureActions(Actions $actions): Actions
    {
        if ($this->isGranted('ROLE_VETERINARY')) {
            return $actions
                ->disable(Action::DELETE, Action::EDIT)
                ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                    return $action->setIcon('fa fa-plus')->setLabel('Crée un rapport');
                });
        }

        return $actions->disable(Action::NEW, Action::EDIT, Action::DELETE);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setPageTitle('index', 'Les rapports sur les animaux')
            ->setPageTitle('new', 'Création d\'un rapport pour un animal');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            DateField::new('date')
                ->onlyOnIndex(),
            AssociationField::new('veterinary', 'Vétérinaire')
                ->onlyOnIndex()
                ->setPermission('ROLE_ADMIN'),
            TextField::new('animal.habitat', 'Habitat')
                ->setSortable(true)
                ->onlyOnIndex(),
            AssociationField::new('animal', 'Animal')
                ->setColumns(2)
                ->setSortable(true)
                ->setRequired(true)
                ->setFormTypeOption('choice_label', function ($animal) {
                    return $animal->getName().' ('.$animal->getHabitat()->getName().')';
                }),
            TextField::new('statut', 'État de l\'animal')
                ->setSortable(false)
                ->setColumns(4)
                ->setHelp('malade, bien, mal a la patte etc..')
                ->setRequired(true),
            TextField::new('food', 'Nourriture')
                ->setSortable(false)
                ->setHelp('nourriture recommandée ex: viande, foin, fruits etc ...')
                ->setColumns(3)
                ->setRequired(true),
            TextField::new('quantity', 'Quantité')
                ->formatValue(function ($value) {
                    return $value.' Kg';
                })
                ->setHelp('quantité recommandée')
                ->setSortable(false),
            NumberField::new('quantity', 'Quantité')
                ->onlyOnForms()
                ->setHelp('en kilogrammes')
                ->setColumns(3)
                ->setRequired(true),
            TextareaField::new('detail')
                ->setColumns(12)
                ->setSortable(false)
                ->setRequired(false),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, mixed $entityInstance): void
    {
        // add veterinary for comment
        if ($entityInstance instanceof AnimalReport) {
            $entityInstance->setDate(new \DateTimeImmutable());

            $user = $this->getUser();

            if ($user instanceof User) {
                $entityInstance->setVeterinary($user);
            }
        }

        parent::updateEntity($entityManager, $entityInstance);
    }
}

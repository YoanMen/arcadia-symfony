<?php

namespace App\Controller\Admin;

use App\Entity\Animal;
use App\Form\AnimalImageType;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AnimalCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Animal::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            return $actions
                ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                    return $action->setIcon('fa fa-plus')->setLabel('Ajouter un animal');
                });
        }

        return $actions->disable(Action::NEW, Action::EDIT, Action::DELETE,);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setPageTitle("index", "Gestion des animaux")
            ->setPageTitle('edit', 'Modifier un animal')
            ->setPageTitle('new', 'Création d\'un animal');
    }

    public function configureFields(string $pageName): iterable
    {
        return [

            TextField::new('name', 'Nom')->setColumns(6),
            SlugField::new('slug')->setTargetFieldName('name')->onlyOnForms(),
            AssociationField::new('habitat'),
            AssociationField::new('animalImages', 'Images')->onlyOnIndex(),

            AssociationField::new('information', 'Information sur l\'animal')
                ->setColumns(12)
                ->onlyOnForms()->renderAsEmbeddedForm(),
            CollectionField::new('animalImages', 'Image de l\'animal')
                ->setEntryType(AnimalImageType::class)->onlyOnForms(),

        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Animal) {
        }
    }
}

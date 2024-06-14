<?php

namespace App\Controller\Admin;

use App\Entity\Animal;
use App\Form\AnimalImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;


class AnimalCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Animal::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('name'),
            SlugField::new('slug')->setTargetFieldName('name'),
            CollectionField::new('animalImages', 'Ajouter une image')
                ->setEntryType(AnimalImageType::class)->onlyOnForms(),
            AssociationField::new('habitat'),
            AssociationField::new('information', 'Information animal')
                ->setCrudController(AnimalInformationCrudController::class)
                ->renderAsEmbeddedForm()
                ->onlyOnForms()



        ];
    }
}

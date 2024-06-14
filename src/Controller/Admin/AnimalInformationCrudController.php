<?php

namespace App\Controller\Admin;

use App\Entity\AnimalInformation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AnimalInformationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AnimalInformation::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('description'),
            TextField::new('sizeAndHeight'),
            TextField::new('lifespan'),
            AssociationField::new('region', 'region'),
            AssociationField::new('uicn', 'UICN'),
            AssociationField::new('species', 'Espèce')
                ->setCrudController(SpeciesCrudController::class)
                ->renderAsEmbeddedForm()
                ->onlyOnForms()
        ];
    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\AnimalInformation;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AnimalInformationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AnimalInformation::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            return $actions;
        }

        return $actions->disable(Action::NEW, Action::EDIT, Action::DELETE);
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addColumn(6),
            TextField::new('description', 'Description'),
            TextField::new('sizeAndHeight', 'Taille et poids'),
            TextField::new('lifespan', 'Espérance de vie'),
            AssociationField::new('region', 'Région'),

            AssociationField::new('uicn', 'UICN'),
            FormField::addFieldset()->addColumn(6),

            AssociationField::new('species', 'Espèce')
                ->renderAsEmbeddedForm()
                ->onlyOnForms()->setColumns(15)
        ];
    }
}

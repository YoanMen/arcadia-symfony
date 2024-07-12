<?php

namespace App\Controller\Admin;

use App\Entity\AnimalInformation;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

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
            TextField::new('description', 'Description')
                ->setRequired(true)
                ->setEmptyData(''),
            TextField::new('sizeAndHeight', 'Taille et poids')
                ->setRequired(true)
                ->setEmptyData(''),
            TextField::new('lifespan', 'Espérance de vie')
                ->setEmptyData('')
                ->setRequired(true),
            AssociationField::new('region', 'Région')
                ->setRequired(true),
            AssociationField::new('uicn', 'UICN')
                ->setRequired(true),
            FormField::addFieldset()
                ->addColumn(6)
                ->setRequired(true),
            AssociationField::new('species', 'Espèce')
                ->renderAsEmbeddedForm()
                ->onlyOnForms()
                ->setRequired(true)
                ->setColumns(15),
        ];
    }
}

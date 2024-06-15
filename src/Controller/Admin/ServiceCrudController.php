<?php

namespace App\Controller\Admin;

use App\Entity\Service;
use App\Form\ServiceImageType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class ServiceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Service::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                return $action->setIcon('fa fa-plus')->setLabel('Ajouter un service');
            });
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setPageTitle("index", "Gestion des services")
            ->setPageTitle('edit', 'Modifier un service')
            ->setPageTitle('new', 'Création d\'un service');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name')->setColumns(6),
            SlugField::new('slug', 'Texte dans l\'url')
                ->setTargetFieldName('name')
                ->onlyOnForms()->setColumns(6),

            TextareaField::new('description')->setColumns(12),
            TextEditorField::new('information')->setColumns(12)->setNumOfRows(12),
            CollectionField::new('serviceImages', 'Ajouter une image')
                ->setRequired(true)
                ->setEntryType(ServiceImageType::class)->onlyOnForms(),
        ];
    }
}

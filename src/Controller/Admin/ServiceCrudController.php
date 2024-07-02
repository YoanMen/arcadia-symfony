<?php

namespace App\Controller\Admin;

use App\Entity\Service;
use App\Form\ServiceImageType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

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
        return $crud->setPageTitle('index', 'Gestion des services')
            ->setPageTitle('edit', 'Modifier un service')
            ->setPageTitle('new', 'Création d\'un service');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom')
                ->setColumns(6)
                ->setHelp('nom du service')
                ->setRequired(true),
            SlugField::new('slug', 'Texte dans l\'url')
                ->setTargetFieldName('name')
                ->setHelp('généralement à ne pas changer')
                ->onlyOnForms()
                ->setColumns(6)
                ->setRequired(true),
            TextareaField::new('description')
                ->setColumns(12)
                ->setHelp('description du service')
                ->setRequired(true),
            TextEditorField::new('information')
                ->setColumns(12)
                ->setNumOfRows(12)
                ->setHelp('informations utile au visiteurs, comme les horaires')
                ->setRequired(true),
            CollectionField::new('serviceImages', 'Ajouter une image')
                ->setEntryType(ServiceImageType::class)
                ->onlyOnForms(),
            AssociationField::new('serviceImages', 'Images')
                ->onlyOnIndex(),
        ];
    }
}

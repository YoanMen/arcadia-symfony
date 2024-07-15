<?php

namespace App\Controller\Admin;

use App\Entity\Service;
use App\Form\ServiceImageType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ServiceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Service::class;
    }

    public function index(AdminContext $context)
    {
        if ($this->isGranted('ROLE_EMPLOYEE') || $this->isGranted('ROLE_ADMIN')) {
            return parent::index($context);
        }

        throw $this->createAccessDeniedException('Vous n\'avez pas l\'autorisation pour accéder à cette page');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER, function (Action $action) {
                return $action->setLabel('Créer et ajouter un nouveau service');
            })
            ->update(Crud::PAGE_NEW, Action::SAVE_AND_RETURN, function (Action $action) {
                return $action->setLabel('Créer un service');
            })
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
                ->setEmptyData('')
                ->setHelp('généralement à ne pas changer')
                ->onlyOnForms()
                ->setColumns(6)
                ->setRequired(true),
            TextareaField::new('description')
                ->setColumns(12)
                ->setEmptyData('')
                ->setHelp('description du service')
                ->setRequired(true),
            TextEditorField::new('information')
                ->setColumns(12)
                ->setNumOfRows(12)
                ->setEmptyData('')
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

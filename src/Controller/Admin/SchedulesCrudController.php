<?php

namespace App\Controller\Admin;

use App\Entity\Schedules;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SchedulesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Schedules::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setPageTitle('index', 'Gestion des horaires')
            ->setPageTitle('edit', 'Modifier les horaires')->showEntityActionsInlined(true);
    }

    public function index(AdminContext $context)
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            return parent::index($context);
        }

        throw $this->createAccessDeniedException('Vous n\'avez pas l\'autorisation pour accéder à cette page');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE)
            ->disable(Action::NEW, Action::DELETE);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('schedules', 'Horaires')
                ->renderAsHtml(),
            TextEditorField::new('schedules', 'Horaires')
                ->setNumOfRows(15)
                ->setColumns(12)
                ->onlyOnForms()
                ->setEmptyData('')
                ->setRequired(true),
        ];
    }
}

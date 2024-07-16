<?php

namespace App\Controller\Admin;

use App\Entity\Advice;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AdviceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Advice::class;
    }

    public function index(AdminContext $context)
    {
        if ($this->isGranted('ROLE_EMPLOYEE')) {
            return parent::index($context);
        }

        throw $this->createAccessDeniedException('Vous n\'avez pas l\'autorisation pour accéder à cette page');
    }

    public function configureActions(Actions $actions): Actions
    {
        if ($this->isGranted('ROLE_EMPLOYEE')) {
            return $actions
                ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                    return $action->setLabel('gérer le statut');
                })
                ->update(Crud::PAGE_EDIT, Action::SAVE_AND_RETURN, function (Action $action) {
                    return $action->setLabel('Sauvegarder');
                })
                ->remove(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE)
                ->disable(Action::NEW, Action::DELETE);
        }

        return $actions
            ->disable(Action::NEW, Action::EDIT, Action::DELETE);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Avis des visiteurs')
            ->setPageTitle('edit', 'Gestion avis')->showEntityActionsInlined(true)
            ->setDefaultSort([
                'id' => 'DESC',
            ]);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            NumberField::new('approved', 'statut')
                ->formatValue(function ($value) {
                    return (0 == $value) ? 'non approuvé' : 'approuvé';
                })
                ->setSortable(true)->onlyOnIndex(),
            BooleanField::new('approved', 'Approuvé')
                ->setColumns(2)
                ->setCssClass('d-flex')
                ->onlyOnForms()
                ->setFormTypeOption('label', 'Approuver avis'),
            TextField::new('pseudo', 'Pseudo')
                ->setDisabled(true)
                ->setColumns(4),
            TextareaField::new('advice', 'Avis')
                ->setDisabled(true)
                ->setColumns(12),
        ];
    }
}

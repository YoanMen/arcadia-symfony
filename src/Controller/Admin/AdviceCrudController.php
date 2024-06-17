<?php

namespace App\Controller\Admin;

use App\Entity\Advice;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AdviceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Advice::class;
    }

    public function configureActions(Actions $actions): Actions
    {

        if ($this->isGranted('ROLE_EMPLOYEE')) {
            return $actions
                ->remove('index', Action::EDIT)
                ->disable(Action::NEW, Action::DELETE);
        }

        return $actions->disable(Action::NEW, Action::EDIT, Action::DELETE);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle("index", "Avis des visiteurs")
            ->setDefaultSort([
                'id' => 'DESC',
            ]);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            BooleanField::new('approved', 'Approuvé'),
            TextField::new('pseudo', 'Pseudo'),
            TextField::new('advice', 'Avis')->renderAsHtml(),
        ];
    }
}

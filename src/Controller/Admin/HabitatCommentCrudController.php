<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Filter\HabitatCommentFilter;
use App\Controller\Admin\Filter\VeterinaryFilter;
use App\Entity\HabitatComment;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class HabitatCommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return HabitatComment::class;
    }

    public function index(AdminContext $context)
    {
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_VETERINARY')) {
            return parent::index($context);
        }

        throw $this->createAccessDeniedException('Vous n\'avez pas l\'autorisation pour accéder à cette page');
    }

    public function configureFilters(Filters $filters): Filters
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $filters->add(VeterinaryFilter::new('veterinary'));
        }

        return $filters->add('date')

            ->add(HabitatCommentFilter::new('habitat'));
    }

    public function configureActions(Actions $actions): Actions
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            return $actions->disable(Action::NEW, Action::DELETE, Action::EDIT);
        }
        if ($this->isGranted('ROLE_VETERINARY')) {
            return $actions
                ->update(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER, function (Action $action) {
                    return $action->setLabel('Créer et ajouter un nouveau commentaire');
                })
                ->update(Crud::PAGE_NEW, Action::SAVE_AND_RETURN, function (Action $action) {
                    return $action->setLabel('Créer un commentaire');
                })
                ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                    return $action->setIcon('fa fa-plus')->setLabel('Ajouter un commentaire');
                })
                ->disable(Action::DELETE, Action::EDIT);
        }

        return $actions;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setPageTitle('index', 'Les commentaires sur les habitats')
            ->setPageTitle('new', 'Création d\'un commentaire pour un habitat');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            DateField::new('date')
                ->onlyOnIndex(),
            AssociationField::new('veterinary', 'Vétérinaire')
                ->onlyOnIndex()
                ->setPermission('ROLE_ADMIN'),
            AssociationField::new('habitat')
                ->setRequired(true),
            TextareaField::new('detail')
                ->setColumns(12)
                ->setEmptyData('')
                ->onlyOnForms()
                ->setRequired(true),
            TextField::new('detail')
                ->onlyOnIndex()
                ->setEmptyData('')
                ->renderAsHtml(),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, mixed $entityInstance): void
    {
        // add veterinary for comment
        if ($entityInstance instanceof HabitatComment) {
            $user = $this->getUser();
            if ($user instanceof User) {
                $entityInstance->setDate(new \DateTimeImmutable());
                $entityInstance->setVeterinary($user);
            }
        }

        parent::updateEntity($entityManager, $entityInstance);
    }
}

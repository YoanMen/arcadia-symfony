<?php

namespace App\Controller\Admin;

use App\Entity\HabitatComment;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use App\Controller\Admin\Filter\VeterinaryFilter;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use App\Controller\Admin\Filter\HabitatCommentFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class HabitatCommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return HabitatComment::class;
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
            return $actions->disable(Action::DELETE, Action::EDIT)
                ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                    return $action->setIcon('fa fa-plus')->setLabel('Ajouter un commentaire');
                });
        }

        return $actions;
    }



    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setPageTitle("index", "Les commentaires sur les habitats")
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
            AssociationField::new("habitat"),
            TextareaField::new('detail')
                ->setColumns(12)
                ->onlyOnForms()
                ->setRequired(true),
            TextField::new('detail')
                ->onlyOnIndex()
                ->renderAsHtml()
        ];
    }


    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // add veterinary for comment
        if ($entityInstance instanceof HabitatComment) {

            $user = $this->getUser();
            if ($user) {
                $entityInstance->setDate(new \DateTimeImmutable());
                $entityInstance->setVeterinary($user);
            }
        }

        parent::updateEntity($entityManager, $entityInstance);
    }
}

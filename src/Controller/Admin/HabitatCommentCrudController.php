<?php

namespace App\Controller\Admin;

use App\Entity\HabitatComment;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class HabitatCommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return HabitatComment::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            return $actions->disable(Action::NEW, Action::DELETE, Action::EDIT);
        }
        if ($this->isGranted('ROLE_VETERINARY')) {
            return $actions->disable(Action::DELETE, Action::EDIT);
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
                ->setFormTypeOption('data', new \DateTimeImmutable('now'))
                ->setColumns(6),
            AssociationField::new('veterinary', 'Vétérinaire')
                ->onlyOnIndex()
                ->setPermission('ROLE_ADMIN'),
            AssociationField::new("habitat"),
            TextareaField::new('detail')
                ->setColumns(12)
                ->onlyOnForms(),
            TextField::new('detail')->renderAsHtml()
        ];
    }


    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // add veterinary for comment
        if ($entityInstance instanceof HabitatComment) {

            $user = $this->getUser();
            if ($user) {
                $entityInstance->setVeterinary($user);
            }
        }

        parent::updateEntity($entityManager, $entityInstance);
    }
}

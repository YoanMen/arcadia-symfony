<?php

namespace App\Controller\Admin;


use App\Entity\AnimalReport;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class AnimalReportCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AnimalReport::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        if ($this->isGranted('ROLE_VETERINARY')) {
            return $actions
                ->disable(Action::DELETE, Action::EDIT)
                ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                    return $action->setIcon('fa fa-plus')->setLabel('Crée un rapport');
                });
        }


        return $actions->disable(Action::NEW, Action::EDIT, Action::DELETE);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setPageTitle("index", "Les rapports sur les animaux")
            ->setPageTitle('new', 'Création d\'un rapport sur un animal');
    }



    public function configureFields(string $pageName): iterable
    {
        return [

            DateField::new('date')
                ->setFormTypeOption('data', new \DateTimeImmutable('now'))
                ->setColumns(12)->setColumns(12),
            TextField::new('statut', 'Etat de l\'animal')->setColumns(4),
            AssociationField::new('veterinary', 'Vétérinaire')
                ->onlyOnIndex()
                ->setPermission('ROLE_ADMIN'),
            AssociationField::new("animal")->setColumns(2),
            TextField::new('food', 'Nourriture')->setColumns(3),
            TextField::new('QuantityFormatted', 'Quantité')->onlyOnIndex(),
            NumberField::new('quantity', 'Quantité')
                ->setStoredAsString()->onlyOnForms()
                ->setHelp('en kilogrammes')
                ->setColumns(3),
            TextareaField::new('detail')->setColumns(12)->setRequired(false)
        ];
    }



    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // add veterinary for comment
        if ($entityInstance instanceof AnimalReport) {

            $user = $this->getUser();
            if ($user) {
                $entityInstance->setVeterinary($user);
            }
        }

        parent::updateEntity($entityManager, $entityInstance);
    }
}

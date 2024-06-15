<?php

namespace App\Controller\Admin;

use App\Entity\AnimalFood;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AnimalFoodCrudController extends AbstractCrudController
{

    public function configureActions(Actions $actions): Actions
    {

        if ($this->isGranted('ROLE_EMPLOYEE')) {
            return $actions->disable(Action::NEW, Action::DELETE, Action::EDIT);
        }
        if ($this->isGranted('ROLE_VETERINARY')) {
            return $actions
                ->disable(Action::DELETE, Action::EDIT);
        }


        return $actions->disable(Action::NEW, Action::EDIT, Action::DELETE);
    }



    public static function getEntityFqcn(): string
    {
        return AnimalFood::class;
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setPageTitle("index", "Les nourrissage des animaux")
            ->setDefaultSort(["date" => 'DESC'])
            ->setPageTitle('new', 'Création d\'un rapport de nourriture')
            ->showEntityActionsInlined(true);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            DateTimeField::new('date')->setFormat("d/M/Y - H:m")
                ->setFormTypeOption('data', new \DateTimeImmutable('now'))
                ->setColumns(12)->setColumns(4),
            AssociationField::new("animal")->setColumns(2),
            TextField::new('food', 'Nourriture')->setColumns(3),
            TextField::new('QuantityFormatted', 'Quantité')->onlyOnIndex(),
            NumberField::new('quantity', 'Quantité')
                ->setStoredAsString()->onlyOnForms()
                ->setHelp('en kilogrammes')
                ->setColumns(3),

        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // add veterinary for comment
        if ($entityInstance instanceof AnimalFood) {

            $user = $this->getUser();
            if ($user) {
                $entityInstance->setEmployee($user);
            }
        }

        parent::updateEntity($entityManager, $entityInstance);
    }
}

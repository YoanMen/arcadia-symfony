<?php

namespace App\Controller\Admin;

use App\Entity\Habitat;
use App\Form\HabitatImageType;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class HabitatCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Habitat::class;
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
        if ($this->isGranted('ROLE_ADMIN')) {
            return $actions
                ->update(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER, function (Action $action) {
                    return $action->setLabel('Créer et ajouter un nouveau habitat');
                })
                ->update(Crud::PAGE_NEW, Action::SAVE_AND_RETURN, function (Action $action) {
                    return $action->setLabel('Créer un habitat');
                })
                ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                    return $action->setIcon('fa fa-plus')->setLabel('Ajouter un habitat');
                });
        }

        return $actions->disable(Action::NEW, Action::EDIT, Action::DELETE);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setPageTitle('index', 'Gestion des habitats')
            ->setPageTitle('edit', 'Modifier un habitat')
            ->setPageTitle('new', 'Création d\'un habitat');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom de l\'habitat')
                ->setColumns(5)
                ->setEmptyData('')
                ->setRequired(true),
            SlugField::new('slug', 'Texte dans l\'url')
                ->setTargetFieldName('name')
                ->setEmptyData('')
                ->onlyOnForms()
                ->setRequired(true)
                ->setHelp('généralement à ne pas changer'),
            TextareaField::new('description', 'Description de l\'habitat')
                ->setNumOfRows(5)
                ->setEmptyData('')
                ->setRequired(true)
                ->setColumns(15),
            CollectionField::new('habitatImages', 'Images de l\'habitat')
                ->setEntryType(HabitatImageType::class)
                ->setRequired(false)
                ->onlyOnForms(),
            AssociationField::new('habitatImages', 'Images')
                ->onlyOnIndex(),
            AssociationField::new('animals', 'Animaux')
                ->onlyOnIndex(),
        ];
    }

    public function deleteEntity(EntityManagerInterface $entityManager, mixed $entityInstance): void
    {
        $animalCount = count($entityInstance->getAnimals());

        if (0 != $animalCount) {
            $this->addFlash('notice', "Impossible de supprimer l'habitat " . $entityInstance->getName() . ' des animaux y sont rattachés');
        } else {
            $entityManager->remove($entityInstance);
            $entityManager->flush();
        }
    }
}

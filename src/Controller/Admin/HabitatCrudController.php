<?php

namespace App\Controller\Admin;

use App\Entity\Habitat;
use App\Form\HabitatImageType;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class HabitatCrudController extends AbstractCrudController
{



    public static function getEntityFqcn(): string
    {
        return Habitat::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            return $actions
                ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                    return $action->setIcon('fa fa-plus')->setLabel('Ajouter un habitat');
                });
        }


        return $actions->disable(Action::NEW, Action::EDIT, Action::DELETE);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setPageTitle("index", "Gestion des habitats")
            ->setPageTitle('edit', 'Modifier un habitat')
            ->setPageTitle('new', 'Création d\'un habitat');
    }
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom de l\'habitat')->setColumns(5),
            SlugField::new('slug', 'Texte dans l\'url')
                ->setTargetFieldName('name')
                ->onlyOnForms(),
            TextEditorField::new('description', 'Description de l\'habitat')->setNumOfRows(15)->setColumns(15),
            CollectionField::new('habitatImages', 'Images de l\'habitat')
                ->setRequired(true)
                ->setEntryType(HabitatImageType::class)->onlyOnForms(),
            AssociationField::new('habitatImages', 'Images')->onlyOnIndex(),
            AssociationField::new('animals', 'Animaux')->onlyOnIndex(),
        ];
    }

    public function deleteEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $animalCount = count($entityInstance->getAnimals());

        if ($animalCount != 0) {
            $this->addFlash('notice', "Impossible de supprimer l'habitat des animaux y sont rattaché");
        } else {
            $entityManager->remove($entityInstance);
            $entityManager->flush();
        }
    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\Animal;
use App\Form\AnimalImageType;
use App\Service\FamousAnimalService;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AnimalCrudController extends AbstractCrudController
{
    public function __construct(
        private FamousAnimalService $famousAnimalService
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return Animal::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters->add('habitat');
    }

    public function configureActions(Actions $actions): Actions
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            return $actions
                ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                    return $action->setIcon('fa fa-plus')->setLabel('Ajouter un animal');
                });
        }

        return $actions->disable(Action::NEW, Action::EDIT, Action::DELETE);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setPageTitle('index', 'Gestion des animaux')
            ->setPageTitle('edit', 'Modifier un animal')
            ->setPageTitle('new', 'Création d\'un animal');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom')
                ->setRequired(true)
                ->setColumns(6),
            SlugField::new('slug')
                ->setTargetFieldName('name')
                ->onlyOnForms()
                ->setHelp('généralement à ne pas changer'),
            AssociationField::new('habitat')
                ->setRequired(true),
            AssociationField::new('animalImages', 'Images')
                ->onlyOnIndex(),
            AssociationField::new('information', 'Information sur l\'animal')
                ->setColumns(12)
                ->onlyOnForms()
                ->renderAsEmbeddedForm()
                ->setRequired(true),
            CollectionField::new('animalImages', 'Image de l\'animal')
                ->setEntryType(AnimalImageType::class)
                ->onlyOnForms(),
        ];
    }

    public function updateEntity(EntityManagerInterface $entityManager, mixed $entityInstance): void
    {
        $this->famousAnimalService->updateAnimal($entityInstance->getId());
        parent::updateEntity($entityManager, $entityInstance);
    }

    public function deleteEntity(EntityManagerInterface $entityManager, mixed $entityInstance): void
    {
        $this->famousAnimalService->deleteAnimal($entityInstance->getId());
        parent::deleteEntity($entityManager, $entityInstance);
    }
}

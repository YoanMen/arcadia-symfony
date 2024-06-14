<?php

namespace App\Controller\Admin;

use App\Entity\Habitat;
use App\Form\HabitatImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class HabitatCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Habitat::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom de l\'habitat'),
            SlugField::new('slug', 'texte dans l\'url')->setTargetFieldName('name'),
            TextEditorField::new('description', 'Description de l\'habitat'),
            CollectionField::new('habitatImages', 'Ajouter des images')
                ->setRequired(true)
                ->setEntryType(HabitatImageType::class)->onlyOnForms(),

        ];
    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\Species;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SpeciesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Species::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('communName', 'Nom Commun')
                ->setColumns(6)
                ->setRequired(true)
                ->setEmptyData(''),
            TextField::new('genre', 'Genre')
                ->setColumns(6)
                ->setEmptyData('')
                ->setRequired(true),
            TextField::new('family', 'Famille')
                ->setColumns(6)
                ->setEmptyData('')
                ->setRequired(true),
            TextField::new('ordre', 'Ordre')
                ->setColumns(6)
                ->setEmptyData('')
                ->setRequired(true),
        ];
    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserRoleType;
use Doctrine\ORM\QueryBuilder;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use Symfony\Component\Validator\Constraints\Choice;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Orm\EntityRepositoryInterface;

class UserCrudController extends AbstractCrudController
{


    public function __construct(private UserPasswordHasherInterface $passwordHasher, private EntityRepository $entityRepository)
    {
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [

            TextField::new('username'),
            TextField::new('email'),
            TextField::new('password')->setFormTypeOption('data', '')->onlyOnForms(),
            ChoiceField::new('roles', 'role de l\'utilisateur')
                ->setChoices([
                    'Vétérinaire' => 'ROLE_VETERINARY',
                    'Employé' => 'ROLE_EMPLOYEE'
                ])->onlyOnIndex(),
            ChoiceField::new('selectedRole', 'role de l\'utilisateur')
                ->setChoices([
                    'Vétérinaire' => 'ROLE_VETERINARY',
                    'Employé' => 'ROLE_EMPLOYEE'
                ])
                ->allowMultipleChoices(false)->renderExpanded()
                ->setRequired(true)->onlyWhenCreating(),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {

        if ($entityInstance instanceof User) {

            $selectedRole = $entityInstance->getSelectedRole();

            $hashedPassword = $this->passwordHasher->hashPassword(
                $entityInstance,
                $entityInstance->getPassword()
            );

            $entityInstance->setPassword($hashedPassword);
            $entityInstance->addRole($selectedRole);
        }

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $response = $this->entityRepository->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $response->andWhere('entity.roles NOT LIKE :admin ')
            ->setParameter('admin',  '%ROLE_ADMIN%');

        return $response;
    }
}

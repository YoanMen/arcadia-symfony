<?php

namespace App\Controller\Admin;

use App\DTO\NewUserDTO;
use App\Entity\User;
use App\Event\NewUserRegisteredEvent;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher, private EntityRepository $entityRepository, private EventDispatcherInterface $dispatcher)
    {
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            return $actions
                ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                    return $action->setIcon('fa fa-plus')->setLabel('Ajouter un compte');
                });
        }

        return $actions->disable(Action::NEW, Action::EDIT, Action::DELETE, Action::DETAIL);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setPageTitle('index', 'Gestion des utilisateurs')
            ->setPageTitle('edit', 'Modifier un utilisateur')
            ->setPageTitle('new', 'Création d\'un utilisateur');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('username', 'Nom d\'utilisateur')
                ->setColumns(6)
                ->setRequired(true),
            TextField::new('email')
                ->setColumns(6)
                ->setRequired(true),
            TextField::new('password', 'Mot de passe')
                ->setColumns(6)
                ->setFormTypeOption('data', '')
                ->setFormTypeOption('attr', ['autocomplete' => 'off'])
                ->onlyOnForms()
                ->setRequired(true),
            ChoiceField::new('roles', 'role de l\'utilisateur')
                ->setChoices([
                    'Vétérinaire' => 'ROLE_VETERINARY',
                    'Employé' => 'ROLE_EMPLOYEE',
                ])->onlyOnIndex(),
            ChoiceField::new('selectedRole', 'role de l\'utilisateur')
                ->setColumns(6)
                ->setChoices([
                    'Vétérinaire' => 'ROLE_VETERINARY',
                    'Employé' => 'ROLE_EMPLOYEE',
                ])
                ->allowMultipleChoices(false)->renderExpanded()
                ->setRequired(true)->onlyWhenCreating(),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, mixed $entityInstance): void
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

        // send mail to new user
        $newUser = new NewUserDTO();
        $newUser->username = $entityInstance->getUsername();
        $newUser->email = $entityInstance->getEmail();

        $this->dispatcher->dispatch(new NewUserRegisteredEvent($newUser));
    }

    public function updateEntity(EntityManagerInterface $entityManager, mixed $entityInstance): void
    {
        if ($entityInstance instanceof User) {
            $hashedPassword = $this->passwordHasher->hashPassword(
                $entityInstance,
                $entityInstance->getPassword()
            );

            $entityInstance->setPassword($hashedPassword);
        }

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $response = $this->entityRepository->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $response->andWhere('entity.roles NOT LIKE :admin ')
            ->setParameter('admin', '%ROLE_ADMIN%');

        return $response;
    }
}

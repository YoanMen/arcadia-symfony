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
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UserCrudController extends AbstractCrudController
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher, private EntityRepository $entityRepository, private EventDispatcherInterface $dispatcher)
    {
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
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
                    return $action->setLabel('Créer et ajouter un nouveau utilisateur');
                })
                ->update(Crud::PAGE_NEW, Action::SAVE_AND_RETURN, function (Action $action) {
                    return $action->setLabel('Créer un utilisateur');
                })
                ->remove(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE)
                ->update(Crud::PAGE_INDEX, Action::NEW, function (Action $action) {
                    return $action->setIcon('fa fa-plus')->setLabel('Ajouter un utilisateur');
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
            TextField::new('username', 'Nom d\'utilisateur')
                ->setColumns(6)
                ->setDisabled(true)
                ->onlyWhenUpdating(),
            TextField::new('email')
                ->setColumns(6)
                ->setDisabled(true)
                ->onlyWhenUpdating(),
            TextField::new('password', 'Mot de passe')
                ->setColumns(6)
                ->onlyOnForms()
                ->setFormType(PasswordType::class)
                ->setRequired(true),
            ChoiceField::new('roles', 'rôle')
                ->setChoices([
                    'Vétérinaire' => 'ROLE_VETERINARY',
                    'Employé' => 'ROLE_EMPLOYEE',
                ])->onlyOnIndex(),
            ChoiceField::new('selectedRole', 'rôle de l\'utilisateur')
                ->setColumns(6)
                ->setChoices([
                    'Vétérinaire' => 'ROLE_VETERINARY',
                    'Employé' => 'ROLE_EMPLOYEE',
                ])
                ->setFormTypeOption('constraints', new Assert\NotBlank(message: 'Vous devez sélectionnez un rôle'))
                ->allowMultipleChoices(false)->renderExpanded()
                ->onlyWhenCreating(),
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

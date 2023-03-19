<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{

    public function __construct(
        public UserPasswordHasherInterface $userPasswordHasher
    ) {}
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Utilisateurs')
            ->setEntityLabelInSingular('Utilisateur')
            ->setPageTitle("index","Administration des utilisateurs");

    }

    public function configureActions(Actions $actions): Actions
    {
        if(!$this->isGranted('ROLE_SUPER_ADMIN')) {
            return $actions
                ->disable(Action::NEW, Action::DELETE);
        } else {
            return $actions;
        }
    }


    public function configureFields(string $pageName): iterable
    {
        $id = IdField::new('id')
            ->hideOnForm();
        $first_name = TextField::new('first_name', 'Prénom');
        if(!$this->isGranted('ROLE_SUPER_ADMIN')) {
            $first_name->setFormTypeOptions([
                'disabled' => true
            ]);
        }

        $last_name = TextField::new('last_name', 'Nom');
        if(!$this->isGranted('ROLE_SUPER_ADMIN')) {
            $last_name->setFormTypeOptions([
                'disabled' => true
            ]);
        }

        $account_name = TextField::new('account_name', 'Nom d\'utilisateur');
        if(!$this->isGranted('ROLE_SUPER_ADMIN')) {
            $account_name->setFormTypeOptions([
                'disabled' => true
            ]);
        }

        $email = TextField::new('email', 'Email');
        if(!$this->isGranted('ROLE_SUPER_ADMIN')) {
            $email->setFormTypeOptions([
                'disabled' => true
            ]);
        }

        $roles = ArrayField::new('roles', 'Rôles');

        $is_verified = BooleanField::new('isVerified', 'Compte vérifié');
        if(!$this->isGranted('ROLE_SUPER_ADMIN')) {
            $is_verified->setFormTypeOptions([
                'disabled' => true
            ]);
        }

        if(Crud::PAGE_NEW === $pageName) {
            $password = TextField::new('password', 'Mot de passe'   );
        } else {
            $password = TextField::new('password', 'Mot de passe')
                ->hideOnIndex()
                ->hideOnForm();
        }

        $is_active = BooleanField::new('isActive', 'Compte actif');

        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            return [$id, $first_name, $last_name, $account_name, $email, $password, $roles, $is_verified, $is_active];
        }  else {
            return [$id, $first_name, $last_name, $account_name, $email, $is_verified, $is_active];
        }

    }

    public function createNewFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createNewFormBuilder($entityDto, $formOptions, $context);
        return $this->addPasswordEventListener($formBuilder);
    }

    private function addPasswordEventListener(FormBuilderInterface $formBuilder): FormBuilderInterface
    {
        return $formBuilder->addEventListener(FormEvents::POST_SUBMIT, $this->hashPassword());
    }

    private function hashPassword() {
        return function($event) {
            $form = $event->getForm();
            if (!$form->isValid()) {
                return;
            }
            $password = $form->get('password')->getData();
            if ($password === null) {
                return;
            }

            $hash = $this->userPasswordHasher->hashPassword($this->getUser(), $password);
            $form->getData()->setPassword($hash);
        };
    }

}

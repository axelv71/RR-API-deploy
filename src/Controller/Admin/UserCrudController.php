<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
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


    public function configureFields(string $pageName): iterable
    {
        $id = IdField::new('id')
            ->hideOnForm();
        $first_name = TextField::new('first_name');
        if(!$this->isGranted('ROLE_SUPER_ADMIN')) {
            $first_name->setFormTypeOptions([
                'disabled' => true
            ]);
        }

        $last_name = TextField::new('last_name');
        if(!$this->isGranted('ROLE_SUPER_ADMIN')) {
            $last_name->setFormTypeOptions([
                'disabled' => true
            ]);
        }

        $account_name = TextField::new('account_name');
        if(!$this->isGranted('ROLE_SUPER_ADMIN')) {
            $account_name->setFormTypeOptions([
                'disabled' => true
            ]);
        }

        $email = TextField::new('email');
        if(!$this->isGranted('ROLE_SUPER_ADMIN')) {
            $email->setFormTypeOptions([
                'disabled' => true
            ]);
        }

        $roles = ArrayField::new('roles');

        $is_verified = BooleanField::new('isVerified');
        if(!$this->isGranted('ROLE_SUPER_ADMIN')) {
            $is_verified->setFormTypeOptions([
                'disabled' => true
            ]);
        }

        if(Crud::PAGE_NEW === $pageName) {
            $password = TextField::new('password');
        } else {
            $password = TextField::new('password')
                ->hideOnIndex()
                ->hideOnForm();
        }




        $is_active = BooleanField::new('isActive');

        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            return [$id, $first_name, $last_name, $account_name, $email, $password, $roles, $is_verified, $is_active];
        }  else {
            return [$id, $first_name, $last_name, $account_name, $email, $is_verified, $is_active];
        }

    }

}

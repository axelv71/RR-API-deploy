<?php

namespace App\Controller\Admin;

use App\Entity\Ressource;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RessourceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Ressource::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $actions
                ->disable(Action::NEW, Action::DELETE);
        } else {
            return $actions;
        }
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Ressources')
            ->setEntityLabelInSingular('Ressource')
            ->setPageTitle('index', 'Administration des ressources')
            ->setPaginatorPageSize(50);
    }

    public function configureFields(string $pageName): iterable
    {
        $id = IdField::new('id')
            ->hideOnForm();
        $category = AssociationField::new('category', 'Catégorie')
            ->hideOnForm();
        $creator = AssociationField::new('creator', 'Créateur')
            ->hideOnForm();
        if ($this->isGranted('ROLE_ADMIN')) {
            $title = TextField::new('title', 'Titre');
        } else {
            $title = TextField::new('title', 'Titre')
                ->setFormTypeOptions([
                    'disabled' => true,
                ]);
        }

        if ($this->isGranted('ROLE_ADMIN')) {
            $description = TextareaField::new('description', 'Description');
        } else {
            $description = TextareaField::new('description', 'Description')
                ->setFormTypeOptions([
                    'disabled' => true,
                ]);
        }

        $isValid = BooleanField::new('isValid', 'Validé');
        $isPublished = BooleanField::new('isPublished', 'Publié');
        $createdAt = DateTimeField::new('createdAt', 'Créé le')
            ->hideOnIndex()
            ->setFormTypeOptions([
                'disabled' => true,
            ]);

        return [
            $id,
            $category,
            $creator,
            $title,
            $description,
            $isValid,
            $isPublished,
            $createdAt,
        ];
    }
}

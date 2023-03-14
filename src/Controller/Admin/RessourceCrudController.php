<?php

namespace App\Controller\Admin;

use App\Entity\Ressource;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class RessourceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Ressource::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Ressources')
            ->setEntityLabelInSingular('Ressource')
            ->setPageTitle("index","Administration des ressources")
            ->setPaginatorPageSize(50);

    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            AssociationField::new('category')
                ->hideOnForm(),
            AssociationField::new('creator')
                ->hideOnForm(),
            TextareaField::new('description'),
            BooleanField::new('isValid'),
            BooleanField::new('isPublished'),
            DateTimeField::new('createdAt')
                ->hideOnIndex(),
        ];
    }

}

<?php

namespace App\Controller\Admin;

use App\Entity\RelationType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RelationTypeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RelationType::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Types de relation')
            ->setEntityLabelInSingular('Type de relation')
            ->setPageTitle('index', 'Administration des types de relations');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('name', 'Nom'),
        ];
    }
}

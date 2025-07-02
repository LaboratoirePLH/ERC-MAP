<?php

namespace App\Controller\Admin;

use App\Entity\TypeSource;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TypeSourceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TypeSource::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['id', 'nomFr', 'nomEn'])
            ->setPaginatorPageSize(30);
    }

    public function configureFields(string $pageName): iterable
    {
        $nomFr = TextField::new('nomFr');
        $nomEn = TextField::new('nomEn');
        $categorieSource = AssociationField::new('categorieSource');
        $id = IntegerField::new('id', 'ID');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $nomFr, $nomEn, $categorieSource];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $nomFr, $nomEn, $categorieSource];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$nomFr, $nomEn, $categorieSource];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$nomFr, $nomEn, $categorieSource];
        }
        return [];
    }
}
